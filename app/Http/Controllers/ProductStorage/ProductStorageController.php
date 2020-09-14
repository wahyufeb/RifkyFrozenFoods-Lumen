<?php

namespace App\Http\Controllers\ProductStorage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\ProductStorage\ProductStorageService;

class ProductStorageController extends Controller
{
  private $productStorageService;

  public function __construct(ProductStorageService $productStorageService)
  {
    $this->productStorageService = $productStorageService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'id_product'    => 'required',
      'id_store'      => 'required',
      'stock'         => 'required',
    ]);
  }

  private function validationExitingItem($request)
  {
    $this->validate($request, [
      'id_product'      => 'required',
      'id_store'        => 'required',
      'stock'           => 'required',
      'id_product_from' => 'required',
      'id_store_from'   => 'required'
    ]);
  }

  public function incomingGoods(Request $request)
  {
    $this->validationData($request);
    return $this->productStorageService->incomingGoods($request);
  }

  public function exitItem(Request $request)
  {
    $this->validationExitingItem($request);
    return $this->productStorageService->exitItem($request);
  }

  public function getByStoreId($storeId)
  {
    return $this->productStorageService->productStorageByIdData($storeId);
  }

  public function totalProductByStoreId($storeId)
  {
    return $this->productStorageService->totalProductByStoreIdData($storeId);
  }

  public function totalStockByStoreId($storeId)
  {
    return $this->productStorageService->totalStockByStoreIdData($storeId);
  }
}
