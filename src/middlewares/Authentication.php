<?php

/**
 *
 */
namespace App\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Mappers\MapperUsers;

/**
 *
 */
class Authentication extends Middleware
{
  /**
   *
   */
  public function __invoke(Request $request, Response $response, $next)
  {
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      $user_mapper = new MapperUsers($this->db);
      $user = $user_mapper->getUserByCredential($_SERVER['PHP_AUTH_USER'],
          sha1($_SERVER['PHP_AUTH_PW']));
    }
    if (empty($_SESSION['user']) && (!isset($user))) {
      return $response->withRedirect($this->router->pathFor('log'));
    } else {
      $response = $next($request, $response);
      return $response;
    }
  }
}
