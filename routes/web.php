<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return view("welcome");
});

Auth::routes();

Route::get("/home", [App\Http\Controllers\HomeController::class, "index"])->name("home");
Route::get("/userclassroom", [App\Http\Controllers\HomeController::class, "userclassroom"])->name("userclassroom");
Route::get("/test", [App\Http\Controllers\HomeController::class, "test"])->name("test");

Route::get("/account", [App\Http\Controllers\AccountController::class, "index"]);
Route::put("/account", [App\Http\Controllers\AccountController::class, "update"]);
Route::delete("/account", [App\Http\Controllers\AccountController::class, "delete"]);

Route::get("/invitation/{code}", [App\Http\Controllers\ClassRoomController::class, "invitation"]);
Route::resource("/classroom", App\Http\Controllers\ClassRoomController::class)->except(["create", "edit"]);
Route::get("/classroom/{classroom}/member", [App\Http\Controllers\ClassRoomController::class, "member"]);
Route::get("/classroom/{classroom}/out", [App\Http\Controllers\ClassRoomController::class, "out"]);

Route::resource("/post", App\Http\Controllers\PostController::class)->only(["store", "destroy"]);
Route::resource("/comment", App\Http\Controllers\CommentController::class)->only(["store", "destroy"]);

Route::get("/classroom/{classroom}/attendance", [App\Http\Controllers\AttendanceController::class, "index"]);
Route::resource("/attendance", App\Http\Controllers\AttendanceController::class)->except("index");
Route::get("/attendance/{attendance}/scan", [App\Http\Controllers\AttendanceController::class, "scan"]);
Route::post("/attendance/{attendance}/scan", [App\Http\Controllers\AttendanceController::class, "scandata"]);
Route::get("/attendance/{attendance}/permit", [App\Http\Controllers\AttendanceController::class, "permit"]);
Route::post("/attendance/{attendance}/permit", [App\Http\Controllers\AttendanceController::class, "permitdata"]);
Route::get("/attendance/{attendance}/data", [App\Http\Controllers\AttendanceController::class, "viewdata"]);

Route::get("/classroom/{classroom}/lesson", [App\Http\Controllers\LessonController::class, "index"]);
Route::resource("/lesson", App\Http\Controllers\LessonController::class)->except("index");