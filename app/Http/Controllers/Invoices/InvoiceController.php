<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Invoices\InvoiceService;

class InvoiceController extends Controller
{
  private $invoiceService;

  public function __construct(InvoiceService $invoiceService)
  {
    $this->invoiceService = $invoiceService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'id_cashier'    => 'required',
      'total'         => 'required',
      'buyer_money'   => 'required',
      'change_money'  => 'required',
    ]);
  }

  public function invoices()
  {
    return $this->invoiceService->allInvoices();
  }

  public function invoicesToday()
  {
    return $this->invoiceService->invoicesToday();
  }

  public function invoicesDate($dateFrom, $dateTo)
  {
    $dateFrom = urldecode($dateFrom);
    $dateTo = urldecode($dateTo);
    return $this->invoiceService->invoicesDate($dateFrom, $dateTo);
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->invoiceService->saveInvoice($request);
  }

  // public function delete($dataId)
  // {
  //   return $this->priceCategoryService->deletePriceCategory($dataId);
  // }

  // public function update(Request $request, $dataId)
  // {
  //   $this->validationData($request);
  //   return $this->priceCategoryService->updatePriceCatagory($request, $dataId);
  // }
}
