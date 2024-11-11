<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\RecordServicesController;
use App\Http\Controllers\GenerateRecordController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [AuthController::class,'register']);
Route::post('auth/login', [AuthController::class,'login']);

//Востановление пароля
Route::post('/auth/restore', [ResetPasswordController::class, 'forgetPassword']);
Route::post('/auth/restore/confirm', [ResetPasswordController::class, 'resetPassword']);

//UserController

Route::get('/get_users',[UserController::class,'index']);
Route::get('/get_visitor_users',[UserController::class,'getNonWorkers']);
Route::get('/get_worker_users',[UserController::class,'getWorkers']);
Route::get('/get_user/{id}',[UserController::class,'show']);
Route::post('/add_user',[UserController::class,'store']);
Route::put('/update_user/{id}',[UserController::class,'update']);
Route::delete('/delete_user/{id}',[UserController::class,'destroy']);

//PostController

Route::get('/get_posts',[PostController::class,'index']);
Route::get('/get_post/{id}',[PostController::class,'show']);
Route::post('/add_post',[PostController::class,'store']);
Route::put('/update_post/{id}',[PostController::class,'update']);
Route::delete('/delete_post/{id}',[PostController::class,'destroy']);

//ServiceController

Route::get('/get_services',[ServiceController::class,'index']);
Route::get('/get_service/{id}',[ServiceController::class,'show']);
Route::post('/add_service',[ServiceController::class,'store']);
Route::put('/update_service/{id}',[ServiceController::class,'update']);
Route::delete('/delete_service/{id}',[ServiceController::class,'destroy']);

//WorkerController

Route::get('/get_workers',[WorkerController::class,'index']);
Route::get('/get_worker_barber',[WorkerController::class,'getBarbers']);
Route::get('/get_worker_barber_for_mainPage',[WorkerController::class,'getBarbersForMainPage']);
Route::get('/get_worker_admin',[WorkerController::class,'getAdmins']);
Route::get('/get_worker_undefined',[WorkerController::class,'getUndefined']);
Route::get('/get_worker_staff',[WorkerController::class,'getStaff']);
Route::get('/get_worker/{id}',[WorkerController::class,'show']);
Route::post('/add_worker',[WorkerController::class,'store']);
Route::put('/update_worker/{id}',[WorkerController::class,'update']);
Route::delete('/delete_worker/{id}',[WorkerController::class,'destroy']);

//RecordController

Route::post('/records', [RecordController::class,'store']); // Добавление записи
Route::put('/records/{id}', [RecordController::class,'update']); // Редактирование записи
Route::get('/records/{id}', [RecordController::class,'show']); // Получение записи по id
Route::delete('/records/{id}', [RecordController::class,'destroy']); // Удаление записи
Route::get('/records', [RecordController::class,'index']); // Получение всех записей
Route::get('/barber_records/{id}', [RecordController::class,'BarberRecords']); // Получение всех записей

//RecordServicesController

Route::post('/record-services', [RecordServicesController::class, 'store']); // Добавление записи
Route::put('/record-services/{id}', [RecordServicesController::class, 'update']); // Обновление записи
Route::delete('/record-services/{id}', [RecordServicesController::class, 'destroy']); // Удаление записи
Route::get('/record-services', [RecordServicesController::class, 'index']); // Получение всех записей
Route::get('/record-services/{id}', [RecordServicesController::class, 'show']); // Получение одной записи по ID

//GenarateRecordController

Route::post('/generate-records', [GenerateRecordController::class, 'generate']);