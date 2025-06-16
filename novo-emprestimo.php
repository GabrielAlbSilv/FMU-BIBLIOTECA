<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

include("conexao.php");

$msg = "";
$tipoMsg = ""; // "erro" ou "sucesso"

// Processa envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? null;
    $livro_id = $_POST['livro_id'] ?? null;

    if (!$usuario_id || !$livro_id) {
        $msg = "Por favor, selecione usuário e livro.";
        $tipoMsg = "erro";
    } else {
        // Verifica quantos empréstimos ativos esse usuário já tem
        $sqlCount = "SELECT COUNT(*) FROM emprestimos WHERE usuario_id = ? AND data_devolucao IS NULL";
        $stmt = $con->prepare($sqlCount);
        $stmt->execute([$usuario_id]);
        $qtdeAtivos = $stmt->fetchColumn();

        if ($qtdeAtivos >= 3) {
            $msg = "Este usuário já possui 3 empréstimos ativos. Não pode realizar novo empréstimo.";
            $tipoMsg = "erro";
        } else {
            // Verifica se já tem empréstimo ativo desse livro para o usuário
            $sqlExiste = "SELECT COUNT(*) FROM emprestimos WHERE usuario_id = ? AND livro_id = ? AND data_devolucao IS NULL";
            $stmt = $con->prepare($sqlExiste);
            $stmt->execute([$usuario_id, $livro_id]);
            $existe = $stmt->fetchColumn();

            if ($existe > 0) {
                $msg = "Este usuário já possui um empréstimo ativo desse livro.";
                $tipoMsg = "erro";
            } else {
                // Verifica estoque do livro
                $sqlEstoque = "SELECT quantidade FROM livros WHERE id = ?";
                $stmt = $con->prepare($sqlEstoque);
                $stmt->execute([$livro_id]);
                $quantidade = $stmt->fetchColumn();

                if (!$quantidade || $quantidade <= 0) {
                    $msg = "Livro indisponível (estoque esgotado).";
                    $tipoMsg = "erro";
                } else {
                    // Realiza empréstimo
                    $sqlInserir = "INSERT INTO emprestimos (usuario_id, livro_id, data_emprestimo) VALUES (?, ?, NOW())";
                    $stmt = $con->prepare($sqlInserir);
                    if ($stmt->execute([$usuario_id, $livro_id])) {
                        // Atualiza estoque do livro
                        $sqlAtualizaLivro = "UPDATE livros SET quantidade = quantidade - 1 WHERE id = ?";
                        $stmtUpdate = $con->prepare($sqlAtualizaLivro);
                        $stmtUpdate->execute([$livro_id]);

                        $msg = "Empréstimo realizado com sucesso!";
                        $tipoMsg = "sucesso";
                    } else {
                        $msg = "Erro ao realizar empréstimo. Tente novamente.";
                        $tipoMsg = "erro";
                    }
                }
            }
        }
    }
}

// Buscar usuários e livros disponíveis (para o formulário)
$usuarios = $con->query("SELECT id, nome FROM usuarios ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$livros = $con->query("SELECT id, titulo FROM livros WHERE quantidade > 0 ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);

// Buscar empréstimos existentes (para histórico)
$emprestimos = $con->query("
    SELECT e.id, u.nome AS usuario, l.titulo AS livro, e.data_emprestimo, e.data_devolucao
    FROM emprestimos e
    JOIN usuarios u ON e.usuario_id = u.id
    JOIN livros l ON e.livro_id = l.id
    ORDER BY e.data_emprestimo DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>


<?php
// Mostrar mensagens
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<div class="msg-sucesso">Empréstimo realizado com sucesso!</div>';
} elseif (isset($_GET['error'])) {
    // htmlspecialchars para evitar injeção XSS
    $erro = htmlspecialchars($_GET['error']);
    echo '<div class="msg-erro">Erro: ' . $erro . '</div>';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Livros Emprestados</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon" />
</head>

<body>

 <div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
  </div>
  
  <main class="form-container">
    <h2>Realizar Empréstimo</h2>

     <?php
  if (isset($_GET['success']) && $_GET['success'] == '1') {
      echo '<div class="msg-sucesso">Empréstimo realizado com sucesso!</div>';
  } elseif (isset($_GET['error'])) {
      $erro = htmlspecialchars($_GET['error']);
      echo '<div class="msg-erro">Erro: ' . $erro . '</div>';
  }
  ?>

    <?php if ($msg): ?>
      <div class="<?= $tipoMsg === 'erro' ? 'msg-erro' : 'msg-sucesso' ?>">
        <?= htmlspecialchars($msg) ?>
      </div>
    <?php endif; ?>

    <form action="emprestar_livro.php" method="POST">
      <select name="usuario_id" required>
        <option value="">Selecione o Usuário</option>
        <?php foreach ($usuarios as $usuario): ?>
          <option value="<?= $usuario['id'] ?>" <?= (isset($usuario_id) && $usuario_id == $usuario['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($usuario['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="livro_id" required>
        <option value="">Selecione o Livro</option>
        <?php foreach ($livros as $livro): ?>
          <option value="<?= $livro['id'] ?>" <?= (isset($livro_id) && $livro_id == $livro['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($livro['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Emprestar</button>
    </form>

    <h3>Histórico de Empréstimos</h3>
    <div class="estoque-lista">
      <?php foreach ($emprestimos as $emp): ?>
        <div class="livro-card">
          <p><strong>Usuário:</strong> <?= htmlspecialchars($emp['usuario']) ?></p>
          <p><strong>Livro:</strong> <?= htmlspecialchars($emp['livro']) ?></p>
          <p><strong>Data de Empréstimo:</strong> <?= date("d/m/Y", strtotime($emp['data_emprestimo'])) ?></p>
          <p><strong>Data de Devolução:</strong> <?= $emp['data_devolucao'] ? date("d/m/Y", strtotime($emp['data_devolucao'])) : 'Em andamento' ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="button-group centralizado" style="margin-top: 40px;">
      <a href="biblioteca.php" class="btn-secondary">Voltar</a>
    </div>
  </main>

  <script src="assets/js/validacao.js"></script>
</body>
</html>
