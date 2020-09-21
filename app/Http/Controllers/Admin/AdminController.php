<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
  private $adminService;

  public function __construct(AdminService $adminService)
  {
    $this->middleware('jwt', ['except' => ['login', 'refreshToken']]);
    $this->adminService = $adminService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'username'            => 'required|unique:admin',
      'name'                => 'required|min:3',
      'password'            => 'required|min:3',
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
    return $this->adminService->loginAdmin($request);
  }

  public function refreshToken(Request $request)
  {
    $this->validate($request, [
      'refresh_token' => 'required'
    ]);
    return $this->adminService->refreshTokenAdmin($request);
  }

  public function authorization($adminId)
  {
    return $this->adminService->authorizationData($adminId);
  }

  public function allAdmin()
  {
    return $this->adminService->allAdmin();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->adminService->saveAdmin($request);
  }

  public function delete($adminId)
  {
    return $this->adminService->deleteAdmin($adminId);
  }

  public function update(Request $request, $adminId)
  {
    $this->updateValidation($request);
    return $this->adminService->updateAdmin($request, $adminId);
  }
}
