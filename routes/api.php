<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FitnessClassController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/', [UserController::class, 'create'])->name('users.create')->middleware('auth:sanctum', 'role:admin');

Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')
    ->middleware('auth:sanctum')
;

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'delete'])->name('users.delete');
});

Route::group(['prefix' => 'memberships', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [MembershipController::class, 'index'])->name('membership.index');
    Route::get('/{id}', [MembershipController::class, 'show'])->name('membership.show');
    Route::post('/', [MembershipController::class, 'create'])->name('membership.create');
    Route::post('/{id}/book', [MembershipController::class, 'bookMembership'])->name('membership.book');
    Route::post('/{id}/benefits', [MembershipController::class, 'addBenefits'])->name('membership.addBenefits');
    Route::put('/{membershipId}/benefits/{benefitId}', [MembershipController::class, 'updateBenefit'])->name('membership.updateBenefit');
    Route::put('/{id}   ', [MembershipController::class, 'update'])->name('membership.update');
    Route::delete('/{id}', [MembershipController::class, 'delete'])->name('membership.delete');
});

Route::group(['prefix' => 'fitnessClasses', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [FitnessClassController::class, 'index'])->name('fitnessClasses.index');
    Route::post('/', [FitnessClassController::class, 'create'])->name('fitnessClasses.create');
    Route::post('/{id}/book', [FitnessClassController::class, 'bookFitnessClass'])->name('fitnessClasses.book');
    Route::get('/{id}', [FitnessClassController::class, 'show'])->name('fitnessClasses.show');
    Route::put('/{id}', [FitnessClassController::class, 'update'])->name('fitnessClasses.update');
    Route::delete('/{id}', [FitnessClassController::class, 'delete'])->name('fitnessClasses.delete');
});
