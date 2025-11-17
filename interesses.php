<?php
include 'verificar_sessao.php';
include 'conexao.php';

$msg = "";

// ADICIONAR novo interesse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nome = trim($_POST['nome']);
    
    if (!empty($nome)) {
        $stmt = $con->prepare("INSERT INTO interesse (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Interesse '$nome' adicionado com sucesso!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Erro ao adicionar interesse: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ Por favor, informe o nome do interesse!</div>";
    }
}

// EDITAR interesse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $cod = intval($_POST['cod']);
    $nome = trim($_POST['nome']);
    
    if (!empty($nome)) {
        $stmt = $con->prepare("UPDATE interesse SET nome = ? WHERE cod = ?");
        $stmt->bind_param("si", $nome, $cod);
        
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Interesse atualizado com sucesso!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Erro ao atualizar interesse: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

// EXCLUIR interesse
if (isset($_GET['delete'])) {
    $cod = intval($_GET['delete']);
    
    // Verifica se há pessoas associadas
    $stmt_check = $con->prepare("SELECT COUNT(*) as total FROM pessoa_interesse WHERE fk_interesse_cod = ?");
    $stmt_check->bind_param("i", $cod);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $total = $result_check->fetch_assoc()['total'];
    $stmt_check->close();
    
    if ($total > 0) {
        $msg = "<div class='alert alert-warning'>⚠️ Este interesse possui $total pessoa(s) associada(s). Ele será desvinculado ao excluir.</div>";
    }
    
    $stmt = $con->prepare("DELETE FROM interesse WHERE cod = ?");
    $stmt->bind_param("i", $cod);
    
    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Interesse excluído com sucesso!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Erro ao excluir interesse: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Buscar interesse para edição
$interesse_edit = null;
if (isset($_GET['edit'])) {
    $cod_edit = intval($_GET['edit']);
    $stmt = $con->prepare("SELECT * FROM interesse WHERE cod = ?");
    $stmt->bind_param("i", $cod_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $interesse_edit = $result->fetch_assoc();
    $stmt->close();
}

// Listar todos os interesses
$sql = "SELECT i.cod, i.nome, COUNT(pi.fk_pessoa_cod) as total_pessoas 
        FROM interesse i
        LEFT JOIN pessoa_interesse pi ON i.cod = pi.fk_interesse_cod
        GROUP BY i.cod
        ORDER BY i.nome";
$resultado = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Interesses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-heart"></i> Gerenciamento de Interesses
            </span>
            <div>
                <span class="text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                </span>
                <a href="home.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-house"></i> Home
                </a>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if($msg) echo $msg; ?>

        <!-- Formulário para adicionar/editar interesse -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> 
                    <?= $interesse_edit ? 'Editar Interesse' : 'Adicionar Novo Interesse' ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?= $interesse_edit ? 'edit' : 'add' ?>">
                    <?php if ($interesse_edit): ?>
                        <input type="hidden" name="cod" value="<?= $interesse_edit['cod'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="nome" class="form-control" 
                                   placeholder="Nome do interesse (ex: Esportes, Música, etc.)" 
                                   value="<?= $interesse_edit ? htmlspecialchars($interesse_edit['nome']) : '' ?>"
                                   required autofocus>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> 
                                <?= $interesse_edit ? 'Atualizar' : 'Adicionar' ?>
                            </button>
                            <?php if ($interesse_edit): ?>
                                <a href="interesses.php" class="btn btn-secondary w-100 mt-2">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de interesses -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul"></i> Interesses Cadastrados
                </h5>
            </div>
            <div class="card-body">
                <?php if ($resultado->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">Código</th>
                                    <th width="50%">Nome</th>
                                    <th width="20%" class="text-center">Pessoas</th>
                                    <th width="20%" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($interesse = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $interesse['cod'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($interesse['nome']) ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                <?= $interesse['total_pessoas'] ?> pessoa(s)
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="?edit=<?= $interesse['cod'] ?>" 
                                               class="btn btn-warning btn-sm" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="?delete=<?= $interesse['cod'] ?>" 
                                               class="btn btn-danger btn-sm" 
                                               title="Excluir"
                                               onclick="return confirm('Tem certeza que deseja excluir o interesse \'<?= htmlspecialchars($interesse['nome']) ?>\'?<?= $interesse['total_pessoas'] > 0 ? ' Ele está associado a ' . $interesse['total_pessoas'] . ' pessoa(s).' : '' ?>')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center p-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="text-muted mt-3">Nenhum interesse cadastrado</h5>
                        <p class="text-muted">Use o formulário acima para adicionar o primeiro interesse.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $con->close(); ?>
