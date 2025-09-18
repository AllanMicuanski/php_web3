<?php
include 'conexao.php'; // conecta ao banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebe os dados
    $nome     = trim($_POST["name"]);
    $email    = $_POST["email"];
    $dataNasc = $_POST["date"];
    $estado   = $_POST["estado"];
    $endereco = $_POST["endereco"];
    $sexo     = $_POST["sexo"] ?? "";
    $login    = $_POST["login"];
    $senha    = password_hash($_POST["senha"], PASSWORD_DEFAULT); // hash da senha

    // Validação: nome completo
    if (str_word_count($nome) < 2) {
        echo "<p style='color:red;'>⚠️ Por favor, preencha o nome completo.</p>";
        echo "<a href='index.php'>Voltar ao formulário</a>";
        exit;
    }

    // Calcular idade
    $hoje = new DateTime();
    $dataNascimento = new DateTime($dataNasc);
    $idade = $hoje->diff($dataNascimento)->y;

    // Saudação
    $saudacao = ($sexo === "masculino") ? "Olá Sr. $nome" :
                (($sexo === "feminino") ? "Olá Sra. $nome" : "Olá $nome");

    // Inserir no banco usando prepared statement
    $stmt = $con->prepare("INSERT INTO usuarios (nome, email, data_nasc, estado, endereco, sexo, login, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nome, $email, $dataNasc, $estado, $endereco, $sexo, $login, $senha);

    if ($stmt->execute()) {
        echo "<h2>Cadastro realizado com sucesso!</h2>";
        echo "<p>$saudacao</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Data de nascimento:</strong> $dataNasc</p>";
        echo "<p><strong>Idade:</strong> $idade anos</p>";
        echo "<p><strong>Estado:</strong> $estado</p>";
        echo "<p><strong>Endereço:</strong> $endereco</p>";
        echo ($idade < 18)
            ? "<p style='color:red;'>⚠️ Atenção: Usuário menor de idade!</p>"
            : "<p style='color:green;'>✅ Usuário maior de idade.</p>";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Método de envio inválido.";
}
?>
