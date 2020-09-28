<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Transactions\TransactionService;

class TransactionController extends Controller
{
  private $transactionService;

  public function __construct(TransactionService $transactionService)
  {
    // $this->middleware('jwt:cashier', ['only' => [
    //   'save',
    // ]]);
    $this->middleware('jwt');
    $this->transactionService = $transactionService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'id_invoice'  => 'required',
      'id_product'  => 'required',
      'qty'         => 'required',
      'subtotal'    => 'required',
    ]);
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->transactionService->saveTransaction($request);
  }

  public function transactionsByInvoice($invoiceId)
  {
    return $this->transactionService->transactionsByInvoiceData($invoiceId);
  }

}
