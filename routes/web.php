<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstoqueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::resource('estoque', EstoqueController::class);

// Primeira 'tabela',  e utilizado para mostrar a roda no navegador, segunda e terceira o nome na controller da função
Route::get('tabela', [EstoqueController::class, 'tabela'])->name('tabela');
Route::get('Atualiza_prod', [EstoqueController::class, 'Atualiza_prod'])->name('Atualiza_prod');
Route::get('cadastrar', [EstoqueController::class, 'cadastrar_prod'])->name('cadastrar_prod');
Route::get('deletar', [EstoqueController::class, 'deletar_prod'])->name('deletar_prod');
// Route::get('Atualiza_prod', 'EstoqueController@Atualiza_prod');
