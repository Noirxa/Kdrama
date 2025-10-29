<?php

use App\Http\Controllers\KdramaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController; // <-- 1. HIER TOEGEVOEGD

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about-us/{id}', function($id) {
    $company = 'Hogeschool Rotterdam';
    return view('about-us', [
        'company' => $company,
        'id' => $id
    ]);
});

// LET OP: Je definieert 'kdramas' hier twee keer.
// De eerste (met middleware) wordt overschreven door de tweede.
// Route::resource('kdramas', KdramaController::class)->middleware('auth');
Route::resource('kdramas', KdramaController::class);

Route::post('/kdramas/{kdrama}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
//        Als een niet-ingelogde gebruiker nu probeert een review te plaatsen, wordt hij automatisch naar de inlogpagina gestuurd.
    ->middleware('auth');

Route::resource('reviews', ReviewController::class)->except(['index']);

Route::get('/contact-pagina', function() {
    return 'This page is contacting us
    you can reach us with the number +31 12345678
    email: test@gmail.com';
});

//Route::get('product/{id}', function ($id) {
//    return view('product', ['id' => $id]);
//});

Route::get('/form-pagina', function() {
    return '
        <form action="/contact-pagina" method="POST">
            '.csrf_field().'
            <input type="text" name="name" placeholder="Naam"><br>
            <input type="email" name="email" placeholder="E-mail"><br>
            <textarea name="message" placeholder="Bericht"></textarea><br>
            <button type="submit">Verstuur</button>
        </form>
    ';
});

Route::post('/contact-pagina', function () {
    return 'Bedankt voor je bericht! We nemen zo snel mogelijk contact met je op.';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- 2. HIER DE NIEUWE ADMIN ROUTES TOEGEVOEGD ---
Route::middleware(['auth', 'admin'])->group(function () {

    // De pagina die de lijst met alle gebruikers toont
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // De POST-route die de Ajax-call afhandelt
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggleStatus');

});
// --- EINDE NIEUWE ADMIN ROUTES ---

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
