<?php
// Arquivo de verificação de sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Função para fazer logout
function logout() {
    session_start();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>