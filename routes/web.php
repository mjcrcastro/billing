<?php

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

Route::get('/', function () {
    return view('master');
});
//Router for the assets  in case I have assets outside the public folder
Route::get('{vendor_name}/{module}/{type}/{file}', [ function ($vendor_name, $module, $type, $file) {
    $module = ucfirst($module);

    $path = app_path("../vendor/$vendor_name/$module/dist/$type/$file");

    if (\File::exists($path)) {
        return response()->download($path, "$file");
    }

    return response()->json([ ], 404);
}]);
//Route for assets

Route::resource('products', 'ProductsController');
Route::resource('descriptors', 'DescriptorsController');
Route::resource('descriptorTypes','DescriptorTypesController');
Route::resource('productTypes','ProductTypesController');
Route::resource('invTransactions','InvTransactionsController');
Route::resource('storages','StoragesController');
Route::get('jdescriptors', array('uses' => 'JsonController@descriptors'));
Route::post('jproducts', array('uses' => 'JsonController@products'));
Route::get('jkardex',array('uses'=>'JsonController@kardex'));