<?php

namespace App\Services\Cashier;

use Illuminate\Support\Facades\Hash;
use App\Cashier;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class CashierService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, Cashier $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function saveCashier($request)
  {
    try {
      $cashier = [
        'username'            => $request->input('username'),
        'photo'               => 'cashier.png',
        'name'                => $request->input('name'),
        'password'            => Hash::make($request->input('password')),
        'id_product_storage'  => $request->input('id_product_storage'),
        'id_store'            => $request->input('id_store'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $cashier);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Kasir Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allCashier()
  {
    try {
      $cashier = $this->model::with('store')->get();

      if (!$cashier || count($cashier) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $cashier, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deleteCashier($cashierId)
  {
    try {
      $deleteData  = $this->DAOService->deleteData($this->model, ['id_cashier' => $cashierId]);

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

  public function updateCashier($request, $cashierId)
  {
    try {
      $cashier = [
        'name'                => $request->input('name'),
        'password'            => Hash::make($request->input('password')),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_cashier' => $cashierId], $cashier);
      $updatedData = $this->DAOService->getDataId($this->model, ['id_cashier' => $cashierId],);

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Data Kasir Berhasil diupdate", $updatedData, false);
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
