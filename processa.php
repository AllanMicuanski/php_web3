<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome     = trim($_POST["name"]);
    $email    = $_POST["email"];
    $dataNasc = $_POST["date"];
    $estado   = $_POST["estado"];
    $endereco = $_POST["endereco"];
    $sexo     = $_POST["sexo"] ?? "";
    $login    = $_POST["login"];
    $senha    = $_POST["senha"];

    // Validação: nome completo (precisa ter pelo menos 2 palavras)
    if (str_word_count($nome) < 2) {
        echo "<p style='color:red;'>⚠️ Por favor, preencha o nome completo.</p>";
        echo "<a href='index.html'>Voltar ao formulário</a>";
        exit;
    }

    // Calcular idade
    $hoje = new DateTime();
    $dataNascimento = new DateTime($dataNasc);
    $idade = $hoje->diff($dataNascimento)->y;

    // Saudação de acordo com sexo
    $saudacao = "";
    if ($sexo === "masculino") {
        $saudacao = "Olá Sr. $nome";
    } elseif ($sexo === "feminino") {
        $saudacao = "Olá Sra. $nome";
    } else {
        $saudacao = "Olá $nome";
    }

    // Exibe os dados
    echo "<h2>Dados recebidos:</h2>";
    echo "<p>$saudacao</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Data de nascimento:</strong> $dataNasc</p>";
    echo "<p><strong>Idade:</strong> $idade anos</p>";
    echo "<p><strong>Estado:</strong> $estado</p>";
    echo "<p><strong>Endereço:</strong> $endereco</p>";
  

    // Verifica maioridade
    if ($idade < 18) {
        echo "<p style='color:red;'>⚠️ Atenção: Usuário menor de idade!</p>";
    } else {
        echo "<p style='color:green;'>✅ Usuário maior de idade.</p>";
    }
} else {
    echo "Método de envio inválido.";
}
?>
