<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainDataController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/fwc/register', [MainDataController::class, 'create'])->name('fwc.register');
Route::post('/fwc/register', [MainDataController::class, 'store']);
Route::middleware(['auth'])->get('/fwc', [MainDataController::class, 'index'])->name('fwc.index');
Route::get('/fwc', [MainDataController::class, 'index'])->middleware('auth')->name('fwc.index');
// route for store
Route::post('/fwc/store', [MainDataController::class, 'store'])->middleware('auth')->name('fwc.store');
// route for destroy
Route::delete('/fwc/{id_fwc}', [MainDataController::class, 'destroy'])->name('fwc.destroy')->middleware('auth');

// route redeem index
Route::get('/redeem', [RedeemController::class, 'index'])->name('redeem.index')->middleware('auth');
Route::get('/redeem/register', [RedeemController::class, 'create'])->name('redeem.create');
Route::post('/redeem/store', [RedeemController::class, 'store'])->name('redeem.store');
Route::post('/redeem', [RedeemController::class, 'store'])->name('redeem.store');
Route::get('/redeem/fetch', [RedeemController::class, 'fetch'])->name('redeem.fetch'); // AJAX lookup
Route::get('/redeem/autocomplete-idfwc', [RedeemController::class, 'autocompleteByIDFWC'])->name('redeem.autocompleteByIDFWC');

Route::get('/fwc/subdata/{id_fwc}', function ($id_fwc) {
    return response()->json(
        \App\Models\SubData::where('id_fwc', $id_fwc)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id_pesanan'   => $item->id_pesanan,
                    'tgl_departure'=> $item->tgl_departure ? \Carbon\Carbon::parse($item->tgl_departure)->format('Y-m-d') : null,
                    'user'         => $item->user,
                    'created_at'   => $item->created_at->timezone('Asia/Jakarta')->format('Y-m-d'),
                ];
            })
    );
});


Route::get('user-management', [UserManagementController::class, 'index'])->name('user.index')->middleware('auth');
Route::get('user-management/create', [UserManagementController::class, 'create'])->name('user.create')->middleware('auth');
Route::post('user-management/store', [UserManagementController::class, 'store'])->name('user.store');
Route::get('user-management/{userManagement}', [UserManagementController::class, 'show'])->name('user.show')->middleware('auth');
Route::get('user-management/{userManagement}/edit', [UserManagementController::class, 'edit'])->name('user.edit')->middleware('auth');
Route::put('user-management/{userManagement}', [UserManagementController::class, 'update'])->name('user.update')->middleware('auth');
// Route::delete('user-management/{userManagement}', [UserManagementController::class, 'destroy'])->name('user.destroy')->middleware('auth');
Route::delete('user-management/{id}', [UserManagementController::class, 'destroy'])->name('user.destroy');

// routes/web.php
Route::get('/fwc/export/main', [MainDataController::class, 'exportMain'])->name('fwc.export.main');
Route::get('/fwc/export/sub', [MainDataController::class, 'exportSub'])->name('fwc.export.sub');


require __DIR__.'/auth.php';
