<?php

// ------------------------------------------------------------
// Primitivas genéricas
// ------------------------------------------------------------

function validateRequired(string $value, string $label): ?string
{
    return $value === '' ? "$label es obligatorio" : null;
}

function validateLength(string $value, int $min, int $max, string $label): ?string
{
    $len = strlen($value);
    if ($len < $min || $len > $max) {
        return "$label debe tener entre $min y $max caracteres";
    }
    return null;
}

function validateRegex(string $value, string $pattern, string $label, string $rule): ?string
{
    return preg_match($pattern, $value) ? null : "$label $rule";
}

function validateEmailFormat(string $value, string $label): ?string
{
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? null : "$label no es válido";
}

// ------------------------------------------------------------
// Validaciones compuestas por campo
// ------------------------------------------------------------

function validateUsername(string $value): ?string
{
    $label = 'El nombre de usuario';
    return validateRequired($value, $label)
        ?? validateLength($value, 3, 50, $label)
        ?? validateRegex($value, '/^[a-zA-Z0-9_]+$/', $label, 'solo puede contener letras, números y guion bajo');
}

function validateEmail(string $value): ?string
{
    $label = 'El correo electrónico';
    return validateRequired($value, $label)
        ?? validateLength($value, 1, 120, $label)
        ?? validateEmailFormat($value, $label);
}

function validatePassword(string $value): ?string
{
    $label = 'La contraseña';
    return validateRequired($value, $label)
        ?? validateLength($value, 8, 64, $label)
        ?? validateRegex($value, '/[a-z]/', $label, 'debe incluir al menos una letra minúscula')
        ?? validateRegex($value, '/[A-Z]/', $label, 'debe incluir al menos una letra mayúscula')
        ?? validateRegex($value, '/[0-9]/', $label, 'debe incluir al menos un número');
}

function validateCurrency(string $value): ?string
{
    $label = 'La moneda base';
    $error = validateRequired($value, $label);
    if ($error) return $error;

    $stmt = db()->prepare('SELECT code FROM currencies WHERE code = :code');
    $stmt->execute(['code' => $value]);
    if (!$stmt->fetch()) {
        return "$label no es válida";
    }
    return null;
}
