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
    $this->cashierService = $cashierService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'username'            => 'required|unique:cashier',
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
