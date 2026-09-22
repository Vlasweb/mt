<?php

function baseUrl(): string
{
    return rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
}
