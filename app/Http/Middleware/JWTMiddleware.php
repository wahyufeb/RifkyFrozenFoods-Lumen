<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\ResponsePresentationLayer;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JWTMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $reqHeader = $request->header('Authorization');
    // $reqRole = $request->hedaer('Role');

    if (!$reqHeader) {
      $response = new ResponsePresentationLayer(401, 'Token tidak ada', [], true);
      return $response->getResponse();
    }

    // if (!$reqRole) {
    //   $response = new ResponsePresentationLayer(401, 'Tidak memiliki akses', [], true);
    //   return $response->getResponse();
    // }

    $getToken = explode(" ", $reqHeader);
    $token = $getToken[1];

    if (!$token) {
      $response = new ResponsePresentationLayer(401, 'Token tidak tersedia', [], true);
      return $response->getResponse();
    } else {
      try {
        $credentials = JWT::decode($token, env("APP_KEY"), ["HS256"]);
      } catch (ExpiredException $e) {
        $response = new ResponsePresentationLayer(400, "Token kadaluarsa", [], true);
        return $response->getResponse();
      } catch (\Exception $e) {
        $response = new ResponsePresentationLayer(500, "Token error", [], true);
        return $response->getResponse();
      }

      // if ($credentials->sub->role !== $reqRole) {
      //   $response = new ResponsePresentationLayer(401, 'Tidak memiliki akses', [$role, $credentials->sub->role], true);
      //   return $response->getResponse();
      // }
      $request->tokenSub = $credentials->sub;

      return $next($request);
    }
  }
}
