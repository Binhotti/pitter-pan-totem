<?php

declare(strict_types=1);

/**
 * Arquivo: AdminDashboardController.php
 * Camada: Controllers/Http/Admin
 *
 * Responsabilidade:
 *   Renderizar a dashboard administrativa com métricas de faturamento.
 *
 * Rota: GET /admin (ver routes/web.php)
 * Renderiza: resources/views/pages/admin/dashboard.php
 */

namespace App\Controllers\Http\Admin;

class AdminDashboardController
{
    public function index(): void
    {
        require __DIR__ . '/../../../../resources/views/pages/admin/dashboard.php';
    }
}
