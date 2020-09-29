<?php

namespace App\Services\Transactions;

use App\Transactions;
use App\ProductStorage;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class TransactionService
{
  private $DAOService;
  private $model;
  private $productStorageModel;
  public function __construct(DAOService $DAOService, Transactions $model, ProductStorage $productStorageModel)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
    $this->productStorageModel = $productStorageModel;
  }

  public function transactionsByInvoiceData($invoiceId) 
  {
    try {
      $transactionsByInvoiceId = $this->model::with('product')
      ->where(['id_invoice' => $invoiceId])
      ->get();

      if (!$transactionsByInvoiceId || count($transactionsByInvoiceId) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $transactionsByInvoiceId, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
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

      $id_store = $request->input('id_store');
      $id_product = $transactionData['id_product'];

      $saveData = $this->DAOService->saveData($this->model, $transactionData);
      // Query untuk mengurangi stok di Product Storage
      $getProductStorage = $this->DAOService->getDataId($this->productStorageModel, ['id_product' => $id_product, 'id_store' => $id_store]);
      $currentStock = $getProductStorage->stock - $transactionData['qty'];
      $getProductStorage->update(['stock' => $currentStock]);

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
