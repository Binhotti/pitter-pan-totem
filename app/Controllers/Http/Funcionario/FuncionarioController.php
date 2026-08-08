<?php

declare(strict_types=1);

/**
 * Arquivo: FuncionarioController.php
 * Camada: Controllers/Http/Funcionario
 *
 * Responsabilidade:
 *   Renderizar a interface de pedidos destinada aos funcionários.
 *   Esta página exibe apenas a lista de pedidos e filtros, mas não
 *   expõe o faturamento da empresa.
 *
 * Rota: GET /funcionarios (ver routes/web.php)
 * Renderiza: resources/views/pages/funcionarios/pedidos.php
 */

namespace App\Controllers\Http\Funcionario;

class FuncionarioController
{
    public function index(): void
    {
        require __DIR__ . '/../../../../resources/views/pages/funcionarios/pedidos.php';
    }
}
