<?php

declare(strict_types=1);

/**
 * Arquivo: Pedido.php
 * Camada: Models
 *
 * Responsabilidade:
 *   Documentar a forma (shape) de um Pedido — não é instanciada como
 *   objeto pelo Repository nesta versão (que, por simplicidade e
 *   fidelidade ao comportamento do projeto original, continua
 *   devolvendo arrays associativos vindos direto do PDO::FETCH_ASSOC).
 *   Esta classe existe para documentar formalmente as colunas da
 *   tabela `pedidos` e servir de referência para quem for evoluir o
 *   Repository para retornar objetos tipados no futuro.
 *
 * Corresponde à tabela `pedidos` (ver database/schema/pedidos.sql),
 *   renomeada a partir da tabela `orders` do projeto original
 *   "pitter-pan-totem", para seguir a convenção de nomenclatura do
 *   sistema (tabelas em português, snake_case, plural).
 *
 * Usado por:
 *   app/Repositories/PedidoRepository.php (como referência de
 *   documentação).
 */

namespace App\Models;

class Pedido
{
    public ?int $id = null;
    public ?string $codigoPedido = null;      // coluna: order_code
    public string $ocasiaoId = '';            // coluna: occasion_id
    public string $ocasiaoNome = '';          // coluna: occasion_name
    public string $modeloId = '';             // coluna: model_id
    public string $modeloNome = '';           // coluna: model_name
    public string $textoBalao = '';           // coluna: balloon_text
    public string $fonteId = '';              // coluna: font_id
    public string $fonteNome = '';            // coluna: font_name
    public string $corBalaoId = '';           // coluna: balloon_color_id
    public string $corBalaoNome = '';         // coluna: balloon_color_name
    public string $corTextoId = '';           // coluna: text_color_id
    public string $corTextoNome = '';         // coluna: text_color_name
    public int $quantidade = 1;               // coluna: quantity
    public float $precoUnitario = 0.0;        // coluna: unit_price
    public float $precoTotal = 0.0;           // coluna: total_price
    public string $status = 'recebido';       // coluna: status
    public ?string $criadoEm = null;          // coluna: created_at
}
