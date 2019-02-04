<?php
// Routes

//route for the home page
$app->get('/[home]', 'HomeController:start')->setName("home"); //HTML Rendering

//group of routes for Notes actions
$app->group('/note', function () {
  $this->get('/all', 'NotesController:show')->setName('notes'); //HTML Rendering
  $this->get('/get', 'NotesController:get_note'); //RestAPI -> get JSON
  $this->get('/get_all', 'NotesController:get_all'); //RestAPI -> get JSON
  $this->get('/get_branch', 'NotesController:get_branch')->setName('show-note'); //RestAPI -> get JSON
  $this->get('/filter', 'NotesController:get_filter')->setName('update-notes'); //RestAPI -> get JSON
  $this->get('/new_child[_{id}]', 'NotesController:add_new')->setName('new-note'); //HTML Rendering
  $this->get('/new[_{subject}]', 'NotesController:add_new_by_subject')->setName('new-note-subject'); //HTML Rendering
  $this->post('/new', 'NotesController:send_new')->setName('add-note'); //RestAPI -> add Note
  $this->group('/{id}', function () {
    $this->get('', 'NotesController:get_note')->setName('show-note-id'); //RestAPI -> get JSON
    $this->get('/info', 'NotesController:show_one')->setName('note'); //HTML Rendering
    $this->get('/delete', 'NotesController:delete')->setName('del-note'); //RestAPI -> delete Note
    $this->get('/modify', 'NotesController:modify')->setName('mod-note'); //HTML Rendering
    $this->post('/update', 'NotesController:update')->setName('update-note'); //RestAPI -> change Note
    $this->post('/check', 'LogController:check')->setName('note-check');
  });
})->add('AuthenticationMW');

//group of routes for log actions
$app->group('/log', function () {
  $this->get('', 'LogController:show_log')->setName('log'); //HTML Rendering
  $this->post('/login', 'LogController:login')->setName('login');
  $this->post('/signin', 'LogController:signin')->setName('signin'); //RestAPI -> add User
  $this->post('/check', 'LogController:check')->setName('check');
})->add('LoginMW');
$app->get('/log/logout', 'LogController:logout')->setName('logout');

$app->group('/account', function () {
  $this->get('','AccountController:show')->setName('account');
  $this->post('/modify','AccountController:modify')->setName('mod-account'); //RestAPI -> modify User
})->add('AuthenticationMW');

$app->get('/test', function ($request, $response) {
  return(var_dump($request->getHeader('Authorization')));
  // return($this->view->render($response, 'test.twig'));
})->setName('test');
