<?php
// Carrega configuração do banco
$config = include 'config.php';

// Cria conexão com o banco
$con = new mysqli($config['host'], $config['usuario'], $config['senha'], $config['banco']);

// Checa conexão
if ($con->connect_error) {
    die("Falha na conexão: " . $con->connect_error);
}
