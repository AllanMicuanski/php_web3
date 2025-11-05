<?php
session_start();

// Se já estiver logado, redireciona para home
if (isset($_SESSION['usuario_id'])) {
    header("Location: home.php");
    exit();
}

include 'conexao.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST["login"]);
    $senha = $_POST["senha"];
    
    if (!empty($login) && !empty($senha)) {
        // Busca o usuário no banco
        $stmt = $con->prepare("SELECT id, nome, login, senha FROM usuarios WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verifica a senha
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_login'] = $usuario['login'];
                
                header("Location: home.php");
                exit();
            } else {
                $msg = "<div class='alert alert-danger'>Senha incorreta!</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
        }
        
        $stmt->close();
    } else {
        $msg = "<div class='alert alert-warning'>Por favor, preencha todos os campos!</div>";
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-person-circle"></i> Login
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($msg) echo $msg; ?>
                        
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="login" class="form-label">
                                    <i class="bi bi-person"></i> Usuário
                                </label>
                                <input type="text" id="login" name="login" 
                                       class="form-control" placeholder="Digite seu login" 
                                       value="<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>" 
                                       required autofocus>
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">
                                    <i class="bi bi-lock"></i> Senha
                                </label>
                                <input type="password" id="senha" name="senha" 
                                       class="form-control" placeholder="Digite sua senha" 
                                       required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Use seu login e senha cadastrados no sistema
                            </small>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-light">
                        <small class="text-muted">Sistema CRUD - Programação Web 3</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>