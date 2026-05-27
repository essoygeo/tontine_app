<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Groups\ListGroups;
use App\Livewire\Groups\ShowGroup;
use App\Livewire\Contributions\ListContributions;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Livewire\Dashboard;

Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/groups', ListGroups::class)->name('groups.index');
    Route::get('/groups/{group}', ShowGroup::class)->name('groups.show');
    Route::get('/contributions', ListContributions::class)->name('contributions.index');

    //payement routes
    Route::get('/pay', [PaymentController::class, 'pay'])->name('payment');
    Route::get('/flutterwave/callback', [PaymentController::class, 'callback'])
        ->name('flutterwave.callback');
    Route::get('/payment/status', [PaymentController::class, 'status'])
        ->name('payment.status');
});

Route::post('/flutterwave/webhook', [PaymentController::class, 'webhook'])->name('flutterwave.webhook');

require __DIR__.'/auth.php';
