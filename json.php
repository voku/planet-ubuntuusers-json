<?php

declare(strict_types=1);

require_once __DIR__ . '/inc_global.php';

$callback = getJsonpCallback();
$payload = json_encode(
    getPlanetFeed(),
    JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);

if ($callback !== null) {
    header('Content-Type: text/javascript; charset=UTF-8');
    echo $callback . '(' . $payload . ');';

    return;
}

header('Content-Type: application/json; charset=UTF-8');
echo $payload;

function getJsonpCallback(): ?string
{
    $callback = $_GET['callback'] ?? null;

    if (!is_string($callback) || $callback === '') {
        return null;
    }

    if (preg_match('/^(?:[$_\p{L}][$_\p{L}\p{N}]*)(?:\.(?:[$_\p{L}][$_\p{L}\p{N}]*))*$/u', $callback) === 1) {
        return $callback;
    }

    http_response_code(400);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(
        ['error' => 'Invalid callback parameter.'],
        JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    exit;
}