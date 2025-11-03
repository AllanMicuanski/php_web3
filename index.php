<?php
include 'conexao.php'; // conecta ao banco

$msg = ""; // mensagem de feedback

// Variáveis para manter valores do formulário
$form_nome = "";
$form_email = "";
$form_data = "";
$form_estado = "";
$form_endereco = "";
$form_sexo = "";
$form_login = "";

// Verifica se há mensagem de sucesso via GET
if (isset($_GET['msg']) && $_GET['msg'] == 'success') {
    $nome = $_GET['nome'] ?? '';
    $prefixo = $_GET['prefixo'] ?? '';
    $msg = "<div class='alert alert-success alert-dismissible fade show'>
        <strong>Sucesso!</strong> Olá $prefixo $nome! Cadastro realizado com sucesso.
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome     = trim($_POST["name"]);
    $email    = $_POST["email"];
    $dataNasc = $_POST["date"];
    $estado   = $_POST["estado"];
    $endereco = $_POST["endereco"];
    $sexo     = $_POST["sexo"] ?? "";
    $login    = $_POST["login"];
    $senha    = password_hash($_POST["senha"], PASSWORD_DEFAULT); // hash da senha
    
    // Processa upload da foto
    $nomeArquivo = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['foto'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($extensao, $extensoesPermitidas) && $arquivo['size'] <= 2000000) { // 2MB
            $nomeArquivo = uniqid() . '.' . $extensao;
            $caminhoDestino = 'uploads/' . $nomeArquivo;
            
            if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
                $nomeArquivo = null;
            }
        }
    }

    // Salva os valores para reexibir no formulário em caso de erro
    $form_nome = $nome;
    $form_email = $email;
    $form_data = $dataNasc;
    $form_estado = $estado;
    $form_endereco = $endereco;
    $form_sexo = $sexo;
    $form_login = $login;

    if (str_word_count($nome) < 2) {
        $msg = "<div class='alert alert-warning'><strong>Atenção!</strong> Por favor, informe o nome completo (nome e sobrenome).</div>";
    } else {
        // Prepared statement seguro
        $stmt = $con->prepare("INSERT INTO usuarios (nome, email, data_nasc, estado, endereco, sexo, login, senha, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nome, $email, $dataNasc, $estado, $endereco, $sexo, $login, $senha, $nomeArquivo);

        if ($stmt->execute()) {
            $prefixo = ($sexo == "masculino") ? "Sr." : "Sra.";
            // Redireciona para a página principal após sucesso
            header("Location: index.php?msg=success&nome=" . urlencode($nome) . "&prefixo=" . urlencode($prefixo));
            exit();
        } else {
            $msg = "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    $con->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Cadastro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 border p-4 bg-white rounded shadow">
  <h2 class="mb-4">Formulário de Cadastro</h2>

  <?php if($msg) echo $msg; ?>

  <form action="" method="post" enctype="multipart/form-data">
    <!-- Aqui vão os campos do formulário -->
    <div class="mb-3">
      <label for="name" class="form-label">Nome completo</label>
      <input type="text" id="name" name="name" class="form-control" 
             value="<?= htmlspecialchars($form_nome) ?>" required />
      <div class="form-text">Digite nome e sobrenome (ex: João Silva)</div>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" id="email" name="email" class="form-control" 
             value="<?= htmlspecialchars($form_email) ?>" required />
    </div>
    <div class="mb-3">
      <label for="date" class="form-label">Data de nascimento</label>
      <input type="date" id="date" name="date" class="form-control" 
             value="<?= htmlspecialchars($form_data) ?>" required />
    </div>
    <div class="mb-3">
      <label for="estado" class="form-label">Estado</label>
      <select id="estado" name="estado" class="form-select" required>
        <option value="">Escolha...</option>
        <option value="pr" <?= $form_estado == 'pr' ? 'selected' : '' ?>>Paraná</option>
        <option value="sc" <?= $form_estado == 'sc' ? 'selected' : '' ?>>Santa Catarina</option>
        <option value="rs" <?= $form_estado == 'rs' ? 'selected' : '' ?>>Rio Grande do Sul</option>
        <option value="sp" <?= $form_estado == 'sp' ? 'selected' : '' ?>>São Paulo</option>
        <option value="rj" <?= $form_estado == 'rj' ? 'selected' : '' ?>>Rio de Janeiro</option>
        <option value="mg" <?= $form_estado == 'mg' ? 'selected' : '' ?>>Minas Gerais</option>
        <option value="es" <?= $form_estado == 'es' ? 'selected' : '' ?>>Espírito Santo</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="endereco" class="form-label">Endereço</label>
      <input type="text" id="endereco" name="endereco" class="form-control" 
             value="<?= htmlspecialchars($form_endereco) ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label d-block">Sexo</label>
      <div class="form-check form-check-inline">
        <input type="radio" id="masculino" name="sexo" value="masculino" 
               class="form-check-input" <?= $form_sexo == 'masculino' ? 'checked' : '' ?> required />
        <label for="masculino" class="form-check-label">Masculino</label>
      </div>
      <div class="form-check form-check-inline">
        <input type="radio" id="feminino" name="sexo" value="feminino" 
               class="form-check-input" <?= $form_sexo == 'feminino' ? 'checked' : '' ?> />
        <label for="feminino" class="form-check-label">Feminino</label>
      </div>
    </div>
    <div class="mb-3">
      <label for="login" class="form-label">Login</label>
      <input type="text" id="login" name="login" class="form-control" 
             value="<?= htmlspecialchars($form_login) ?>" required />
    </div>
    <div class="mb-3">
      <label for="senha" class="form-label">Senha</label>
      <input type="password" id="senha" name="senha" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="foto" class="form-label">Foto de Perfil</label>
      <input type="file" id="foto" name="foto" class="form-control" accept="image/*" />
      <div class="form-text">Formatos aceitos: JPG, PNG, GIF (máx. 2MB)</div>
    </div>
    <div class="d-flex gap-2">
      <button type="reset" class="btn btn-secondary">Limpar</button>
      <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
  </form>
</div>

<!-- Lista de Usuários Cadastrados -->
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">👥 Usuários Cadastrados</h4>
    </div>
    <div class="card-body">
      <?php
      // Busca todos os usuários
      $sql = "SELECT * FROM usuarios ORDER BY nome";
      $resultado = $con->query($sql);
      
      if ($resultado->num_rows > 0): ?>
        <div class="row">
          <?php while($usuario = $resultado->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="card h-100">
                <?php if ($usuario['foto']): ?>
                  <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" 
                       class="card-img-top" alt="Foto de <?= htmlspecialchars($usuario['nome']) ?>"
                       style="height: 200px; object-fit: cover;">
                <?php else: ?>
                  <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                       style="height: 200px;">
                    <i class="bi bi-person-circle text-muted" style="font-size: 4rem;"></i>
                  </div>
                <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title text-primary">
                    <?= htmlspecialchars($usuario['nome']) ?>
                  </h5>
                  <p class="card-text">
                    <strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?><br>
                    <strong>Login:</strong> <?= htmlspecialchars($usuario['login']) ?><br>
                    <strong>Estado:</strong> <?= strtoupper($usuario['estado']) ?><br>
                    <strong>Nascimento:</strong> <?= date('d/m/Y', strtotime($usuario['data_nasc'])) ?>
                  </p>
                </div>
                <div class="card-footer d-flex gap-2">
                  <a href="editar.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm flex-fill">
                    ✏️ Editar
                  </a>
                  <a href="excluir.php?id=<?= $usuario['id'] ?>" 
                     class="btn btn-danger btn-sm flex-fill"
                     onclick="return confirm('Tem certeza que deseja excluir <?= htmlspecialchars($usuario['nome']) ?>?')">
                    🗑️ Excluir
                  </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="text-center p-4">
          <h5 class="text-muted">📭 Nenhum usuário cadastrado ainda</h5>
          <p class="text-muted">Use o formulário acima para cadastrar o primeiro usuário.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Bootstrap JS para funcionalidade do alert -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
