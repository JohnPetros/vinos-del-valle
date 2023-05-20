<?php

require __DIR__ . '/vendor/autoload.php';

use \App\controllers\LoginController;

echo LoginController::getLoginPage();
