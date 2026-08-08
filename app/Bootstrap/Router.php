<?php

declare(strict_types=1);

/**
 * Arquivo: Router.php
 * Camada: Bootstrap
 *
 * Responsabilidade:
 *   Roteador mínimo do sistema: associa um método HTTP + caminho a um
 *   par [Controller, método] e despacha a requisição atual para o
 *   Controller correto. É deliberadamente simples (sem parâmetros de
 *   rota tipo {id} — os identificadores continuam vindo por query
 *   string, ?id=, como já era no projeto original "pitter-pan-totem")
 *   para não introduzir uma dependência de framework externo.
 *
 * Usado por:
 *   app/Bootstrap/app.php (única classe que instancia e despacha).
 *
 * Alimentado por:
 *   routes/web.php, routes/api.php (que recebem a instância e
 *   registram as rotas).
 */

namespace App\Bootstrap;

class Router
{
    private array $rotas = [];

    public function get(string $caminho, callable|array $acao): void
    {
        $this->registrar('GET', $caminho, $acao);
    }

    public function post(string $caminho, callable|array $acao): void
    {
        $this->registrar('POST', $caminho, $acao);
    }

    public function put(string $caminho, callable|array $acao): void
    {
        $this->registrar('PUT', $caminho, $acao);
    }

    public function patch(string $caminho, callable|array $acao): void
    {
        $this->registrar('PATCH', $caminho, $acao);
    }

    public function delete(string $caminho, callable|array $acao): void
    {
        $this->registrar('DELETE', $caminho, $acao);
    }

    private function registrar(string $metodo, string $caminho, callable|array $acao): void
    {
        $this->rotas[$metodo][$caminho] = $acao;
    }

    public function despachar(string $metodo, string $caminho): void
    {
        $acao = $this->rotas[$metodo][$caminho] ?? null;

        if ($acao === null) {
            http_response_code(404);
            echo 'Rota não encontrada.';

            return;
        }

        if (is_callable($acao) && !is_array($acao)) {
            $acao();

            return;
        }

        [$classe, $metodoAcao] = $acao;
        (new $classe())->$metodoAcao();
    }
}
