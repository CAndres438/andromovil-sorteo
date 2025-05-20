<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContestController;
use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', fn() => Inertia::render('ContestForm'));


Route::middleware(['auth', 'verified'])->get('/dashboard', fn() =>
    auth()->user()?->is_admin ? Inertia::render('Admin/ChooseWinner') : abort(403)
)->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/choose-winner', fn() =>
        auth()->user()?->is_admin ? Inertia::render('Admin/ChooseWinner') : abort(403)
    )->name('admin.choose.winner');

    Route::get('/admin/clients', fn() =>
        auth()->user()?->is_admin ? Inertia::render('Admin/Clients') : abort(403)
    )->name('admin.clients');

    Route::get('/web/clients/export', fn() =>
        auth()->user()?->is_admin ? Excel::download(new ClientsExport, 'participantes.xlsx') : abort(403)
    );
});


Route::middleware('auth')->group(function () {
    Route::post('/web/contest/winner', [ContestController::class, 'chooseWinner']);
    Route::get('/web/contest/clients', [ContestController::class, 'getClients']);
    Route::get('/web/contest/count', [ContestController::class, 'count']);
});




require __DIR__.'/auth.php';
