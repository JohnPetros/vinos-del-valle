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

// $router->post(
//   '/dashboard/grape/add',
//   function ($request) {
//     return new Response(200, UserController::addGrape($request));
//   }
// );

$router->post(
  '/dashboard/user/{id}/edit',
  function ($request, $id) {
    return new Response(200, UserController::editUser($request, $id));
  }
);

// $router->get(
//   '/dashboard/grape/{id}/delete',
//   function ($request, $id) {
//     return new Response(200, UserController::deleteGrape($request, $id));
//   }
// );
