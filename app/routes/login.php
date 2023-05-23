<?php

use \App\controllers\LoginController;
use \App\core\Response;

$router->get('/', function () {
  return new Response(200, LoginController::getLoginPage());
});

$router->post('/login', function ($request) {
  return new Response(200, LoginController::handleLogin($request));
});