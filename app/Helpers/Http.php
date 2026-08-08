<?php

declare(strict_types=1);

/**
 * Arquivo: Http.php
 * Camada: Helpers
 *
 * Responsabilidade:
 *   Funções utilitárias genéricas para respostas HTTP em JSON e
 *   leitura do corpo da requisição — migradas de config/helpers.php
 *   do projeto original "pitter-pan-totem" (funções globais
 *   jsonResponse() e readJsonBody()), agora agrupadas em uma classe
 *   sob o namespace App\Helpers, sem alterar o comportamento.
 *
 * Usado por:
 *   app/Controllers/Api/*.php, app/Validators/*.php.
 */

namespace App\Helpers;

class Http
{
    /**
     * Envia uma resposta JSON padronizada e encerra a execução.
     * Equivalente a jsonResponse() do projeto original.
     */
    public static function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Lê e decodifica o corpo JSON da requisição.
     * Equivalente a readJsonBody() do projeto original.
     */
    public static function corpoJson(): array
    {
        $bruto = file_get_contents('php://input');

        if ($bruto === false || trim($bruto) === '') {
            return [];
        }

        $dados = json_decode($bruto, true);

        if (!is_array($dados)) {
            self::json(['success' => false, 'message' => 'JSON inválido.'], 400);
        }

        return $dados;
    }
}
