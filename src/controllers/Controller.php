<?php

/**
 *
 */

namespace App\Controllers;

use Slim\Views\Twig;
use Slim\Router;

/**
 *
 */
abstract class Controller
{
  protected $view;
  protected $router;
  protected $db;

  /**
   *
   */
  public function __construct(Twig $view, Router $router, $db = null)
  {
    $this->view = $view;
    $this->router = $router;
    $this->db = $db;
  }
}
