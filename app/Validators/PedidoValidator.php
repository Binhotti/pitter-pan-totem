<?php

declare(strict_types=1);

/**
 * Arquivo: PedidoValidator.php
 * Camada: Validators
 *
 * Responsabilidade:
 *   Validar os dados recebidos ao criar/atualizar um pedido (textos,
 *   quantidade, preço, status), antes de chegarem ao Repository.
 *   Migrado de config/helpers.php do projeto original (funções globais
 *   textValue() e allowedStatus()) — mesmo comportamento, agora
 *   agrupado por entidade em vez de disperso em um arquivo de helpers
 *   genérico.
 *
 * Usado por:
 *   app/Controllers/Api/PedidoApiController.php
 */

namespace App\Validators;

use App\Helpers\Http;

class PedidoValidator
{
    private const STATUS_PERMITIDOS = ['recebido', 'producao', 'pronto', 'finalizado', 'cancelado'];

    /**
     * Valida e retorna um campo de texto. Interrompe a requisição com
     * 422 se obrigatório e vazio, ou se exceder o tamanho máximo.
     * Equivalente a textValue() do projeto original.
     */
    public static function texto(array $dados, string $chave, int $tamanhoMaximo = 120, bool $obrigatorio = true): string
    {
        $valor = trim((string) ($dados[$chave] ?? ''));

        if ($obrigatorio && $valor === '') {
            Http::json(['success' => false, 'message' => "O campo {$chave} é obrigatório."], 422);
        }

        if (mb_strlen($valor) > $tamanhoMaximo) {
            Http::json(['success' => false, 'message' => "O campo {$chave} excede {$tamanhoMaximo} caracteres."], 422);
        }

        return $valor;
    }

    /**
     * Equivalente a allowedStatus() do projeto original.
     */
    public static function statusPermitido(string $status): bool
    {
        return in_array($status, self::STATUS_PERMITIDOS, true);
    }
}
