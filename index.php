<?php

// INCLUI AS CONFIGURAÇÕES DO PROJETO
require __DIR__ . '/config.php';

use \App\core\Router;

// INICIA O ROTEADOR
$router = new Router(URL);

// INCLUI AS ROTAS DO PROJETO
include __DIR__ . '/app/routes/index.php';

// IMPRIME O RESPONSE NA PÁGINA
$router->run()->sendResponse();
