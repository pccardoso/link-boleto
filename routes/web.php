<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Inertia\Inertia;
use App\Http\Controllers\SGAController;
use App\Http\Controllers\HashPlateController;
use App\Http\Controllers\UploadHashController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BillController;

# ROTAS PÚBLICAS

Route::get('/', function(){
    return Inertia::render("Home");
});

Route::get('/upload', [HashPlateController::class, 'index']);

Route::get('/search-plate/{cpfOrCnpj}', [SGAController::class, 'consultPlatesUser']);
Route::post('/search-boleto-cpf/{cpfOrCnpj}', [SGAController::class, 'consultBoletUser']);
Route::post('/search-boleto-plate/{plate}', [SGAController::class, 'consultBoletPlate']);
Route::post('/alterar/vencimento-boleto', [SGAController::class, "updateBolet"]);

Route::prefix('hash-plate')->group(function(){

    Route::post('/', [HashPlateController::class, 'store']);
    Route::post('/upload-moovie', [HashPlateController::class, 'uploadMoovie']);

});


Route::prefix('login')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Login');
    })->middleware('guest')->name('login');

    Route::post('/login', [UserController::class, 'login'])->name('user.login');

});

Route::get('/home', function () {
    return Inertia::render('Dashboard');
})->name('home');


#ROTAS PROTEGIDAS

Route::middleware(['auth'])->group(function () {

    Route::prefix('dashboard')->group(function () {

        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/hashes-mes', [HashPlateController::class, 'hashesPorMes']);
        Route::get('/hashes-mes-upload', [HashPlateController::class, 'hashesPorMesComUpload']);

    });

    Route::prefix('users')->group(function () {
    
        Route::get('/', function () {
            return Inertia::render('user/UserView');
        })->name('users.index');

    });

    Route::prefix('links')->group(function () {

        Route::get('/', function () {
            return Inertia::render('link/LinkView');
        })->name('links.index');
        
        Route::get('/all', [HashPlateController::class, 'index'])->name('links.all');
        Route::get('/get-upload/{id_hash}', [UploadHashController::class, 'listUploadHash'])->name('links.detail');

    });

    Route::prefix('bills')->group(function () {

        Route::get('/', function () {
            return Inertia::render('bill/BillView');
        })->name('bills.index');

        Route::get('/all', [BillController::class, 'index'])->name('bills.all');

    }); 

    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');

});