<?php require_once __DIR__ . '/baseUrl.php'; ?>
<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT - Finanzas</title>
    <script>
        (function() {
            const saved = localStorage.getItem('mt_theme');
            const theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link rel="stylesheet" href="<?= baseUrl() ?>/frontend/css/main.css">
</head>

<body>
