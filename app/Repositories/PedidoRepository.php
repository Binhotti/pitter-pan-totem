<?php

declare(strict_types=1);

/**
 * Arquivo: PedidoRepository.php
 * Camada: Repositories
 *
 * Responsabilidade:
 *   Única classe autorizada a executar queries SQL sobre a tabela
 *   `pedidos`. Migra, com o mesmo comportamento, todas as queries que
 *   estavam soltas dentro de api/orders.php no projeto original
 *   "pitter-pan-totem" (buscar por id, listar com filtro de status e
 *   busca textual, criar com geração de código do tipo "A0001",
 *   atualizar e excluir).
 *
 * Depende de:
 *   app/Bootstrap/Database.php.
 *
 * Usado por:
 *   app/Controllers/Api/PedidoApiController.php.
 */

namespace App\Repositories;

use App\Bootstrap\Database;
use PDO;

class PedidoRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::conexao();
    }

    /**
     * Busca um pedido pelo id. Retorna false se não encontrado.
     */
    public function buscarPorId(int $id): array|false
    {
        $statement = $this->pdo->prepare('SELECT * FROM pedidos WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->fetch();
    }

    /**
     * Lista pedidos, com filtro opcional por status e por busca textual
     * (senha do pedido, ocasião, modelo ou texto do balão).
     */
    public function listar(string $status = '', string $busca = ''): array
    {
        $sql = 'SELECT * FROM pedidos WHERE 1 = 1';
        $parametros = [];

        if ($status !== '') {
            $sql .= ' AND status = :status';
            $parametros['status'] = $status;
        }

        if ($busca !== '') {
            $sql .= ' AND (order_code LIKE :busca OR occasion_name LIKE :busca OR model_name LIKE :busca OR balloon_text LIKE :busca)';
            $parametros['busca'] = '%' . $busca . '%';
        }

        $sql .= ' ORDER BY created_at DESC, id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($parametros);

        return $statement->fetchAll();
    }

    /**
     * Cria um novo pedido e gera o código de senha (ex.: "A0001") a
     * partir do id gerado — mesma regra do projeto original.
     * Retorna o pedido recém-criado já com o código preenchido.
     */
    public function criar(array $dados): array|false
    {
        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO pedidos (
                    order_code, occasion_id, occasion_name, model_id, model_name,
                    balloon_text, font_id, font_name, balloon_color_id, balloon_color_name,
                    text_color_id, text_color_name, quantity, unit_price, total_price, status
                ) VALUES (
                    NULL, :occasion_id, :occasion_name, :model_id, :model_name,
                    :balloon_text, :font_id, :font_name, :balloon_color_id, :balloon_color_name,
                    :text_color_id, :text_color_name, :quantity, :unit_price, :total_price, "recebido"
                )'
            );

            $statement->execute([
                'occasion_id'        => $dados['occasion_id'],
                'occasion_name'      => $dados['occasion_name'],
                'model_id'           => $dados['model_id'],
                'model_name'         => $dados['model_name'],
                'balloon_text'       => $dados['balloon_text'],
                'font_id'            => $dados['font_id'],
                'font_name'          => $dados['font_name'],
                'balloon_color_id'   => $dados['balloon_color_id'],
                'balloon_color_name' => $dados['balloon_color_name'],
                'text_color_id'      => $dados['text_color_id'],
                'text_color_name'    => $dados['text_color_name'],
                'quantity'           => $dados['quantity'],
                'unit_price'         => $dados['unit_price'],
                'total_price'        => $dados['total_price'],
            ]);

            $novoId = (int) $this->pdo->lastInsertId();
            $codigoPedido = 'A' . str_pad((string) $novoId, 4, '0', STR_PAD_LEFT);

            $atualizarCodigo = $this->pdo->prepare('UPDATE pedidos SET order_code = :order_code WHERE id = :id');
            $atualizarCodigo->execute(['order_code' => $codigoPedido, 'id' => $novoId]);

            $this->pdo->commit();

            return $this->buscarPorId($novoId);
        } catch (\PDOException $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Atualiza texto do balão, quantidade, preço unitário e status de
     * um pedido existente, recalculando o total.
     */
    public function atualizar(int $id, array $dados): array|false
    {
        $statement = $this->pdo->prepare(
            'UPDATE pedidos SET
                balloon_text = :balloon_text,
                quantity = :quantity,
                unit_price = :unit_price,
                total_price = :total_price,
                status = :status
             WHERE id = :id'
        );

        $statement->execute([
            'balloon_text' => $dados['balloon_text'],
            'quantity'     => $dados['quantity'],
            'unit_price'   => $dados['unit_price'],
            'total_price'  => $dados['total_price'],
            'status'       => $dados['status'],
            'id'           => $id,
        ]);

        return $this->buscarPorId($id);
    }

    /**
     * Exclui um pedido. Retorna true se alguma linha foi afetada.
     */
    public function excluir(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM pedidos WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }
}
