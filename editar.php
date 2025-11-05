<?php
include 'verificar_sessao.php'; // Verifica se está logado
include 'conexao.php';

$msg = "";
$usuario = null;

// Verifica se foi passado um ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Busca o usuário
    $stmt = $con->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
    } else {
        $msg = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
    }
    $stmt->close();
}

// Processa o formulário de edição
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST["name"]);
    $email = $_POST["email"];
    $dataNasc = $_POST["date"];
    $estado = $_POST["estado"];
    $endereco = $_POST["endereco"];
    $sexo = $_POST["sexo"] ?? "";
    $login = $_POST["login"];
    
    // Processa upload da nova foto (se enviada)
    $novaFoto = null;
    $fotoAtual = null;
    
    // Busca a foto atual do usuário
    $stmt_foto = $con->prepare("SELECT foto FROM usuarios WHERE id = ?");
    $stmt_foto->bind_param("i", $id);
    $stmt_foto->execute();
    $resultado_foto = $stmt_foto->get_result();
    if ($resultado_foto->num_rows > 0) {
        $fotoAtual = $resultado_foto->fetch_assoc()['foto'];
    }
    $stmt_foto->close();
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['foto'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($extensao, $extensoesPermitidas) && $arquivo['size'] <= 2000000) { // 2MB
            $novaFoto = uniqid() . '.' . $extensao;
            $caminhoDestino = 'uploads/' . $novaFoto;
            
            if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
                // Remove a foto antiga se existir
                if ($fotoAtual && file_exists('uploads/' . $fotoAtual)) {
                    unlink('uploads/' . $fotoAtual);
                }
            } else {
                $novaFoto = null;
            }
        }
    }
    
    // Se não há nova foto, mantém a atual
    $fotoParaSalvar = $novaFoto ?? $fotoAtual;
    
    if (str_word_count($nome) < 2) {
        $msg = "<div class='alert alert-warning'>Por favor, informe o nome completo.</div>";
    } else {
        // Atualiza os dados incluindo a foto
        $stmt = $con->prepare("UPDATE usuarios SET nome=?, email=?, data_nasc=?, estado=?, endereco=?, sexo=?, login=?, foto=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $nome, $email, $dataNasc, $estado, $endereco, $sexo, $login, $fotoParaSalvar, $id);
        
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Usuário atualizado com sucesso! <a href='index.php' class='alert-link'>Ver lista</a></div>";
            
            // Recarrega os dados atualizados
            $stmt2 = $con->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $resultado2 = $stmt2->get_result();
            $usuario = $resultado2->fetch_assoc();
            $stmt2->close();
        } else {
            $msg = "<div class='alert alert-danger'>Erro ao atualizar: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

if (!$usuario && empty($msg)) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Editar Usuário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<!-- Header com informações do usuário -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="home.php">
      <i class="bi bi-house-door"></i> Sistema CRUD
    </a>
    <div class="navbar-nav ms-auto">
      <span class="navbar-text me-3">
        <i class="bi bi-person-circle"></i> 
        Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <!-- Navegação -->
  <div class="mb-3">
    <a href="home.php" class="btn btn-outline-secondary">← Voltar para Lista</a>
  </div>

  <div class="card shadow">
    <div class="card-header bg-warning text-dark">
      <h4 class="mb-0">✏️ Editar Usuário</h4>
    </div>
    <div class="card-body">
      <?php if($msg) echo $msg; ?>
      
      <?php if($usuario): ?>
      <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Nome completo *</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?= htmlspecialchars($usuario['nome']) ?>" required />
          </div>
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">E-mail *</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($usuario['email']) ?>" required />
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="date" class="form-label">Data de nascimento *</label>
            <input type="date" id="date" name="date" class="form-control" 
                   value="<?= $usuario['data_nasc'] ?>" required />
          </div>
          <div class="col-md-4 mb-3">
            <label for="estado" class="form-label">Estado *</label>
            <select id="estado" name="estado" class="form-select" required>
              <option value="">Escolha...</option>
              <option value="pr" <?= $usuario['estado'] == 'pr' ? 'selected' : '' ?>>Paraná</option>
              <option value="sc" <?= $usuario['estado'] == 'sc' ? 'selected' : '' ?>>Santa Catarina</option>
              <option value="rs" <?= $usuario['estado'] == 'rs' ? 'selected' : '' ?>>Rio Grande do Sul</option>
              <option value="sp" <?= $usuario['estado'] == 'sp' ? 'selected' : '' ?>>São Paulo</option>
              <option value="rj" <?= $usuario['estado'] == 'rj' ? 'selected' : '' ?>>Rio de Janeiro</option>
              <option value="mg" <?= $usuario['estado'] == 'mg' ? 'selected' : '' ?>>Minas Gerais</option>
              <option value="es" <?= $usuario['estado'] == 'es' ? 'selected' : '' ?>>Espírito Santo</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label d-block">Sexo *</label>
            <div class="form-check form-check-inline">
              <input type="radio" id="masculino" name="sexo" value="masculino" 
                     class="form-check-input" <?= $usuario['sexo'] == 'masculino' ? 'checked' : '' ?> required />
              <label for="masculino" class="form-check-label">Masculino</label>
            </div>
            <div class="form-check form-check-inline">
              <input type="radio" id="feminino" name="sexo" value="feminino" 
                     class="form-check-input" <?= $usuario['sexo'] == 'feminino' ? 'checked' : '' ?> />
              <label for="feminino" class="form-check-label">Feminino</label>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="endereco" class="form-label">Endereço *</label>
          <input type="text" id="endereco" name="endereco" class="form-control" 
                 value="<?= htmlspecialchars($usuario['endereco']) ?>" required />
        </div>
        
        <div class="mb-3">
          <label for="login" class="form-label">Login *</label>
          <input type="text" id="login" name="login" class="form-control" 
                 value="<?= htmlspecialchars($usuario['login']) ?>" required />
        </div>

        <div class="mb-3">
          <label for="foto" class="form-label">Foto de Perfil</label>
          <?php if ($usuario['foto']): ?>
            <div class="mb-2">
              <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" 
                   alt="Foto atual" class="img-thumbnail" style="max-width: 150px;">
              <small class="text-muted d-block">Foto atual</small>
            </div>
          <?php endif; ?>
          <input type="file" id="foto" name="foto" class="form-control" accept="image/*" />
          <div class="form-text">Formatos aceitos: JPG, PNG, GIF (máx. 2MB). Deixe vazio para manter a foto atual.</div>
        </div>

        <div class="alert alert-info">
          <strong>ℹ️ Nota:</strong> A senha não pode ser alterada nesta versão por segurança.
        </div>

        <div class="d-flex gap-2">
          <a href="index.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-warning">💾 Salvar Alterações</button>
        </div>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
<?php $con->close(); ?>