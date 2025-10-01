<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Busca o usuário para confirmar que existe
    $stmt = $con->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Exclui o usuário
        $stmt2 = $con->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt2->bind_param("i", $id);
        
        if ($stmt2->execute()) {
            $mensagem = "Usuário '{$usuario['nome']}' foi excluído com sucesso!";
            $tipo = "success";
        } else {
            $mensagem = "Erro ao excluir usuário: " . $stmt2->error;
            $tipo = "danger";
        }
        $stmt2->close();
    } else {
        $mensagem = "Usuário não encontrado!";
        $tipo = "warning";
    }
    $stmt->close();
} else {
    $mensagem = "ID do usuário não informado!";
    $tipo = "danger";
}

$con->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Excluir Usuário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow mx-auto" style="max-width: 500px;">
    <div class="card-header bg-<?= $tipo ?> text-white text-center">
      <h4 class="mb-0">
        <?php if($tipo == 'success'): ?>
          ✅ Exclusão Realizada
        <?php else: ?>
          ❌ Erro na Exclusão
        <?php endif; ?>
      </h4>
    </div>
    <div class="card-body text-center">
      <div class="alert alert-<?= $tipo ?> mb-4">
        <?= htmlspecialchars($mensagem) ?>
      </div>
      
      <a href="index.php" class="btn btn-primary btn-lg">
        🏠 Voltar para Lista de Usuários
      </a>
    </div>
  </div>
</div>

<!-- Redirecionamento automático -->
<script>
  setTimeout(function() {
    window.location.href = 'index.php';
  }, 3000); // 3 segundos
</script>
</body>
</html>