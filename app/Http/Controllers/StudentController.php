<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignedTeacherRequest;
use App\Http\Requests\StudentRequest;
use App\Models\StudentTeacher;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\NewUserApproved;
use App\Notifications\NewStudentAssignedNotification;

class StudentController extends Controller
{
    const STUDENTROLE = 2;

    const STATUSPENDING = 1;

    const STATUSAPPROVED = 2;

    const DEFAULTPASSWORD = 'password';

    const STOREPATH = 'public/images/students';

    /**
     * Student REGISTER API - POST
     */
    public function register(StudentRequest $request)
    {
        try {
            $regId = $this->userRegister($request);
            if (is_numeric($regId)) {
                return response()->json([
                    'message' => __('messages.student.successfully_register'),
                    'studentId' => $regId,
                ], 200);
            } else {
                return response()->json(['error' => __('messages.error')], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }

    /**
     * Once the student completes his/her profile the admin is able to approve his/her profile.
     */
    public function approved($id)
    {
        try {
            $status = $this->isAdmin(); // Check role is admin
            if ($status) {
                return response()->json(['error' => __('messages.error')], 500);
            }

            $student = $this->approvedUser($id);
            if (is_numeric($student)) {
                return response()->json([
                    'message' => __('messages.student.approved'),
                ], 200);
            } else {
                return response()->json(['error' => __('messages.error')], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }

    /**
     * Teacher will be assigned to the student by the admin.
     */
    public function assignedTeacher(AssignedTeacherRequest $request)
    {
        try {
            $status = $this->isAdmin(); // Check role is admin
            if ($status) {
                return response()->json(['error' => __('messages.error')], 500);
            }

            $rs = StudentTeacher::updateOrCreate(
                [
                    'user_id' => $request->input('user_id'),
                ],
                [
                    'teacher_id' => $request->input('teacher_id'),
                    'created_at' => now(),
                ]);

            //notification for the teacher, when there is a new student assigned to him
            $teacher = User::find($request->input('teacher_id'));
            $student = User::find($request->input('user_id'));

            $teacher->notify(new NewStudentAssignedNotification($student));

            if ($rs) {
                return response()->json([
                    'message' => __('messages.student.teacher_assigned'),
                ], 200);
            } else {
                return response()->json(['error' => __('messages.error')], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }

    public function isAdmin()
    {
        if (auth('api')->user()->role_id != 1) {
            return true;
        }
        return false;
    }

    public function approvedUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status_id = self::STATUSAPPROVED;
            $user->updated_at = now();
            $user->save();

            event(new NewUserApproved($user));

            return $user->id;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }

    protected function userRegister($request)
    {
        DB::beginTransaction();
        try {
            $data = [
                "name" => $request->input('name'),
                "email" => $request->input('email'),
                'password' => Hash::make(self::DEFAULTPASSWORD),
                'role_id' => self::STUDENTROLE,
                'status_id' => self::STATUSPENDING,
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ];

            $rs = User::create($data);
            if ($rs->id) {
                LOG::info($request->input('name') . ' Saved in Users table');

                $profileStatus = UserProfile::insert([
                    'user_id' => $rs->id,
                    'address' => $request->input('address'),
                    'current_school' => $request->input('current_school'),
                    'previous_school' => $request->input('previous_school'),
                    'parents_details' => $request->input('parents_details'),
                    'profile_picture' => $request->file('image')->store(self::STOREPATH),
                    'created_at' => now(),
                ]);
                if ($profileStatus) {
                    LOG::info($request->input('name') . ' Profile Saved');
                }
            }
            DB::commit();
            return $rs->id;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
