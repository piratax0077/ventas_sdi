<?php
require dirname(__DIR__).'/vendor/autoload.php';
$env = Dotenv\Dotenv::parse(file_get_contents(dirname(__DIR__).'/.env'));
$pdo = new PDO('mysql:host='.$env['DB_HOST'].';port='.$env['DB_PORT'], $env['DB_USERNAME'], $env['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('CREATE DATABASE IF NOT EXISTS ventas_sdi_v13_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
echo "Base aislada de pruebas preparada.\n";
