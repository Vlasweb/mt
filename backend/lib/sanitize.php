<?php

function sanitizeText(string $value): string
{
    $value = trim($value);
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
    return $value;
}

function sanitizeEmail(string $value): string
{
    return strtolower(sanitizeText($value));
}

function sanitizeCurrency(string $value): string
{
    return strtoupper(sanitizeText($value));
}

function sanitizePassword(string $value): string
{
    return trim($value);
}
