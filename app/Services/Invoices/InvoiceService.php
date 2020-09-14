<?php

namespace App\Services\Invoices;

use App\Invoices;
use Carbon\Carbon;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class InvoiceService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, Invoices $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
    $this->now = Carbon::now()->toDateTimeString();
  }

  public function saveInvoiceData($request)
  {
    try {
      $invoiceData = [
        'id_cashier'    => $request->input('id_cashier'),
        'id_store'      => $request->input('id_store'),
        'date'          => $this->now,
        'total'         => $request->input('total'),
        'buyer_money'   => $request->input('buyer_money'),
        'change_money'  => $request->input('change_money'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $invoiceData);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Kategori Harga Berhasil ditambahkan", $saveData, false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allInvoicesData()
  {
    try {
      $invoices = $this->model::with('cashier', 'store')->get();

      if (!$invoices || count($invoices) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $invoices, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function invoicesTodayData()
  {
    try {
      $today  = explode(" ", $this->now)[0];
      $invoicesToday = $this->model::with('cashier', 'store')
        ->where('date', 'like', '%' . $today . '%')->get();

      if (!$invoicesToday || count($invoicesToday) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $invoicesToday, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function invoicesDateByStoreData($storeId, $dateFrom, $dateTo)
  {
    try {
      $invoicesDateByStore = $this->model::with('cashier', 'store')
        ->where(['id_store' => $storeId])
        ->whereBetween('date', [$dateFrom, $dateTo])
        ->get();

      if (!$invoicesDateByStore || count($invoicesDateByStore) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $invoicesDateByStore, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function invoicesDateData($dateFrom, $dateTo)
  {
    try {
      $invoicesDate = $this->model::with('cashier', 'store')
        ->whereBetween('date', [$dateFrom, $dateTo])
        ->get();

      if (!$invoicesDate || count($invoicesDate) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $invoicesDate, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function incomeTodayData()
  {
    try {
      $startTime  = explode(" ", $this->now)[0] . ' ' . '00:00:00';
      $endTime    = explode(" ", $this->now)[0] . ' ' . '23:59:00';
      $incomeToday = $this->model::whereBetween('date', [$startTime, $endTime])
        ->get()
        ->sum('total');

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $incomeToday, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function incomeAllData()
  {
    try {
      $incomeToday = $this->model::get()
        ->sum('total');

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $incomeToday, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function invoiceTodayByStoreIdData($storeId)
  {

    try {
      $today  = explode(" ", $this->now)[0];
      $invoiceTodayByStore = $this->model::with('cashier', 'store')
        ->where(['id_store' => $storeId])
        ->where('date', 'like', '%' . $today . '%')->get();

      if (!$invoiceTodayByStore || count($invoiceTodayByStore) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $invoiceTodayByStore, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function invoiceTodayByStoreData($storeId)
  {
    try {
      $startTime  = explode(" ", $this->now)[0] . ' ' . '00:00:00';
      $endTime    = explode(" ", $this->now)[0] . ' ' . '23:59:00';
      $incomeToday = $this->model::where(['id_store' => $storeId])
        ->whereBetween('date', [$startTime, $endTime])
        ->get()
        ->sum('total');

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $incomeToday, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  // public function deletePriceCategory($dataId)
  // {
  //   try {
  //     $deleteData  = $this->DAOService->deleteData($this->model, ['id_price_category' => $dataId]);

  //     if ($deleteData <= 0) {
  //       $response = new ResponsePresentationLayer(404, "Gagal dihapus, Id tidak ditemukan", [], true);
  //     } else {
  //       $response = new ResponsePresentationLayer(204, "Berhasil dihapus", [], false);
  //     }
  //   } catch (\Exception $e) {
  //     $errors[] = $e->getMessage();
  //     $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
  //   }

  //   return $response->getResponse();
  // }

  // public function updatePriceCatagory($request, $dataId)
  // {
  //   try {
  //     $priceCategory = [
  //       'id_product'  => $request->input('id_product'),
  //       'name'        => $request->input('name'),
  //       'price'       => $request->input('price'),
  //     ];

  //     $updateData = $this->DAOService->updateData($this->model, ['id_price_category' => $dataId], $priceCategory);
  //     $updatedData = $this->DAOService->getDataId($this->model, ['id_price_category' => $dataId],);

  //     if ($updateData) {
  //       $response = new ResponsePresentationLayer(201, "Kategori harga Berhasil diupdate", $updatedData, false);
  //     } else {
  //       $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
  //     }
  //   } catch (\Exception $e) {
  //     $errors[] = $e->getMessage();
  //     $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
  //   }

  //   return $response->getResponse();
  // }
}
