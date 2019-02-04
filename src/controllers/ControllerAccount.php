<?php

/**
 *
 */

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Mappers\MapperUsers;

/**
 *
 */
class ControllerAccount extends Controller
{
  /**
   *
   */
  public function show(Request $request, Response $response)
  {
    $user_mapper = new MapperUsers($this->db);
    $user = $user_mapper->getUserByCredential($_SESSION['user']);
    $_SESSION["color"] = $user->getColor();

    return($this->view->render($response, 'showUser.twig', [
      "user" => $user, "session" => $_SESSION
    ]));
  }

  /**
   *
   */
  public function modify(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $params = [];

    $params['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $params['surname']= filter_var($data['surname'], FILTER_SANITIZE_STRING);
    $params['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $params['spam']= filter_var($data['spam'], FILTER_SANITIZE_STRING);
    $params['color'] = filter_var($data['color'], FILTER_SANITIZE_STRING);
    $params['pwd'] = filter_var($data['pwd'], FILTER_SANITIZE_STRING);

    $user_mapper = new MapperUsers($this->db);

    if ($params['pwd'] == null || $params['pwd'] == "") {
      $user_mapper->modify($params);
    } else {
      $user_mapper->modify_password($params);
    }

    $response = $response->withRedirect($this->router->pathFor('account'));
    return $response;
  }
}
