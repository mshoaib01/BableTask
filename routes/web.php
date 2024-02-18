<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('login');
}); 


// Display a login form
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/logout', [UserController::class, 'logout'])->name('logout');



// Display a listing of the tasks
Route::get('/tasks/list', [TaskController::class, 'index'])->name('tasks.list');

Route::middleware(['auth:web'])->group(function () {
    // Show the form for creating a new task
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    // Store a newly created task in storage
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Show the form for editing the specified task
    Route::get('/tasks/edit/{task}', [TaskController::class, 'edit'])->name('tasks.edit');

    // Update the specified task in storage
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);
});

Route::middleware(['auth', 'checkUserRole'])->group(function () {
     // Remove the specified task from storage
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

