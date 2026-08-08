<?php

declare(strict_types=1);

/**
 * Arquivo: PedidoApiController.php
 * Camada: Controllers/Api
 *
 * Responsabilidade:
 *   Receber as requisições da API de pedidos (GET/POST/PUT/PATCH/DELETE)
 *   e orquestrar Validator + Repository + resposta HTTP. É a migração
 *   direta de api/orders.php do projeto original "pitter-pan-totem" —
 *   mesmo comportamento, mesmos campos, mesmas mensagens — agora
 *   dividido entre Controller (orquestração), Validator (validação) e
 *   Repository (SQL), em vez de um único arquivo de 200+ linhas.
 *
 * Rotas (ver routes/api.php):
 *   GET    /api/pedidos            -> index()   (lista, ou um único
 *                                     registro se ?id= for informado)
 *   POST   /api/pedidos            -> armazenar()
 *   PUT    /api/pedidos?id=        -> atualizar()
 *   PATCH  /api/pedidos?id=        -> atualizar()
 *   DELETE /api/pedidos?id=        -> excluir()
 *
 * Depende de:
 *   app/Repositories/PedidoRepository.php, app/Validators/PedidoValidator.php,
 *   app/Helpers/Http.php.
 */

namespace App\Controllers\Api;

use App\Helpers\Http;
use App\Repositories\PedidoRepository;
use App\Validators\PedidoValidator;
use PDOException;

class PedidoApiController
{
    private PedidoRepository $repositorio;

    public function __construct()
    {
        $this->repositorio = new PedidoRepository();
    }

    public function index(): void
    {
        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($id) {
                $pedido = $this->repositorio->buscarPorId($id);

                if (!$pedido) {
                    Http::json(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
                }

                Http::json(['success' => true, 'data' => $pedido]);
            }

            $status = trim((string) ($_GET['status'] ?? ''));
            $busca = trim((string) ($_GET['search'] ?? ''));

            Http::json(['success' => true, 'data' => $this->repositorio->listar($status, $busca)]);
        } catch (PDOException $exception) {
            $this->erroBanco($exception);
        }
    }

    public function armazenar(): void
    {
        try {
            $dados = Http::corpoJson();

            $occasionId = PedidoValidator::texto($dados, 'occasionId', 60);
            $occasionName = PedidoValidator::texto($dados, 'occasionName', 120);
            $modelId = PedidoValidator::texto($dados, 'modelId', 60);
            $modelName = PedidoValidator::texto($dados, 'modelName', 120);
            $message = PedidoValidator::texto($dados, 'message', 45, false);
            $fontId = PedidoValidator::texto($dados, 'fontId', 60);
            $fontName = PedidoValidator::texto($dados, 'fontName', 120);
            $balloonColorId = PedidoValidator::texto($dados, 'balloonColorId', 60);
            $balloonColorName = PedidoValidator::texto($dados, 'balloonColorName', 120);
            $textColorId = PedidoValidator::texto($dados, 'textColorId', 60);
            $textColorName = PedidoValidator::texto($dados, 'textColorName', 120);
            $quantity = filter_var($dados['quantity'] ?? null, FILTER_VALIDATE_INT);
            $unitPrice = filter_var($dados['unitPrice'] ?? null, FILTER_VALIDATE_FLOAT);

            if (!$quantity || $quantity < 1 || $quantity > 20) {
                Http::json(['success' => false, 'message' => 'Quantidade inválida.'], 422);
            }

            if ($unitPrice === false || $unitPrice < 0) {
                Http::json(['success' => false, 'message' => 'Preço unitário inválido.'], 422);
            }

            $pedidoCriado = $this->repositorio->criar([
                'occasion_id'        => $occasionId,
                'occasion_name'      => $occasionName,
                'model_id'           => $modelId,
                'model_name'         => $modelName,
                'balloon_text'       => $message,
                'font_id'            => $fontId,
                'font_name'          => $fontName,
                'balloon_color_id'   => $balloonColorId,
                'balloon_color_name' => $balloonColorName,
                'text_color_id'      => $textColorId,
                'text_color_name'    => $textColorName,
                'quantity'           => $quantity,
                'unit_price'         => $unitPrice,
                'total_price'        => round($unitPrice * $quantity, 2),
            ]);

            Http::json([
                'success' => true,
                'message' => 'Pedido cadastrado com sucesso.',
                'data'    => $pedidoCriado,
            ], 201);
        } catch (PDOException $exception) {
            $this->erroBanco($exception);
        }
    }

    public function atualizar(): void
    {
        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                Http::json(['success' => false, 'message' => 'Informe o ID do pedido.'], 400);
            }

            $existente = $this->repositorio->buscarPorId($id);

            if (!$existente) {
                Http::json(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
            }

            $dados = Http::corpoJson();

            $status = trim((string) ($dados['status'] ?? $existente['status']));

            if (!PedidoValidator::statusPermitido($status)) {
                Http::json(['success' => false, 'message' => 'Status inválido.'], 422);
            }

            $quantity = filter_var($dados['quantity'] ?? $existente['quantity'], FILTER_VALIDATE_INT);
            $unitPrice = filter_var($dados['unitPrice'] ?? $existente['unit_price'], FILTER_VALIDATE_FLOAT);

            if (!$quantity || $quantity < 1 || $quantity > 20 || $unitPrice === false || $unitPrice < 0) {
                Http::json(['success' => false, 'message' => 'Quantidade ou preço inválido.'], 422);
            }

            $message = isset($dados['message'])
                ? PedidoValidator::texto($dados, 'message', 45, false)
                : $existente['balloon_text'];

            $pedidoAtualizado = $this->repositorio->atualizar($id, [
                'balloon_text' => $message,
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
                'total_price'  => round($unitPrice * $quantity, 2),
                'status'       => $status,
            ]);

            Http::json(['success' => true, 'message' => 'Pedido atualizado.', 'data' => $pedidoAtualizado]);
        } catch (PDOException $exception) {
            $this->erroBanco($exception);
        }
    }

    public function excluir(): void
    {
        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                Http::json(['success' => false, 'message' => 'Informe o ID do pedido.'], 400);
            }

            if (!$this->repositorio->excluir($id)) {
                Http::json(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
            }

            Http::json(['success' => true, 'message' => 'Pedido excluído.']);
        } catch (PDOException $exception) {
            $this->erroBanco($exception);
        }
    }

    private function erroBanco(PDOException $exception): never
    {
        Http::json([
            'success' => false,
            'message' => 'Erro ao acessar o banco de dados.',
            'details' => $exception->getMessage(),
        ], 500);
    }
}
