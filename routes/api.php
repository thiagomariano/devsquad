<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('/products', 'Api\ProductController', [
    'except' => ['create'],
]);

//rota colocada como post por causa da imagem que não estava passando pelo get
Route::post('/products-up', 'Api\ProductController@update');
Route::post('/products-import', 'Api\ProductController@productImport');
Route::post('/products/verify-create', 'Api\ProductController@verifyCreate');
Route::post('/products/verify-edit', 'Api\ProductController@verifyEdit');


Route::resource('/categories', 'Api\CategoryController');
Route::put('/categories', 'Api\CategoryController@update');
Route::delete('/categories/{id}', 'Api\CategoryController@delete');

Route::delete('/categories/{id}', 'Api\CategoryController@delete');


