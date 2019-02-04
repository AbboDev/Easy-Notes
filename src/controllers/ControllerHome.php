<?php

/**
 *
 */

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class ControllerHome extends Controller
{
  /**
   *
   */
  public function start(Request $request, Response $response)
  {
    return($this->view->render($response, 'home.twig', [
      "session" => $_SESSION
    ]));
  }
}
