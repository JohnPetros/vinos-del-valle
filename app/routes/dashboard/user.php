<?php

use \App\controllers\UserController;
use \App\core\Response;

$router->get(
  '/dashboard/user',
  function ($request) {
    return new Response(200, UserController::getUserDashboardPage($request));
  }
);

$router->get(
  '/dashboard/user/{id}/form',
  function ($request, $id) {
    return new Response(200, UserController::getUserFormPage($request, $id));
  }
);

$router->post(
  '/dashboard/user/add',
  function ($request) {
    return new Response(200, UserController::addUser($request));
  }
);

$router->post(
  '/dashboard/user/{id}/edit',
  function ($request, $id) {
    return new Response(200, UserController::editUser($request, $id));
  }
);

$router->get(
  '/dashboard/user/{id}/delete',
  function ($request, $id) {
    return new Response(200, UserController::deleteUser($request, $id));
  }
);
