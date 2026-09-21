<?php

function jsonSuccess($data = [], int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['ok' => true], $data), JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError(string $error, string $message, int $status = 400): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'ok'      => false,
        'error'   => $error,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function check(?string $error, string $code, int $status = 400): void
{
    if ($error !== null) {
        jsonError($code, $error, $status);
    }
}
