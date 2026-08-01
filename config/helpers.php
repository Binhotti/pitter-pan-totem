<?php

declare(strict_types=1);

function jsonResponse(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function readJsonBody(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);

    if (!is_array($data)) {
        jsonResponse(['success' => false, 'message' => 'JSON inválido.'], 400);
    }

    return $data;
}

function textValue(array $data, string $key, int $maxLength = 120, bool $required = true): string
{
    $value = trim((string)($data[$key] ?? ''));

    if ($required && $value === '') {
        jsonResponse(['success' => false, 'message' => "O campo {$key} é obrigatório."], 422);
    }

    if (mb_strlen($value) > $maxLength) {
        jsonResponse(['success' => false, 'message' => "O campo {$key} excede {$maxLength} caracteres."], 422);
    }

    return $value;
}

function allowedStatus(string $status): bool
{
    return in_array($status, ['recebido', 'producao', 'pronto', 'finalizado', 'cancelado'], true);
}
