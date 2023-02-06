<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/get-pokemon', [PokemonController::class, 'getPokemon'])->name('api.pokemon.get');
Route::patch('/update-pokemon/{pokemon}', [PokemonController::class, 'update'])->name('api.pokemon.update');
Route::delete('/delete-pokemon/{pokemon}', [PokemonController::class, 'delete'])->name('api.pokemon.delete');


