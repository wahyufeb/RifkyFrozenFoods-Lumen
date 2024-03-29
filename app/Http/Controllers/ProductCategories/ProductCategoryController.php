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
		// $this->middleware('jwt:admin', ['only' => [
		// 	'delete',
		// 	'save',
		// 	'productCategories',
		// ]]);
		// $this->middleware('jwt:warehouse_admin', ['only' => [
		// 	'productCategories',
		// ]]);
		// $this->middleware('jwt:cashier', ['only' => [
		// 	'productCategories',
		// ]]);
		$this->middleware('jwt');
		$this->productCategoryService = $productCategoryService;
	}

	// validation
	private function validationData($request)
	{
		$this->validate($request, [
			'name'	=> 'required',
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
