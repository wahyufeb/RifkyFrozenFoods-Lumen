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
		// Ambil seluruh data kategori produk
		$router->get('/', 'ProductCategories\ProductCategoryController@productCategories');

		// Menghapus kategori produk
		$router->delete('{productCategoryId}/delete', 'ProductCategories\ProductCategoryController@delete');

		// Membuat kategori produk baru 
		$router->post('/create', 'ProductCategories\ProductCategoryController@save');

		// Update
		// $router->put('{productCategoryId}/update', 'ProductCategories\ProductCategoryController@update');
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
		// Ambil seluruh data kios dari stores
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
		// Ambil seluruh data produk
		$router->get('/', 'Products\ProductController@products');

		// Hitung jumlah produk yang ada
		$router->get('/total-product', 'Products\ProductController@totalProducts');

		// Menghapus produk
		$router->delete('{productId}/delete', 'Products\ProductController@delete');

		// Mengedit produk
		$router->post('{productId}/update', 'Products\ProductController@update');

		// Membuat produk baru 
		$router->post('/create', 'Products\ProductController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Invoice Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Fakur
	*/
	$router->group(['prefix' => 'invoice'], function () use ($router) {
		// Ambil data invoice dari seluruh store
		$router->get('/', 'Invoices\InvoiceController@invoices');

		// Ambil data invoice diantara dateFrom dan dateTo berdasarkan storeId
		$router->get('/{storeId}/{dateFrom}/{dateTo}', 'Invoices\InvoiceController@invoicesDateByStore');

		//  Ambil data invoice diantara dateFrom dan dateTo
		$router->get('/{dateFrom}/{dateTo}', 'Invoices\InvoiceController@invoicesDate');

		// Jumlahkan seluruh kolom total pada invoice hari ini
		$router->get('/total-income-today', 'Invoices\InvoiceController@invoiceIncomeToday');

		// Jumlah seluruh kolom total pada invoice
		$router->get('/total-income', 'Invoices\InvoiceController@invoiceIncomeAll');

		// Kasir membuat invoice dari suatu transaksi
		$router->post('/create', 'Invoices\InvoiceController@save');

		// Ambil data invoice hari ini berdasarkan storeId
		$router->get('{storeId}/today', 'Invoices\InvoiceController@invoiceTodayByStoreId');

		// Ambil data dan jumlahkan kolom total pada invoice hari ini berdasarkan storeId
		$router->get('{storeId}/total-income-today', 'Invoices\InvoiceController@invoiceIncomeTodayByStore');

		// Delete
		// $router->delete('{dataId}/delete', 'Invoices\InvoiceController@delete');

		// Update
		// $router->post('{dataId}/update', 'Invoices\InvoiceController@update');

		// Get Invoices Today
		// $router->get('/today', 'Invoices\InvoiceController@invoicesToday');

	});

	/*
	|--------------------------------------------------------------------------
	| Transaction Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk membuat transaksi dari suatu invoice
	*/
	$router->group(['prefix' => 'transaction'], function () use ($router) {
		$router->post('/create', 'Transactions\TransactionController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Cashier Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Kasir
	*/
	$router->group(['prefix' => 'users/cashier'], function () use ($router) {
		// Login untuk Cashier
		$router->post('login', 'Cashier\CashierController@login');

		// Refresh Token untuk Cashier
		$router->post('refresh-token', 'Cashier\CashierController@refreshToken');

		// Ambil seluruh data cashier
		$router->get('/', 'Cashier\CashierController@allCashier');

		// Menghapus cashier
		$router->delete('{cashierId}/delete', 'Cashier\CashierController@delete');

		// Mengedit name dan password cashier
		$router->put('{cashierId}/update', 'Cashier\CashierController@update');

		// Membuat cashier baru 
		$router->post('/create', 'Cashier\CashierController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Warehouse Admin Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Admin Gudang
	*/
	$router->group(['prefix' => 'users/warehouse'], function () use ($router) {
		// Login untuk Admin Gudang
		$router->post('login', 'WarehouseAdmin\WarehouseAdminController@login');

		// Refresh Token untuk Admin Gudang
		$router->post('refresh-token', 'WarehouseAdmin\WarehouseAdminController@refreshToken');

		// Ambil seluruh data warehouse admin
		$router->get('/', 'WarehouseAdmin\WarehouseAdminController@allWarehouseAdmin');

		// Menghapus warehouse admin
		$router->delete('{warehouseId}/delete', 'WarehouseAdmin\WarehouseAdminController@delete');

		// Mengedit warehouse admin
		$router->put('{warehouseId}/update', 'WarehouseAdmin\WarehouseAdminController@update');

		// Membuat warehouse admin baru 
		$router->post('/create', 'WarehouseAdmin\WarehouseAdminController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Admin Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Admin
	*/
	$router->group(['prefix' => 'users/admin'], function () use ($router) {
		// Login untuk Admin
		$router->post('login', 'Admin\AdminController@login');

		// Refresh Token untuk Admin
		$router->post('refresh-token', 'Admin\AdminController@refreshToken');

		// Ambil seluruh data admin
		$router->get('/', 'Admin\AdminController@allAdmin');

		// Menghapus admin
		$router->delete('{adminId}/delete', 'Admin\AdminController@delete');

		// Mengedit admin
		$router->put('{adminId}/update', 'Admin\AdminController@update');

		// Membuat admin baru 
		$router->post('create', 'Admin\AdminController@save');
	});

	/*
	|--------------------------------------------------------------------------
	| Product Storage Router
	|--------------------------------------------------------------------------
	| Berisi fitur untuk mengatur Penyimpanan Produk
	*/
	$router->group(['prefix' => 'product-storage'], function () use ($router) {
		// Ambil data produk dari product storage berdasarkan id_store
		$router->get('{storeId}', 'ProductStorage\ProductStorageController@getByStoreId');

		// Menambah stok / data jika belum ada dan mengupdate jika sudah ada pada products_storage
		$router->post('incoming-goods', 'ProductStorage\ProductStorageController@incomingGoods');

		// Mengupdate data dengan mengurangi stok pada products_storage
		$router->post('exit-item', 'ProductStorage\ProductStorageController@exitItem');

		// Hitung jumlah produk yang ada dari product-storage berdasarkan id_store
		$router->get('{storeId}/total-product', 'ProductStorage\ProductStorageController@totalProductByStoreId');

		// Hitung jumlah stok produk dari product-storage berdasarkan id_store
		$router->get('{storeId}/total-stock', 'ProductStorage\ProductStorageController@totalStockByStoreId');
	});
});
