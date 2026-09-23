<?php

$theme = $_GET['theme'] ?? 'light';
$accent = $_GET['accent'] ?? '#6366F1';

$symbolColor = '#FFFFFF';

header('Content-Type: image/svg+xml');
header('Cache-Control: public, max-age=3600');

echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32">
  <circle cx="16" cy="16" r="16" fill="{$accent}"/>
  <text x="16" y="16" font-family="Arial, Helvetica, sans-serif" font-size="20" font-weight="700" fill="{$symbolColor}" text-anchor="middle" dominant-baseline="central">$</text>
</svg>
SVG;
