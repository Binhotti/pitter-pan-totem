<?php

declare(strict_types=1);

/**
 * Arquivo: index.php
 * Camada: public (front controller)
 *
 * Responsabilidade:
 *   Único ponto de entrada PHP do sistema. Delega toda a
 *   inicialização para app/Bootstrap/app.php.
 */

if (PHP_SAPI === 'cli-server') {
    $uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

    $arquivoReal = __DIR__ . $uri;

    if ($uri !== '/' && is_file($arquivoReal)) {
        return false;
    }
}

require __DIR__ . '/../app/Bootstrap/app.php';
