<?php

/**
 *
 */

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Mappers\MapperUsers;
use App\Entities\EntityUser;

/**
 *
 */
class ControllerLog extends Controller
{
  /**
   *
   */
  public function show_log(Request $request, Response $response)
  {
    $error = false;

    if ($request->getParam('error') != null) {
      $error = $request->getParam('error');
    }

    return($this->view->render($response, 'login.twig', [
      "session" => $_SESSION, "error" => $error
    ]));
  }

  /**
   *
   */
  public function login(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $nick = filter_var($data['logEmail'], FILTER_SANITIZE_STRING);
    $pwd = filter_var($data['logPwd'], FILTER_SANITIZE_STRING);

    $user_mapper = new MapperUsers($this->db);
    $user = $user_mapper->getUserByCredential($nick, $pwd);
    if (isset($user)) {
      $_SESSION["user"] = $user->getNickname();
      $_SESSION["color"] = $user->getColor();
      $response = $response->withRedirect($this->router->pathFor('notes'));
      return $response;
    }
    $error = true;
    return $response->withRedirect($this->router->pathFor('log',[], [
    // return($this->view->render($response, 'login.twig', [
      "error" => true
    ]));
  }

  /**
   *
   */
  public function signin(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $params = [];
    $params['nickname'] = filter_var($data['signNick'], FILTER_SANITIZE_STRING);
    $params['first_name']= filter_var($data['signName'], FILTER_SANITIZE_STRING);
    $params['last_name'] = filter_var($data['signSur'], FILTER_SANITIZE_STRING);
    $params['email'] = filter_var($data['signEmail'], FILTER_SANITIZE_STRING);
    $params['password'] = filter_var($data['signPwd'], FILTER_SANITIZE_STRING);
    $spam = filter_var($data['signSpam'], FILTER_SANITIZE_STRING);
    if ($spam) {
      $spam = 0;
    } else {
      $spam = 1;
    }
    $params['spam'] = $spam;

    $user = new EntityUser($params);
    $user_mapper = new MapperUsers($this->db);
    $user_mapper->save($user);
    $_SESSION["user"] = $params['nickname'];

    $response = $response->withRedirect($this->router->pathFor('notes'));
    return $response;
  }

  /**
   *
   */
  public function error(Request $request, Response $response)
  {
    return($this->view->render($response, 'login.twig', [
      "session" => $_SESSION, "error" => true
    ]));
  }

  /**
   *
   */
  public function check(Request $request, Response $response)
  {
    $data = $request->getParsedBody();
    $nick = filter_var($data['nickname'], FILTER_SANITIZE_STRING);

    $user_mapper = new MapperUsers($this->db);
    $user = $user_mapper->getUserByCredential($nick);
    if (isset($user)) {
      $response = $response->getBody()->write(1);
      return $response;
    }
    $response = $response->getBody()->write(0);
    return $response;
  }

  /**
   *
   */
  public function logout(Request $request, Response $response)
  {
    $_SESSION["user"] = null;
    $_SESSION["color"] = null;
    $response = $response->withRedirect($this->router->pathFor('home'));

    return $response;
  }
}
