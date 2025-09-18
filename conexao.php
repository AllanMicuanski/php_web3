<?php
$host = "localhost";
$usuario = "meuusuario";  // seu usuário MariaDB
$senha = "minhasenha";    // sua senha MariaDB
$banco = "agenda03";       // nome do banco

$con = new mysqli($host, $usuario, $senha, $banco);

// Checa conexão
if ($con->connect_error) {
    die("Falha na conexão: " . $con->connect_error);
}
?>
