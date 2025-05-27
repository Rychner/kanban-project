<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::controller(WorkspaceController::class)->group(function () {
    Route::get('workspace/create', 'create')->name('workspace.create');
    Route::post('workspace/create', 'store')->name('workspace.store');
    Route::get('workspace/p/{workspace:slug}', 'show')->name('workspace.show');
    Route::get('workspace/edit/{workspace:slug}', 'edit')->name('workspace.edit');
    Route::put('workspace/edit/{workspace:slug}', 'update')->name('workspace.update');
    Route::delete('workspace/destroy/{workspace:slug}', 'destroy')->name('workspace.destroy');
    Route::post('workspace/member/{workspace:slug}/store', 'member_store')->name('workspace.member_store');
    Route::delete('workspace/member/{workspace:slug}/destroy', 'member_destroy')->name('workspace.member_destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
