<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Models\Experience;
use App\Models\Subject;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("register/student", [StudentController::class, 'register']);
Route::post("register/teacher", [TeacherController::class, 'register']);


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});


Route::middleware(['jwt.auth'])->group(function() {
    Route::get('/user', function() {
        return Auth::user();
    });

    Route::get('/experiences', function() {
        return Experience::all(['id','name']);
    });

    Route::get('/subjects', function() {
        return Subject::all(['id','name']);
    });

    Route::get("student/approved/{id}", [StudentController::class, 'approved']);
    Route::post("assigned/teacher", [StudentController::class, 'assignedTeacher']);

    Route::get("teacher/approved/{id}", [TeacherController::class, 'approved']);

});
