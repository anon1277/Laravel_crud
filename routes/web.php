<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdministratorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





//----------------------------------guest middleware ---login not required-------------------------------------------
Route::group(['middleware' => "guest"], function () {
   // --------------------------------Routes For Login And Registration For Users --------------------------------
Route::get('register', [UserController::class ,'view_register_page'])->name('register');
Route::post('register', [UserController::class ,'register'])->name('register_action');
Route::get('login', [UserController::class ,'view_login_page'])->name('login');
Route::post('login', [UserController::class ,'login'])->name('login_action');
Route::get('/', [UserController::class ,'view_login_page']);


    // --------------------------------Routes For Login And Registration For Users --------------------------------
 Route::get('admin/register', [AdministratorController::class ,'view_register_page'])->name('admin.register');
 Route::post('admin/register', [AdministratorController::class ,'register'])->name('admin.register_action');
 Route::get('admin/login', [AdministratorController::class ,'view_login_page'])->name('admin.login');
 Route::post('admin/login', [AdministratorController::class ,'login'])->name('admin.login_action');

 });

 Route::get('/admin/dashboard', [AdministratorController::class, 'index'])->name('admin.index');
//---------------------auth middleare ----login must required to accesee these routes ---------------------------------
Route::group(['middleware' => "auth"], function () {

Route::get('/logout',[UserController::class ,'logout'])->name('logout');

Route::get('/admin/dashboard', [AdministratorController::class, 'index'])->name('admin.index');
//---------------------------------- EmployeeController Route --------------------------------
// Route::get('dashboard', [EmployeeController::class ,'index'])->name('dashboard');
Route::get('/dashboard', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/test', [EmployeeController::class, 'test_index'])->name('employees.test');



// ---------------------------EmployeeController Crud--------------------------------
// Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

});
