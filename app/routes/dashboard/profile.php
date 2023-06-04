<?php

use App\controllers\ProfileController;
use App\core\Response;

$router->get(
  '/dashboard/profile',
  function ($request) {
    return new Response(200, ProfileController::getProfilePage($request));
  }
);