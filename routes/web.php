<?php

/**
 * Arquivo: web.php
 * Camada: routes
 *
 * Responsabilidade:
 *   Mapa das rotas de página (HTML) do sistema. Recebe a instância
 *   $router criada em app/Bootstrap/app.php e registra cada rota.
 */

/** @var App\Bootstrap\Router $router */

// Rota raiz redireciona para o totem após a remoção da landing page
$router->get('/', function () use ($basePath): void {
    $destino = $basePath === '' ? '/totem' : $basePath . '/totem';

    header('Location: ' . $destino);
    exit;
});

$router->get('/totem', [App\Controllers\Http\TotemController::class, 'index']);
$router->get('/funcionarios', [App\Controllers\Http\Funcionario\FuncionarioController::class, 'index']);
$router->get('/admin', [App\Controllers\Http\Admin\AdminDashboardController::class, 'index']);
$router->get('/admin/pedidos', [App\Controllers\Http\Admin\PedidoAdminController::class, 'index']);
