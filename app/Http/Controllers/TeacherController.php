<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeacherRequest;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserExperience;
use App\Models\UserSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\StudentController;

class TeacherController extends Controller
{
    const TEACHERROLE = 3;

    const STATUSPENDING = 1;

    const STATUSAPPROVED = 2;

    const DEFAULTPASSWORD = 'password';

    const STOREPATH = 'public/images/teacher';

    /**
     * Teacher REGISTER API - POST
     */
    public function register(TeacherRequest $request)
    {
        try {
            $regId = $this->userRegister($request);
            if (is_numeric($regId)) {
                return response()->json([
                    'message' => __('messages.teacher.successfully_register'),
                    'teacherId' => $regId,
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
     * Teacher Approved
     */
    public function approved($id)
    {
        try {
            $st = new StudentController();
            $status = $st->isAdmin(); // Check role is admin

            if ($status) {
                return response()->json(['error' => __('messages.error')], 500);
            }

            $teacher = $st->approvedUser($id);

            if ($teacher) {
                return response()->json([
                    'message' => __('messages.teacher.approved'),
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
     * For User Register
     * Duplicate Code in StudentController
     */
    protected function userRegister($request)
    {
        DB::beginTransaction();
        try {
            $data = [
                "name" => $request->input('name'),
                "email" => $request->input('email'),
                'password' => Hash::make(self::DEFAULTPASSWORD),
                'role_id' => self::TEACHERROLE,
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
                    'profile_picture' => $request->file('image')->store(self::STOREPATH),
                    'created_at' => now(),
                ]);
                if ($profileStatus) {
                    LOG::info($request->input('name') . ' Profile Saved');
                }

                $exp = UserExperience::insert([
                    'user_id' => $rs->id,
                    'experience_id' => $request->input('experience'),
                ]);
                if ($exp) {
                    LOG::info($request->input('name') . ' Experience Saved');
                }

                if($request->input('exp_subjects'))
                {
                    $subjects = explode(',',$request->input('exp_subjects'));
                    foreach($subjects as $sub)
                    {
                        $exp = UserSubject::insert([
                            'user_id' => $rs->id,
                            'subject_id' => $sub,
                        ]);
                    }
                }
                if ($exp) {
                    LOG::info($request->input('name') . ' Subject Saved');
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
