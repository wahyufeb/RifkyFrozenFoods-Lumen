<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Cashier\CashierService;

class CashierController extends Controller
{
  private $cashierService;

  public function __construct(CashierService $cashierService)
  {
    // $this->middleware('jwt:admin', ['only' => [
    //   'allCashier',
    //   'save',
    //   'delete',
    //   'update',
    //   'refreshToken'
    // ]]);
    $this->middleware('jwt', ['except' => ['login', 'refreshToken']]);
    $this->cashierService = $cashierService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'username'            => 'required|unique:cashier',
      'name'                => 'required',
      'password'            => 'required',
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
    return $this->cashierService->loginCashier($request);
  }

  public function refreshToken(Request $request)
  {
    $this->validate($request, [
      'refresh_token' => 'required'
    ]);
    return $this->cashierService->refreshTokenCashier($request);
  }

  public function authorization($cashierId)
  {
    return $this->cashierService->authorizationData($cashierId);
  }

  public function allCashier()
  {
    return $this->cashierService->allCashier();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->cashierService->saveCashier($request);
  }

  public function delete($cashierId)
  {
    return $this->cashierService->deleteCashier($cashierId);
  }

  public function update(Request $request, $cashierId)
  {
    $this->updateValidation($request);
    return $this->cashierService->updateCashier($request, $cashierId);
  }
}
