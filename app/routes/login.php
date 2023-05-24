<?php

use \App\controllers\LoginController;
use \App\core\Response;

$router->get('/', function ($request) {
  return new Response(200, LoginController::getLoginPage($request));
});

$router->post('/login', function ($request) {
  return new Response(200, LoginController::handleLogin($request));
});

$router->get('/logout', function ($request) {
  return new Response(200, LoginController::handleLogout($request));
});
