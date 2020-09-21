<?php

namespace App\Services\PriceCategories;

use App\PriceCategories;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class PriceCategoryService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, PriceCategories $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function savePriceCategory($request)
  {
    try {
      $priceCategory = [
        'id_product'  => $request->input('id_product'),
        'name'        => $request->input('name'),
        'price'       => $request->input('price'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $priceCategory);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Kategori Harga Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allPriceCategory()
  {
    try {
      $priceCategory = $this->model::with('products')->get();

      if (!$priceCategory || count($priceCategory) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $priceCategory, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deletePriceCategory($dataId)
  {
    try {
      $deleteData  = $this->DAOService->deleteData($this->model, ['id_product' => $dataId]);

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

  public function updatePriceCatagory($request, $dataId)
  {
    try {
      $priceCategory = [
        'id_product'  => $request->input('id_product'),
        'name'        => $request->input('name'),
        'price'       => $request->input('price'),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_product' => $dataId, 'name' => $priceCategory['name']], $priceCategory);
      $updatedData = $this->DAOService->getDataId($this->model, ['id_product' => $dataId, 'name' => $priceCategory['name']]);

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Kategori harga Berhasil diupdate", $updatedData, false);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }
}
