<?php

declare(strict_types=1);

/**
 * Arquivo: PedidoAdminController.php
 * Camada: Controllers/Http/Admin
 *
 * Responsabilidade:
 *   Renderizar o painel administrativo de pedidos. Assim como o
 *   TotemController, a tela é estática no servidor — toda a listagem,
 *   filtro e edição acontece client-side via
 *   js/modules/admin/app.js, que consome /api/pedidos.
 *
 * Rota: GET /admin/pedidos (ver routes/web.php)
 * Renderiza: resources/views/pages/admin/pedidos.php
 *
 * NOTA DE SEGURANÇA:
 *   Quando app/Security/ e app/Middlewares/AuthMiddleware.php forem
 *   implementados, esta rota deve passar a exigir autenticação — hoje
 *   ainda está aberta, replicando o estado do projeto original.
 */

namespace App\Controllers\Http\Admin;

class PedidoAdminController
{
    public function index(): void
    {
        require __DIR__ . '/../../../../resources/views/pages/admin/pedidos.php';
    }
}
