<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return response()->json([
        'auth' => "Unauthenticated",
    ]);
})->name('login');

Route::get('/', function () {
    return redirect('/api/projects');
});
//______________________________________________________________________________
// Route::get('/react', function () {
//     return view('welcome');
// });
Route::view('/{any_path?}', 'welcome')->where('any_path', '(.*)');

