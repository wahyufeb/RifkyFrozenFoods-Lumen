<?php

namespace App\Http\Controllers\WarehouseAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\WarehouseAdmin\WarehouseAdminService;

class WarehouseAdminController extends Controller
{
  private $warehouseAdminService;

  public function __construct(WarehouseAdminService $warehouseAdminService)
  {
    // $this->middleware('jwt:warehouse_admin', ['only' => [
    //   'allWarehouseAdmin',
    //   'save',
    //   'delete',
    //   'update',
    //   'refreshToken'
    // ]]);
    $this->middleware('jwt', ['except' => ['login', 'refreshToken']]);
    $this->warehouseAdminService = $warehouseAdminService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'username'            => 'required|unique:warehouse_admin',
      'name'                => 'required|min:3',
      'password'            => 'required|min:3',
      'id_product_storage'  => 'required',
      'id_store'            => 'required',
    ]);
  }


  private function updateValidation($request)
  {
    $this->validate($request, [
      'name'                => 'required|min:3',
      'password'            => 'required|min:3',
    ]);
  }

  public function login(Request $request)
  {
    $this->validate($request, [
      'username'  => 'required',
      'password'  => 'required'
    ]);
    return $this->warehouseAdminService->loginWarehouseAdmin($request);
  }

  public function refreshToken(Request $request)
  {
    $this->validate($request, [
      'refresh_token' => 'required'
    ]);
    return $this->warehouseAdminService->refreshTokenWarehouseAdmin($request);
  }

  public function authorization($warehouseId)
  {
    return $this->warehouseAdminService->authorizationData($warehouseId);
  }

  public function allWarehouseAdmin()
  {
    return $this->warehouseAdminService->allWarehouseAdmin();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->warehouseAdminService->saveWarehouseAdmin($request);
  }

  public function delete($warehouseId)
  {
    return $this->warehouseAdminService->deleteWarehouseAdmin($warehouseId);
  }

  public function update(Request $request, $warehouseId)
  {
    $this->updateValidation($request);
    return $this->warehouseAdminService->updateWarehouseAdmin($request, $warehouseId);
  }
}
