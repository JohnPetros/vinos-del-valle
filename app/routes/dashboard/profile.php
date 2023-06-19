<?php

use App\controllers\ProfileController;
use App\core\Response;

$router->get(
  '/dashboard/profile',
  function ($request) {
    return new Response(200, ProfileController::getProfilePage($request));
  }
);

$router->post(
  '/dashboard/profile/edit',
  function ($request) {
    return new Response(200, ProfileController::editProfile($request));
  }
);

$router->get(
  '/dashboard/profile/delete',
  function ($request) {
    return new Response(200, ProfileController::deleteProfile($request));
  }
);