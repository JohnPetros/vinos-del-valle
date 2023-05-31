<?php

use \App\controllers\UserController;
use \App\core\Response;

$router->get(
  '/dashboard/user',
  function ($request) {
    return new Response(200, UserController::getUserDashboardPage($request));
  }
);

// $router->get(
//   '/dashboard/grape/{id}/form',
//   function ($request, $id) {
//     return new Response(200, UserController::getGrapeFormPage($request, $id));
//   }
// );

// $router->post(
//   '/dashboard/grape/add',
//   function ($request) {
//     return new Response(200, UserController::addGrape($request));
//   }
// );

// $router->post(
//   '/dashboard/grape/{id}/edit',
//   function ($request, $id) {
//     return new Response(200, UserController::editGrape($request, $id));
//   }
// );

// $router->get(
//   '/dashboard/grape/{id}/delete',
//   function ($request, $id) {
//     return new Response(200, UserController::deleteGrape($request, $id));
//   }
// );
