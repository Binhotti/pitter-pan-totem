<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

try {
    $pdo = getPDO();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if ($method === 'GET') {
        if ($id) {
            $statement = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
            $statement->execute(['id' => $id]);
            $order = $statement->fetch();

            if (!$order) {
                jsonResponse(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
            }

            jsonResponse(['success' => true, 'data' => $order]);
        }

        $status = trim((string)($_GET['status'] ?? ''));
        $search = trim((string)($_GET['search'] ?? ''));

        $sql = 'SELECT * FROM orders WHERE 1 = 1';
        $params = [];

        if ($status !== '') {
            $sql .= ' AND status = :status';
            $params['status'] = $status;
        }

        if ($search !== '') {
            $sql .= ' AND (order_code LIKE :search OR occasion_name LIKE :search OR model_name LIKE :search OR balloon_text LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY created_at DESC, id DESC';
        $statement = $pdo->prepare($sql);
        $statement->execute($params);

        jsonResponse(['success' => true, 'data' => $statement->fetchAll()]);
    }

    if ($method === 'POST') {
        $data = readJsonBody();

        $occasionId = textValue($data, 'occasionId', 60);
        $occasionName = textValue($data, 'occasionName', 120);
        $modelId = textValue($data, 'modelId', 60);
        $modelName = textValue($data, 'modelName', 120);
        $message = textValue($data, 'message', 45, false);
        $fontId = textValue($data, 'fontId', 60);
        $fontName = textValue($data, 'fontName', 120);
        $balloonColorId = textValue($data, 'balloonColorId', 60);
        $balloonColorName = textValue($data, 'balloonColorName', 120);
        $textColorId = textValue($data, 'textColorId', 60);
        $textColorName = textValue($data, 'textColorName', 120);
        $quantity = filter_var($data['quantity'] ?? null, FILTER_VALIDATE_INT);
        $unitPrice = filter_var($data['unitPrice'] ?? null, FILTER_VALIDATE_FLOAT);

        if (!$quantity || $quantity < 1 || $quantity > 20) {
            jsonResponse(['success' => false, 'message' => 'Quantidade inválida.'], 422);
        }

        if ($unitPrice === false || $unitPrice < 0) {
            jsonResponse(['success' => false, 'message' => 'Preço unitário inválido.'], 422);
        }

        $total = round($unitPrice * $quantity, 2);

        $pdo->beginTransaction();

        $statement = $pdo->prepare(
            'INSERT INTO orders (
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
            'occasion_id' => $occasionId,
            'occasion_name' => $occasionName,
            'model_id' => $modelId,
            'model_name' => $modelName,
            'balloon_text' => $message,
            'font_id' => $fontId,
            'font_name' => $fontName,
            'balloon_color_id' => $balloonColorId,
            'balloon_color_name' => $balloonColorName,
            'text_color_id' => $textColorId,
            'text_color_name' => $textColorName,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $total,
        ]);

        $newId = (int)$pdo->lastInsertId();
        $orderCode = 'A' . str_pad((string)$newId, 4, '0', STR_PAD_LEFT);

        $updateCode = $pdo->prepare('UPDATE orders SET order_code = :order_code WHERE id = :id');
        $updateCode->execute(['order_code' => $orderCode, 'id' => $newId]);

        $pdo->commit();

        $getOrder = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $getOrder->execute(['id' => $newId]);

        jsonResponse([
            'success' => true,
            'message' => 'Pedido cadastrado com sucesso.',
            'data' => $getOrder->fetch(),
        ], 201);
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        if (!$id) {
            jsonResponse(['success' => false, 'message' => 'Informe o ID do pedido.'], 400);
        }

        $data = readJsonBody();
        $existingStatement = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $existingStatement->execute(['id' => $id]);
        $existing = $existingStatement->fetch();

        if (!$existing) {
            jsonResponse(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
        }

        $status = trim((string)($data['status'] ?? $existing['status']));

        if (!allowedStatus($status)) {
            jsonResponse(['success' => false, 'message' => 'Status inválido.'], 422);
        }

        $quantity = filter_var($data['quantity'] ?? $existing['quantity'], FILTER_VALIDATE_INT);
        $unitPrice = filter_var($data['unitPrice'] ?? $existing['unit_price'], FILTER_VALIDATE_FLOAT);

        if (!$quantity || $quantity < 1 || $quantity > 20 || $unitPrice === false || $unitPrice < 0) {
            jsonResponse(['success' => false, 'message' => 'Quantidade ou preço inválido.'], 422);
        }

        $message = isset($data['message']) ? textValue($data, 'message', 45, false) : $existing['balloon_text'];
        $total = round($unitPrice * $quantity, 2);

        $statement = $pdo->prepare(
            'UPDATE orders SET
                balloon_text = :balloon_text,
                quantity = :quantity,
                unit_price = :unit_price,
                total_price = :total_price,
                status = :status
             WHERE id = :id'
        );

        $statement->execute([
            'balloon_text' => $message,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $total,
            'status' => $status,
            'id' => $id,
        ]);

        $getOrder = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $getOrder->execute(['id' => $id]);

        jsonResponse(['success' => true, 'message' => 'Pedido atualizado.', 'data' => $getOrder->fetch()]);
    }

    if ($method === 'DELETE') {
        if (!$id) {
            jsonResponse(['success' => false, 'message' => 'Informe o ID do pedido.'], 400);
        }

        $statement = $pdo->prepare('DELETE FROM orders WHERE id = :id');
        $statement->execute(['id' => $id]);

        if ($statement->rowCount() === 0) {
            jsonResponse(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
        }

        jsonResponse(['success' => true, 'message' => 'Pedido excluído.']);
    }

    jsonResponse(['success' => false, 'message' => 'Método não permitido.'], 405);
} catch (PDOException $exception) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    jsonResponse([
        'success' => false,
        'message' => 'Erro ao acessar o banco de dados.',
        'details' => $exception->getMessage(),
    ], 500);
}
