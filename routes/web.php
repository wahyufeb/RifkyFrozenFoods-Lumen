<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
	return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
	/*
	|--------------------------------------------------------------------------
	| Product Categories Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Product
	*/
	$router->group(['prefix' => 'product-category'], function () use ($router) {
		// Get All
		$router->get('/', 'ProductCategories\ProductCategoryController@productCategories');
		// Delete
		$router->delete('{dataId}/delete', 'ProductCategories\ProductCategoryController@delete');
		// Update
		$router->put('{dataId}/update', 'ProductCategories\ProductCategoryController@update');
		// Create 
		$router->post('/create', 'ProductCategories\ProductCategoryController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Price Categories Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Kategori harga
	*/
	$router->group(['prefix' => 'price-category'], function () use ($router) {
		// Get All
		$router->get('/', 'PriceCategories\PriceCategoryController@priceCategories');
		// Delete
		$router->delete('{dataId}/delete', 'PriceCategories\PriceCategoryController@delete');
		// Update
		$router->put('{dataId}/update', 'PriceCategories\PriceCategoryController@update');
		// Create 
		$router->post('/create', 'PriceCategories\PriceCategoryController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Stores Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Kios
	*/
	$router->group(['prefix' => 'stores'], function () use ($router) {
		// Get All
		$router->get('/', 'Stores\StoreController@stores');
		// Delete
		$router->delete('{dataId}/delete', 'Stores\StoreController@delete');
		// Update
		$router->put('{dataId}/update', 'Stores\StoreController@update');
		// Create 
		$router->post('/create', 'Stores\StoreController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Product Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Produk
	*/
	$router->group(['prefix' => 'products'], function () use ($router) {
		// Get All
		$router->get('/', 'Products\ProductController@products');
		// Total Products
		$router->get('/total-product', 'Products\ProductController@totalProducts');
		// Delete
		$router->delete('{dataId}/delete', 'Products\ProductController@delete');
		// Update
		$router->post('{dataId}/update', 'Products\ProductController@update');
		// Create 
		$router->post('/create', 'Products\ProductController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Invoice Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Fakur
	*/
	$router->group(['prefix' => 'invoice'], function () use ($router) {
		// Get All
		$router->get('/', 'Invoices\InvoiceController@invoices');
		// Get Invoices Today
		$router->get('/today', 'Invoices\InvoiceController@invoicesToday');
		//  Get Invoices by Date from and Date to
		$router->get('/{dateFrom}/{dateTo}', 'Invoices\InvoiceController@invoicesDate');
		// Delete
		// $router->delete('{dataId}/delete', 'Invoices\InvoiceController@delete');
		// Update
		// $router->post('{dataId}/update', 'Invoices\InvoiceController@update');
		// Create 
		$router->post('/create', 'Invoices\InvoiceController@save');
	});
});
