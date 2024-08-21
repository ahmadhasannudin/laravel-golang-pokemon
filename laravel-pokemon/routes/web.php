<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

route::get('/pokemon-list', [PokemonController::class, 'list'])->name('pokemon.list');
route::get('/', [PokemonController::class, 'index'])->name('pokemon.index');
route::prefix('pokemon')->group(function () {
    route::post('/{id}', [PokemonController::class, 'store'])->name('pokemon.store');
    route::get('/{id}', [PokemonController::class, 'detail'])->name('pokemon.detail');
    route::put('/{id}', [PokemonController::class, 'update'])->name('pokemon.update');
    route::delete('/{id}', [PokemonController::class, 'delete'])->name('pokemon.delete');
});
