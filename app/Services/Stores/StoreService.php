<?php

namespace App\Services\Stores;

use App\Stores;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class StoreService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, Stores $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function saveStore($request)
  {
    try {
      $store = [
        'name' => $request->input('name'),
        'location' => $request->input('location'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $store);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Kios Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allStores()
  {
    try {
      $stores = $this->DAOService->getAllData($this->model);

      if (!$stores || count($stores) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $stores, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deleteStore($dataId)
  {
    try {
      $deleteData  = $this->DAOService->deleteData($this->model, ['id_store' => $dataId]);

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

  public function updateStore($request, $dataId)
  {
    try {
      $productCategory = [
        'name' => $request->input('name'),
        'location' => $request->input('location'),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_store' => $dataId], $productCategory);
      $updatedData = $this->DAOService->getDataId($this->model, ['id_store' => $dataId],);

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Kios Berhasil diupdate", $updatedData, false);
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
