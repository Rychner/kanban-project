<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\MemberCardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
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
    Route::delete('workspace/member/{workspace}/destroy/{member}', 'member_destroy')->name('workspace.member_destroy');
});

Route::controller(CardController::class)->group(function () {
    Route::get('card/{workspace:slug}/create', 'create')->name('card.create');
    Route::post('card/{workspace:slug}/store', 'store')->name('card.store');
    Route::get('card/{workspace:slug}/detail/{card}', 'show')->name('card.show');
    Route::get('card/{workspace:slug}/edit/{card}', 'edit')->name('card.edit');
    Route::put('card/{workspace:slug}/edit/{card}', 'update')->name('card.update');
    Route::post('card/{workspace:slug}/{card}/reorder', 'reorder')->name('card.reorder');
    Route::delete('card/{workspace:slug}/destroy/{card}', 'destroy')->name('card.destroy');
})->middleware('auth');

Route::controller(MemberCardController::class)->group(function () {
    Route::post('card/member/{card}/store', 'member_store')->name('member_card.store');
    Route::delete('card/member/{card}/destroy/{member}', 'member_destroy')->name('member_card.destroy');
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
