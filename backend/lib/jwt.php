<?php

function base64UrlEncode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode(string $data): string
{
    $pad = strlen($data) % 4;
    if ($pad > 0) {
        $data .= str_repeat('=', 4 - $pad);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwtCreate(array $payload, int $ttl): string
{
    $secret = $_ENV['JWT_SECRET'] ?? '';

    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload['iat'] = time();
    $payload['exp'] = time() + $ttl;

    $headerB64  = base64UrlEncode(json_encode($header, JSON_UNESCAPED_UNICODE));
    $payloadB64 = base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));

    $signature = hash_hmac('sha256', "$headerB64.$payloadB64", $secret, true);
    $signatureB64 = base64UrlEncode($signature);

    return "$headerB64.$payloadB64.$signatureB64";
}

function jwtVerify(string $token): ?array
{
    $secret = $_ENV['JWT_SECRET'] ?? '';

    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }

    [$headerB64, $payloadB64, $signatureB64] = $parts;

    $header = json_decode(base64UrlDecode($headerB64), true);
    if (!is_array($header) || ($header['alg'] ?? '') !== 'HS256') {
        return null;
    }

    $expectedSignature = hash_hmac('sha256', "$headerB64.$payloadB64", $secret, true);
    $expectedSignatureB64 = base64UrlEncode($expectedSignature);

    if (!hash_equals($expectedSignatureB64, $signatureB64)) {
        return null;
    }

    $payload = json_decode(base64UrlDecode($payloadB64), true);
    if (!is_array($payload)) {
        return null;
    }

    if (!isset($payload['exp']) || $payload['exp'] < time()) {
        return null;
    }

    return $payload;
}

function jwtFromHeader(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
        return null;
    }

    return trim($matches[1]);
}
