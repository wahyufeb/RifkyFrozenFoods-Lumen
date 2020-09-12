<?php

namespace App\Http\Controllers\PriceCategories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\PriceCategories\PriceCategoryService;

class PriceCategoryController extends Controller
{
  private $priceCategoryService;

  public function __construct(PriceCategoryService $priceCategoryService)
  {
    $this->priceCategoryService = $priceCategoryService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'id_product'  => 'required',
      'name'  => 'required|min:3',
      'price'  => 'required',
    ]);
  }

  public function priceCategories()
  {
    return $this->priceCategoryService->allPriceCategory();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->priceCategoryService->savePriceCategory($request);
  }

  public function delete($dataId)
  {
    return $this->priceCategoryService->deletePriceCategory($dataId);
  }

  public function update(Request $request, $dataId)
  {
    $this->validationData($request);
    return $this->priceCategoryService->updatePriceCatagory($request, $dataId);
  }
}
