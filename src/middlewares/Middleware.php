<?php

/**
 *
 */
namespace App\Middlewares;
use Slim\Views\Twig;
use Slim\Router;

/**
 *
 */
abstract class Middleware
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
