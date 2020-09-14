<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Products\ProductService;

class ProductController extends Controller
{
  private $productService;

  public function __construct(ProductService $productService)
  {
    $this->productService = $productService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'image'                 => 'required|image|mimes:jpeg,png,jpg|max:2048',
      'name'                  => 'required|min:3',
      'id_product_category'   => 'required',
      'total_perunit'         => 'required',
    ]);
  }

  public function products()
  {
    return $this->productService->allProducts();
  }

  public function totalProducts()
  {
    return $this->productService->totalProducts();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->productService->saveProduct($request);
  }

  public function delete($productId)
  {
    return $this->productService->deleteProduct($productId);
  }

  public function update(Request $request, $productId)
  {
    $this->validationData($request);
    return $this->productService->updateProduct($request, $productId);
  }
}
