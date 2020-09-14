<?php

namespace App\Http\Controllers\ProductCategories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\ProductCategories\ProductCategoryService;

class ProductCategoryController extends Controller
{
	private $productCategoryService;

	public function __construct(ProductCategoryService $productCategoryService)
	{
		$this->productCategoryService = $productCategoryService;
	}

	// validation
	private function validationData($request)
	{
		$this->validate($request, [
			'name'	=> 'required|min:3',
		]);
	}

	public function productCategories()
	{
		return $this->productCategoryService->allProductCategory();
	}

	public function save(Request $request)
	{
		$this->validationData($request);
		return $this->productCategoryService->saveProductCategory($request);
	}

	public function delete($productCategoryId)
	{
		return $this->productCategoryService->deleteProductCategory($productCategoryId);
	}

	public function update(Request $request, $productCategoryId)
	{
		$this->validationData($request);
		return $this->productCategoryService->updateProductCatagory($request, $productCategoryId);
	}
}
