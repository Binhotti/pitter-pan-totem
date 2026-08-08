<?php

declare(strict_types=1);

/**
 * Arquivo: app.php
 * Camada: Bootstrap
 *
 * Responsabilidade:
 *   Ponto de inicialização da aplicação — a implementação real do
 *   esqueleto descrito em docs/arquitetura-sistema.md, Seção 6 (Fluxo
 *   geral do sistema). Registra o autoload das classes de app/,
 *   carrega as rotas e despacha a requisição atual para o Controller
 *   correspondente.
 *
 * Único arquivo incluído por public/index.php.
 */

// Autoload simples PSR-4-like: App\Foo\Bar -> app/Foo/Bar.php
spl_autoload_register(function (string $classe): void {
    $prefixo = 'App\\';

    if (!str_starts_with($classe, $prefixo)) {
        return;
    }

    $caminhoRelativo = str_replace('\\', '/', substr($classe, strlen($prefixo)));
    $arquivo = __DIR__ . '/../' . $caminhoRelativo . '.php';

    if (is_file($arquivo)) {
        require $arquivo;
    }
});

// Carrega variáveis de ambiente do .env (se existir), sem dependência externa.
$arquivoEnv = __DIR__ . '/../../.env';

if (is_file($arquivoEnv)) {
    foreach (file($arquivoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        if (str_starts_with(trim($linha), '#') || !str_contains($linha, '=')) {
            continue;
        }

        [$chave, $valor] = explode('=', $linha, 2);
        putenv(trim($chave) . '=' . trim($valor));
    }
}

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$basePath = dirname($scriptName);
$basePath = $basePath === '.' || $basePath === '/' || $basePath === '\\' ? '' : rtrim($basePath, '/');

if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', $basePath);
}

$router = new App\Bootstrap\Router();

require __DIR__ . '/../../routes/web.php';
require __DIR__ . '/../../routes/api.php';

$caminho = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$caminho = $caminho === '' ? '/' : $caminho;

if ($basePath !== '' && str_starts_with($caminho, $basePath)) {
    $caminho = substr($caminho, strlen($basePath));
}

$caminho = rtrim($caminho, '/');
$caminho = $caminho === '' ? '/' : $caminho;

$router->despachar($_SERVER['REQUEST_METHOD'] ?? 'GET', $caminho);
