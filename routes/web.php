    <?php

    use App\Http\Controllers\KdramaController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\ReviewController;


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
    Route::resource('products', ProductController::class);

//    Route::get('/kdrama', [KdramaController::class, 'index']);


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

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
