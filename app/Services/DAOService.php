<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Services\ResponsePresentationLayer;

class DAOService
{
  // JWT Token Generate
  private function generateJwtToken($data)
  {
    $key = env('APP_KEY');
    $payload = [
      'iss' => 'RIFKYFROZENFOODS',
      'sub' => $data,
      'iat' => time(),
      'exp' => time() + 60 * 60
    ];

    return JWT::encode($payload, $key);
  }

  // Login Process
  public function loginProcess($model, $role,  $request)
  {
    $username = $request->input('username');
    $password = $request->input('password');

    try {
      $userData = $this->getDataId($model, ['username' => $username]);

      if (!$userData) {
        $response = new ResponsePresentationLayer(404, "Username Tidak ditemukan", [], true);
        return $response->getResponse();
      }

      if (Hash::check($password, $userData->password)) {
        $idRole = 'id_' . $role;
        $tokenData = [
          'role' => $role,
          'id'  => $userData->$idRole,
        ];

        $token = $this->generateJwtToken($tokenData);
        $userData['token'] = $token;

        $credentials = JWT::decode($token, env("APP_KEY"), ["HS256"]);

        $subEncrypt = Crypt::encryptString($credentials->sub->id);
        $userData['refresh'] = $subEncrypt;

        $response = new ResponsePresentationLayer(200, "Login Sukses", [$userData, $credentials->sub->role], false);
      } else {
        $response = new ResponsePresentationLayer(401, "Password Anda Salah", [], true);
      }
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    }
    return $response->getResponse();
  }

  // refresh token
  public function refreshTokenProcess($model, $role,  $request)
  {
    $refreshToken = $request->input('refresh_token');
    try {
      $fixRefreshToken = Crypt::decryptString($refreshToken);
      $idRole = 'id_' . $role;

      $userData = $this->getDataId($model, [$idRole => $fixRefreshToken]);

      if (!$userData) {
        $response = new ResponsePresentationLayer(403, "Tidak dapat diakses", [], true);
        return $response->getResponse();
      }

      $tokenData = [
        'role' => $role,
        'id'  => $userData->$idRole,
      ];

      $token = $this->generateJwtToken($tokenData);
      $userData['token'] = $token;

      $credentials = JWT::decode($token, env("APP_KEY"), ["HS256"]);

      $subEncrypt = Crypt::encryptString($credentials->sub->id);
      $userData['refresh'] = $subEncrypt;

      $response = new ResponsePresentationLayer(200, "Login Sukses", $userData, false);
    } catch (\Exception $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(500, "Terjadi kesalahan pada server", [], $errors);
    } catch (DecryptException $e) {
      $errors[] = $e->getMessage();
      $response = new ResponsePresentationLayer(403, "Tidak memiliki akses", [], $errors);
    }
    return $response->getResponse();
  }

  public function saveData($table, $data)
  {
    return $table::create($data);
  }

  public function getAllData($table)
  {
    return $table::all();
  }

  public function getDataId($table, $fieldId)
  {
    return $table::where($fieldId)->first();
  }

  public function countData($table)
  {
    return $table::all()->count();
  }

  public function updateData($table, $fieldId, $data)
  {
    return $table::where($fieldId)->update($data);
  }

  public function updateSingleField($table, $fieldId, $data, $fieldUpdated)
  {
    return $table::where($fieldId)->update([$fieldUpdated => $data]);
  }

  public function deleteData($table, $fieldId)
  {
    return $table::where($fieldId)->delete();
  }
}
