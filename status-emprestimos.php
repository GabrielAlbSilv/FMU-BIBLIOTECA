<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}
include("conexao.php");

// Buscar empréstimos ativos (em andamento)
$sqlAtivos = "
    SELECT 
        e.id AS emprestimo_id,
        u.id AS usuario_id,
        u.nome AS nome_usuario,
        u.email,
        u.telefone1,
        u.telefone2,
        u.endereco,
        l.titulo AS titulo_livro,
        l.editora,
        l.isbn,
        e.data_emprestimo
    FROM emprestimos e
    INNER JOIN usuarios u ON e.usuario_id = u.id
    INNER JOIN livros l ON e.livro_id = l.id
    WHERE e.data_devolucao IS NULL
    ORDER BY e.data_emprestimo DESC
";
$ativos = $con->query($sqlAtivos)->fetchAll(PDO::FETCH_ASSOC);

// Buscar empréstimos finalizados (com devolução)
$sqlFinalizados = "
    SELECT 
        e.id AS emprestimo_id,
        u.id AS usuario_id,
        u.nome AS nome_usuario,
        l.titulo AS titulo_livro,
        l.isbn,
        e.data_emprestimo,
        e.data_devolucao
    FROM emprestimos e
    INNER JOIN usuarios u ON e.usuario_id = u.id
    INNER JOIN livros l ON e.livro_id = l.id
    WHERE e.data_devolucao IS NOT NULL
    ORDER BY e.data_devolucao DESC
";
$finalizados = $con->query($sqlFinalizados)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Status dos Empréstimos</title>
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

 <div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
  </div>
  <main class="form-container">
    <h2>Empréstimos Ativos</h2>
    <?php if (count($ativos) > 0): ?>
      <div class="estoque-lista">
        <?php foreach ($ativos as $emp): ?>
          <div class="livro-card">
            <h3><?= htmlspecialchars($emp['nome_usuario']) ?></h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($emp['email']) ?></p>
            <p><strong>Telefone 1:</strong> <?= htmlspecialchars($emp['telefone1']) ?></p>
            <p><strong>Telefone 2:</strong> <?= htmlspecialchars($emp['telefone2']) ?></p>
            <p><strong>Endereço:</strong> <?= htmlspecialchars($emp['endereco']) ?></p>
            <p><strong>Livro:</strong> <?= htmlspecialchars($emp['titulo_livro']) ?></p>
            <p><strong>Editora:</strong> <?= htmlspecialchars($emp['editora']) ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($emp['isbn']) ?></p>
            <p><strong>Data do Empréstimo:</strong> <?= date("d/m/Y", strtotime($emp['data_emprestimo'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Nenhum empréstimo ativo no momento.</p>
    <?php endif; ?>

    <hr style="margin: 40px 0;">

    <h2>Histórico de Devoluções</h2>
    <?php if (count($finalizados) > 0): ?>
      <div class="estoque-lista">
        <?php foreach ($finalizados as $emp): ?>
          <div class="livro-card">
            <h3><?= htmlspecialchars($emp['nome_usuario']) ?></h3>
            <p><strong>Livro:</strong> <?= htmlspecialchars($emp['titulo_livro']) ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($emp['isbn']) ?></p>
            <p><strong>Data do Empréstimo:</strong> <?= date("d/m/Y", strtotime($emp['data_emprestimo'])) ?></p>
            <p><strong>Data da Devolução:</strong> <?= date("d/m/Y", strtotime($emp['data_devolucao'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Sem histórico de devoluções.</p>
    <?php endif; ?>

    <div class="centralizado" style="margin-top: 30px;">
      <a href="emprestimos.php" class="btn-secondary btn-small">Voltar</a>
    </div>
  </main>

   <script src="assets/js/validacao.js"></script> 

</body>
</html>
