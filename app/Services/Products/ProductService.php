<?php

namespace App\Services\Products;

use App\Products;
use Illuminate\Support\Str;
use App\Services\ResponsePresentationLayer;
use App\Services\DAOService;

class ProductService
{
  private $DAOService;
  private $model;
  public function __construct(DAOService $DAOService, Products $model)
  {
    $this->DAOService = $DAOService;
    $this->model = $model;
  }

  public function saveProduct($request)
  {
    try {
      $imageName = $this->uploadPhoto($request, null);
      $products = [
        'image'                 => $imageName,
        'name'                  => $request->input('name'),
        'id_product_category'   => $request->input('id_product_category'),
        'total_perunit'         => $request->input('total_perunit'),
      ];

      $saveData = $this->DAOService->saveData($this->model, $products);
      $getDataProduct = $this->model::with('category', 'price')
        ->where(['id_product' => $saveData->id_product])
        ->get();

      if ($saveData) {
        $response = new ResponsePresentationLayer(201, "Produk Berhasil ditambahkan", $getDataProduct[0], false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function allProducts()
  {
    try {
      $products = $this->model::with('category', 'price')->get();

      if (!$products || count($products) == 0) {
        $response = new ResponsePresentationLayer(404, "Data Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $products, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function totalProducts()
  {
    try {
      $products = $this->DAOService->countData($this->model);

      $response = new ResponsePresentationLayer(200, "Data Berhasil ditemukan", $products, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function deleteProduct($productId)
  {
    try {
      $imageName = $this->DAOService->getDataId($this->model, ['id_product' => $productId])->image;

      if ($imageName !== '') {
        $destinationPath = './uploads/products/';
        $fullPathImage = $destinationPath . '/' . $imageName;

        if (file_exists($fullPathImage)) {
          unlink($fullPathImage);
        }

        $deleteData  = $this->DAOService->deleteData($this->model, ['id_product' => $productId]);

        if ($deleteData <= 0) {
          $response = new ResponsePresentationLayer(404, "Gagal dihapus", [], true);
        } else {
          $response = new ResponsePresentationLayer(204, "Berhasil dihapus", [], false);
        }
      } else {
        $response = new ResponsePresentationLayer(404, "Gagal dihapus, Id tidak ditemukan", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function updateProduct($request, $productId)
  {
    try {
      $imageName = $this->uploadPhoto($request, $productId);
      $products = [
        'image'                 => $imageName,
        'name'                  => $request->input('name'),
        'id_product_category'   => $request->input('id_product_category'),
        'total_perunit'         => $request->input('total_perunit'),
      ];

      $updateData = $this->DAOService->updateData($this->model, ['id_product' => $productId], $products);
      $updatedData = $this->model::with('category', 'price')
      ->where(['id_product' => $productId])
      ->get();

      if ($updateData) {
        $response = new ResponsePresentationLayer(201, "Produk Berhasil diubah", $updatedData[0], false);
      } else {
        $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }

  public function uploadPhoto($request, $productId)
  {
    try {
      if ($request->hasFile('image')) {
        $reqPhoto = $request->file('image');
        $extension = $reqPhoto->getClientoriginalExtension();

        $destinationPath = './uploads/products/';

        $photoName = Str::random(15);
        $fixPhotoName = $photoName . '.' . $extension;

        if ($productId != null) {
          $imageData = $this->DAOService->getDataId($this->model, ['id_product' => $productId]);
          $fullPathImage = $destinationPath . '/' . $imageData->image;
          if ($reqPhoto->move($destinationPath, $fixPhotoName)) {
            // hapus file image yang lama
            if (file_exists($fullPathImage)) {
              unlink($fullPathImage);
            }

            // update data image di database
            $this->DAOService->updateSingleField($this->model, ['id_product' => $productId], $fixPhotoName, 'image');
            return $fixPhotoName;
          } else {
            $response = new ResponsePresentationLayer(500, "Terjadi Kesalahan pada saat Upload Foto", [], true);
          }
        } else {
          if ($request->file('image')->move($destinationPath, $fixPhotoName)) {
            return $fixPhotoName;
          } else {
            $response = new ResponsePresentationLayer(500, "Terjadi Kesalahan pada saat Upload Foto", [], true);
          }
        }
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }

    return $response->getResponse();
  }
}
