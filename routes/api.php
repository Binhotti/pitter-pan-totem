<?php

/**
 * Arquivo: api.php
 * Camada: routes
 *
 * Responsabilidade:
 *   Mapa das rotas de API (JSON). Migra o comportamento de
 *   api/orders.php do projeto original "pitter-pan-totem" (um único
 *   arquivo que tratava GET/POST/PUT/PATCH/DELETE por
 *   $_SERVER['REQUEST_METHOD']) para rotas explícitas, mantendo o
 *   mesmo padrão de identificar o registro via query string (?id=)
 *   usado pelo front-end original (public/js/modules/admin/app.js).
 */

/** @var App\Bootstrap\Router $router */

$router->get('/api/pedidos', [App\Controllers\Api\PedidoApiController::class, 'index']);
$router->post('/api/pedidos', [App\Controllers\Api\PedidoApiController::class, 'armazenar']);
$router->put('/api/pedidos', [App\Controllers\Api\PedidoApiController::class, 'atualizar']);
$router->patch('/api/pedidos', [App\Controllers\Api\PedidoApiController::class, 'atualizar']);
$router->delete('/api/pedidos', [App\Controllers\Api\PedidoApiController::class, 'excluir']);
