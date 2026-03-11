<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Inertia\Inertia;
use App\Http\Controllers\SGAController;
use App\Http\Controllers\HashPlateController;

Route::get('/', function(){
    return Inertia::render("Home");
});

Route::get('/upload', [HashPlateController::class, 'index']);

Route::get('/search-plate/{cpfOrCnpj}', [SGAController::class, 'consultPlatesUser']);
Route::post('/search-boleto-cpf/{cpfOrCnpj}', [SGAController::class, 'consultBoletUser']);
Route::post('/search-boleto-plate/{plate}', [SGAController::class, 'consultBoletPlate']);
Route::post('/alterar/vencimento-boleto/{codigoBoleto}', [SGAController::class, "updateBolet"]);

Route::prefix('hash-plate')->group(function(){

    Route::post('/', [HashPlateController::class, 'store']);
    Route::post('/upload-moovie', [HashPlateController::class, 'uploadMoovie']);

});