<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Hash;
use App\Admin;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class AdminService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, Admin $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function loginAdmin($request)
  {
    return $this->DAOService->loginProcess($this->model, 'admin', $request);
  }

  public function refreshTokenAdmin($request)
  {
    return $this->DAOService->refreshTokenProcess($this->model, 'admin', $request);
  }

  public function saveAdmin($request)
  {
    try {
      $adminData = [
        'photo'               => 'admin.png',
        'username'            => $request->input('username'),
        'name'                => $request->input('name'),
        'password'            => Hash::make($request->input('password')),
      ];

      $saveData = $this->DAOService->saveData($this->model, $adminData);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Admin Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function authorizationData($adminId)
  {
    try {
      $adminData = $this->DAOService->getDataId($this->model, ['id_admin' => $adminId]);

      if (!$adminData) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $adminData, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allAdmin()
  {
    try {
      $adminData = $this->DAOService->getAllData($this->model);

      if (!$adminData || count($adminData) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $adminData, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deleteAdmin($adminId)
  {
    try {
      $deleteData  = $this->DAOService->deleteData($this->model, ['id_admin' => $adminId]);

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

  public function updateAdmin($request, $adminId)
  {
    try {
      $adminData = [
        'name'                => $request->input('name'),
        'password'            => Hash::make($request->input('password')),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_admin' => $adminId], $adminData);
      $updatedData = $this->DAOService->getDataId($this->model, ['id_admin' => $adminId],);

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Data Admin Berhasil diupdate", $updatedData, false);
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
