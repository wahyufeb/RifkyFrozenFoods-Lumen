<?php

namespace App\Services\WarehouseAdmin;

use Illuminate\Support\Facades\Hash;
use App\WarehouseAdmin;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class WarehouseAdminService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, WarehouseAdmin $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function loginWarehouseAdmin($request)
  {
    return $this->DAOService->loginProcess($this->model, 'warehouse_admin', $request);
  }

  public function refreshTokenWarehouseAdmin($request)
  {
    return $this->DAOService->refreshTokenProcess($this->model, 'warehouse_admin', $request);
  }

  public function saveWarehouseAdmin($request)
  {
    try {
      $warehouseAdmin = [
        'username'            => $request->input('username'),
        'photo'               => 'warehouse.png',
        'name'                => $request->input('name'),
        'password'            => Hash::make($request->input('password')),
        'id_product_storage'  => $request->input('id_product_storage'),
        'id_store'            => $request->input('id_store'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $warehouseAdmin);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Admin Gudang Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allWarehouseAdmin()
  {
    try {
      $warehouseAdmin = $this->model::with('store')->get();

      if (!$warehouseAdmin || count($warehouseAdmin) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $warehouseAdmin, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deleteWarehouseAdmin($warehouseId)
  {
    try {
      $deleteData  = $this->DAOService->deleteData($this->model, ['id_warehouse_admin' => $warehouseId]);

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

  public function updateWarehouseAdmin($request, $warehouseId)
  {
    try {
      $warehouseAdmin = [
        'name'                => $request->input('name'),
        'password'            => Hash::make($request->input('password')),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_warehouse_admin' => $warehouseId], $warehouseAdmin);
      $updatedData = $this->DAOService->getDataId($this->model, ['id_warehouse_admin' => $warehouseId],);

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Data Admin Gudang Berhasil diupdate", $updatedData, false);
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
