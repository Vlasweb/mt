<?php

require_once __DIR__ . '/../../lib/app.php';

// Solo POST
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    jsonError('method_not_allowed', 'Método no permitido', 405);
}

// Leer body JSON
$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    jsonError('invalid_body', 'Datos inválidos en la petición', 400);
}

// Sanitizar
$username     = sanitizeText($body['username'] ?? '');
$email        = sanitizeEmail($body['email'] ?? '');
$password     = sanitizePassword($body['password'] ?? '');
$baseCurrency = sanitizeCurrency($body['baseCurrency'] ?? '');

// Validar (alcabala: si falla, responde y termina)
check(validateUsername($username), 'username_invalid');
check(validateEmail($email), 'email_invalid');
check(validatePassword($password), 'password_invalid');
check(validateCurrency($baseCurrency), 'currency_invalid');

// Verificar unicidad
$stmt = db()->prepare('SELECT id FROM users WHERE username = :username');
$stmt->execute(['username' => $username]);
if ($stmt->fetch()) {
    jsonError('username_taken', 'El nombre de usuario ya está en uso', 409);
}

$stmt = db()->prepare('SELECT id FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
if ($stmt->fetch()) {
    jsonError('email_taken', 'El correo electrónico ya está registrado', 409);
}

// Crear usuario
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = db()->prepare(
    'INSERT INTO users (username, email, passwordHash, baseCurrency)
     VALUES (:username, :email, :passwordHash, :baseCurrency)'
);
$stmt->execute([
    'username'     => $username,
    'email'        => $email,
    'passwordHash' => $passwordHash,
    'baseCurrency' => $baseCurrency,
]);

$userId = (int) db()->lastInsertId();

// Auditoría
logActivity($userId, 'register');

// Crear token (auto-login)
$ttl = (int) $_ENV['JWT_EXPIRACION_CORTA'];
$token = jwtCreate([
    'userId'   => $userId,
    'username' => $username,
    'role'     => 'client',
], $ttl);

// Respuesta
jsonSuccess([
    'token' => $token,
    'user'  => [
        'id'           => $userId,
        'username'     => $username,
        'email'        => $email,
        'baseCurrency' => $baseCurrency,
        'role'         => 'client',
    ],
], 201);
