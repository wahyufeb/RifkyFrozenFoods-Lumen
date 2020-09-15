<?php

namespace App\Http\Controllers\Stores;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Stores\StoreService;

class StoreController extends Controller
{
  private $storeService;

  public function __construct(StoreService $storeService)
  {
    // $this->middleware('jwt', ['only' => [
    //   'stores',
    // ]]);
    // $this->middleware('jwt:admin', ['only' => [
    //   'stores',
    // ]]);
    $this->middleware('jwt');
    $this->storeService = $storeService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'name'      => 'required|min:3',
      'location'  => 'required|min:3',
    ]);
  }

  public function stores()
  {
    return $this->storeService->allStores();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->storeService->saveStore($request);
  }

  public function delete($dataId)
  {
    return $this->storeService->deleteStore($dataId);
  }

  public function update(Request $request, $dataId)
  {
    $this->validationData($request);
    return $this->storeService->updateStore($request, $dataId);
  }
}
