<?php
include 'conexao.php'; // conecta ao banco

$msg = ""; // mensagem de feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome     = trim($_POST["name"]);
    $email    = $_POST["email"];
    $dataNasc = $_POST["date"];
    $estado   = $_POST["estado"];
    $endereco = $_POST["endereco"];
    $sexo     = $_POST["sexo"] ?? "";
    $login    = $_POST["login"];
    $senha    = password_hash($_POST["senha"], PASSWORD_DEFAULT); // hash da senha

    if (str_word_count($nome) < 2) {
        $msg = "<div class='alert alert-warning'>Por favor, informe o nome completo.</div>";
    } else {
        // Prepared statement seguro
        $stmt = $con->prepare("INSERT INTO usuarios (nome, email, data_nasc, estado, endereco, sexo, login, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nome, $email, $dataNasc, $estado, $endereco, $sexo, $login, $senha);

        if ($stmt->execute()) {
            $prefixo = ($sexo == "masculino") ? "Sr." : "Sra.";
            $msg = "<div class='alert alert-success'>Olá $prefixo $nome! Cadastro realizado com sucesso.</div>";
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
</head>
<body class="bg-light">
<div class="container mt-5 border p-4 bg-white rounded shadow">
  <h2 class="mb-4">Formulário de Cadastro</h2>

  <?php if($msg) echo $msg; ?>

  <form action="" method="post">
    <!-- Aqui vão os campos do formulário -->
    <div class="mb-3">
      <label for="name" class="form-label">Nome completo</label>
      <input type="text" id="name" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" id="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="date" class="form-label">Data de nascimento</label>
      <input type="date" id="date" name="date" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="estado" class="form-label">Estado</label>
      <select id="estado" name="estado" class="form-select" required>
        <option selected disabled value="">Escolha...</option>
        <option value="pr">Paraná</option>
        <option value="sc">Santa Catarina</option>
        <option value="rs">Rio Grande do Sul</option>
        <option value="sp">São Paulo</option>
        <option value="rj">Rio de Janeiro</option>
        <option value="mg">Minas Gerais</option>
        <option value="es">Espírito Santo</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="endereco" class="form-label">Endereço</label>
      <input type="text" id="endereco" name="endereco" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label d-block">Sexo</label>
      <div class="form-check form-check-inline">
        <input type="radio" id="masculino" name="sexo" value="masculino" class="form-check-input" required />
        <label for="masculino" class="form-check-label">Masculino</label>
      </div>
      <div class="form-check form-check-inline">
        <input type="radio" id="feminino" name="sexo" value="feminino" class="form-check-input" />
        <label for="feminino" class="form-check-label">Feminino</label>
      </div>
    </div>
    <div class="mb-3">
      <label for="login" class="form-label">Login</label>
      <input type="text" id="login" name="login" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="senha" class="form-label">Senha</label>
      <input type="password" id="senha" name="senha" class="form-control" required />
    </div>
    <div class="d-flex gap-2">
      <button type="reset" class="btn btn-secondary">Limpar</button>
      <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
  </form>
</div>
</body>
</html>
