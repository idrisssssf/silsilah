<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeluargaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('anak/{nama}', [KeluargaController::class, 'getSemuaAnak']);
Route::get('cucu/{nama}', [KeluargaController::class, 'getSemuaCucu']);
Route::get('cucu/perempuan/{nama}', [KeluargaController::class, 'getCucuPerempuan']);
Route::get('bibi/{nama}', [KeluargaController::class, 'getBibi']);
Route::get('sepupu/laki/{nama}', [KeluargaController::class, 'getSepupuLaki']);

//API CRUD
Route::post('anggota', [KeluargaController::class, 'createAnggota']);
Route::get('anggota', [KeluargaController::class, 'getSemuaAnggota']);
Route::get('anggota/{nama}', [KeluargaController::class, 'getDetailAnggota']);
Route::put('anggota/{nama}', [KeluargaController::class, 'editAnggota']);
Route::delete('anggota/{nama}', [KeluargaController::class, 'deleteAnggota']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
