<?php

use \App\controllers\LoginController;
use \App\core\Response;

$router->get('/page/{id}', function ($id) {
  return new Response(200, 'Kaue' . $id);
});

$router->get('/', function () {
  return new Response(200, LoginController::getLoginPage());
});

