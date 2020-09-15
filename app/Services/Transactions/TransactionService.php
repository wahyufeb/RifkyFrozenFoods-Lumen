<?php

namespace App\Services\Transactions;

use App\Transactions;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class TransactionService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, Transactions $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function saveTransaction($request)
  {
    try {
      $transactionData = [
        'id_invoice'  => $request->input('id_invoice'),
        'id_product'  => $request->input('id_product'),
        'qty'         => $request->input('qty'),
        'subtotal'    => $request->input('subtotal'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $transactionData);

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Transaksi Berhasil ditambahkan", $saveData, false);
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
