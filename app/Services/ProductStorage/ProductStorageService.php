<?php

namespace App\Services\ProductStorage;

use App\ProductStorage;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class ProductStorageService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, ProductStorage $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function incomingGoods($request)
  {
    try {
      $productData = [
        'id_product'    => $request->input('id_product'),
        'id_store'      => $request->input('id_store'),
        'stock'         => $request->input('stock'),
      ];

      $whereProductStore = [
        'id_product'  => $productData['id_product'],
        'id_store'    => $productData['id_store']
      ];

      $getDataProductStorage = $this->model::with('product.category', 'product.price', 'store')
        ->where($whereProductStore)
        ->get();

      if (count($getDataProductStorage) == 0) {
        $this->model::with('product.category', 'product.price', 'store')->create($productData);
        $getData = $this->model::with('product.category', 'product.price', 'store')
          ->where($whereProductStore)->get();
        $saveData = $getData[0];
      } else {
        $getDataProductStorage[0]->stock += $productData['stock'];
        $getDataProductStorage[0]->save();
        $saveData = $getDataProductStorage[0];
      }

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Produk Berhasil ditambahkan ke Penyimpanan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function exitItem($request)
  {
    try {
      $productData = [
        'id_product'    => $request->input('id_product'),
        'id_store'      => $request->input('id_store'),
        'stock'         => $request->input('stock'),
      ];

      $whereProductStoreWarehouse = [
        'id_product'  => $request->input('id_product_from'),
        'id_store'    => $request->input('id_store_from')
      ];

      $whereProductStore = [
        'id_product'  => $request->input('id_product'),
        'id_store'    => $request->input('id_store')
      ];

      // Ambil data product storage di gudang (warehouse)
      $getProductStorageWarehouse = $this->model::with('product.category', 'product.price', 'store')
        ->where($whereProductStoreWarehouse)
        ->get();
      // mengupdate stok di gudang (warehouse)
      $getProductStorageWarehouse[0]->stock -= $productData['stock'];
      $getProductStorageWarehouse[0]->save();

      // Ambil data product storage di kios (store)
      $getProductStorageStore = $this->model::with('product.category', 'product.price', 'store')
        ->where($whereProductStore)
        ->get();

      if (count($getProductStorageStore) == 0) {
        $dataProductStorageStore = $this->model::with('product.category', 'product.price', 'store')->create($productData);
      } else {
        $getProductStorageStore[0]->stock += $productData['stock'];
        $getProductStorageStore[0]->save();
        $dataProductStorageStore = $getProductStorageStore;
      }

      if ($getProductStorageWarehouse && $getProductStorageStore) {
        $response = new ResponsePresentationLayer(201, "Produk Berhasil ditambahkan dari Gudang ke Kios", [
          'dari'    => $getProductStorageWarehouse[0],
          'tujuan'  => $dataProductStorageStore,
        ], false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function productStorageByIdData($storeId)
  {
    try {
      $productStorageId = $this->model::with('product.category', 'product.price', 'store')
        ->where(['id_store' => $storeId])
        ->get();

      if (!$productStorageId || count($productStorageId) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $productStorageId, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function totalProductByStoreIdData($storeId)
  {
    try {
      $totalProductByStore = $this->model::where(['id_store' => $storeId])
        ->count('id_product');

      if (!$totalProductByStore) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $totalProductByStore, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function totalStockByStoreIdData($storeId)
  {
    try {
      $totalStockByStore = $this->model::where(['id_store' => $storeId])
        ->get()
        ->sum('stock');

      if (!$totalStockByStore) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $totalStockByStore, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }
}
