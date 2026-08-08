<?php

declare(strict_types=1);

/**
 * Arquivo: TotemController.php
 * Camada: Controllers/Http
 *
 * Responsabilidade:
 *   Renderizar a tela do totem de autoatendimento. Como a tela é
 *   totalmente estática no lado do servidor (todo o dinamismo é
 *   client-side, via js/modules/totem/*.js), este Controller apenas
 *   inclui a View — sem buscar dados de banco nesta etapa.
 *
 * Rota: GET /totem (ver routes/web.php)
 * Renderiza: resources/views/pages/totem/index.php
 */

namespace App\Controllers\Http;

class TotemController
{
    public function index(): void
    {
        require __DIR__ . '/../../../resources/views/pages/totem/index.php';
    }
}
