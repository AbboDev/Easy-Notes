<?php

/**
 *
 */
namespace App\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class LoginChecker extends Middleware
{
  /**
   *
   */
  public function __invoke(Request $request, Response $response, $next)
  {
    if (!isset($_SESSION['user'])) {
      $_SESSION['user'] = null;
    }

    if ($_SESSION['user'] == null) {
      $response = $next($request, $response);
      return $response;
    } else {
      return $response->withRedirect($this->router->pathFor('notes'));
    }
  }
}
