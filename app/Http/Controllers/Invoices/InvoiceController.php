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
    // $this->middleware('jwt:admin', ['only' => [
    //   'invoicesDate',
    //   'invoicesDateByStore',
    //   'invoices',
    //   'invoiceIncomeToday',
    //   'invoiceIncomeAll',
    // ]]);
    // $this->middleware('jwt:warehouse_admin', ['only' => [
    //   'invoicesDate',
    //   'invoicesDateByStore',
    //   'invoices',
    //   'invoiceIncomeToday',
    // ]]);
    // $this->middleware('jwt:cashier', ['only' => [
    //   'invoiceIncomeTodayByStore',
    //   'invoicesDateByStore',
    //   'invoiceTodayByStoreId',
    //   'save',
    // ]]);
    $this->middleware('jwt');
    $this->invoiceService = $invoiceService;
  }

  // validation
  private function validationData($request)
  {
    $this->validate($request, [
      'id_cashier'    => 'required',
      'id_store'      => 'required',
      'total'         => 'required',
      'buyer_money'   => 'required',
      'change_money'  => 'required',
    ]);
  }

  public function invoices()
  {
    return $this->invoiceService->allInvoicesData();
  }

  public function invoicesToday()
  {
    return $this->invoiceService->invoicesTodayData();
  }

  public function invoicesDateByStore($storeId, $dateFrom, $dateTo)
  {
    $dateFrom = urldecode($dateFrom);
    $dateTo = urldecode($dateTo);
    return $this->invoiceService->invoicesDateByStoreData($storeId, $dateFrom, $dateTo);
  }

  public function invoicesDate($dateFrom, $dateTo)
  {
    $dateFrom = urldecode($dateFrom);
    $dateTo = urldecode($dateTo);
    return $this->invoiceService->invoicesDateData($dateFrom, $dateTo);
  }

  public function invoiceIncomeToday()
  {
    return $this->invoiceService->incomeTodayData();
  }

  public function invoiceIncomeAll()
  {
    return $this->invoiceService->incomeAllData();
  }

  public function save(Request $request)
  {
    $this->validationData($request);
    return $this->invoiceService->saveInvoiceData($request);
  }

  public function invoiceTodayByStoreId($storeId)
  {
    return $this->invoiceService->invoiceTodayByStoreIdData($storeId);
  }

  public function invoiceIncomeTodayByStore($storeId)
  {
    return $this->invoiceService->invoiceTodayByStoreData($storeId);
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
