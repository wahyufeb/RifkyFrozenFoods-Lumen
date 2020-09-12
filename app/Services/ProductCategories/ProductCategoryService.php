<?php

namespace App\Services\ProductCategories;

use App\ProductCategories;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class ProductCategoryService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, ProductCategories $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function saveProductCategory($request)
  {
    try {
      $productCategory = [
        'name' => $request->input('name'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $productCategory);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Kategori Produk Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allProductCategory()
  {
    try {
      $productCategories = $this->DAOService->getAllData($this->model);

      if (!$productCategories || count($productCategories) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $productCategories, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deleteProductCategory($dataId)
  {
    try {
      $deleteData  = $this->DAOService->deleteData($this->model, ['id_product_category' => $dataId]);

      if ($deleteData <= 0) {
        $response = new ResponsePresentationLayer(404, "Gagal dihapus, Id tidak ditemukan", [], true);
      } else {
        $response = new ResponsePresentationLayer(204, "Berhasil dihapus", [], false);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function updateProductCatagory($request, $dataId)
  {
    try {
      $productCategory = [
        'name' => $request->input('name'),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_product_category' => $dataId], $productCategory);
      $updatedData = $this->DAOService->getDataId($this->model, ['id_product_category' => $dataId],);

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Kategori Produk Berhasil diupdate", $updatedData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }
}
