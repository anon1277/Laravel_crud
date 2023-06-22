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


//  Route::get('admin-dashboard', [AdministratorController::class, 'index'])->name('admin1.index');

//----------------------------------guest middleware ---login not required-------------------------------------------
Route::group(['middleware' => "guest"], function () {
        // --------------------------------Routes For Login And Registration For Users --------------------------------
        Route::get('register', [UserController::class ,'view_register_page'])->name('register');
        Route::post('register', [UserController::class ,'register'])->name('register_action');
        Route::get('login', [UserController::class ,'view_login_page'])->name('login');
        Route::post('login', [UserController::class ,'login'])->name('login_action');
        Route::get('/', [UserController::class ,'view_login_page']);


        Route::get('employee-register', [EmployeeController::class ,'view_register_page'])->name('employee.register');
        Route::post('employee-register', [EmployeeController::class ,'register'])->name('employee.register_action');
        Route::get('employee-login', [EmployeeController::class ,'view_login_page'])->name('employee.login');
        Route::post('employee-login', [EmployeeController::class ,'login'])->name('employee.login_action');

 });

//---------------------auth middleare----login must required to accesee these routes ---------------------------------
Route::group(['middleware' => "auth"], function () {
        Route::get('/logout',[UserController::class ,'logout'])->name('logout');


        Route::get('employee-dashboard', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/dashboard', [EmployeeController::class, 'index'])->name('admin.index'); //admin dashboard
        Route::get('/test', [EmployeeController::class, 'test_index'])->name('employees.test');

        //--------------------------------Route For Admin Crud --------------------------------

        // ---------------------------EmployeeController Crud--------------------------------
        // Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

});
