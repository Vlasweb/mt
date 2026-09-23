<?php require_once __DIR__ . '/baseUrl.php'; ?>
<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT - Finanzas</title>
    <link rel="icon" type="image/svg+xml" href="<?= baseUrl() ?>/frontend/assets/icons/favicon.php?theme=light&accent=%236366F1" id="favicon">
    <script>
        (function() {
            const saved = localStorage.getItem('mt_theme');
            const theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);

            const favicon = document.getElementById('favicon');
            if (favicon) {
                const accent = localStorage.getItem('mt_accent') || '#6366F1';
                const base = favicon.href.split('?')[0];
                favicon.href = base + '?theme=' + theme + '&accent=' + encodeURIComponent(accent);
            }
        })();
    </script>
    <link rel="stylesheet" href="<?= baseUrl() ?>/frontend/css/main.css">
</head>

<body>
