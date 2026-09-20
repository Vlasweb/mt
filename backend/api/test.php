<?php
require_once __DIR__ . '/../lib/app.php';

jsonSuccess(['mensaje' => 'app.php funciona', 'env' => $_ENV['DB_NAME'] ?? 'no cargado']);
