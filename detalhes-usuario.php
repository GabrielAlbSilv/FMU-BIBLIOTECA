<?php
if (!isset($_GET["id"])) {
    header("Location: status-emprestimos.php");
    exit;
}

include("conexao.php");

$usuario_id = $_GET["id"];

// Buscar dados do usuário
$sqlUsuario = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $con->prepare($sqlUsuario);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar todos os empréstimos desse usuário
$sqlEmprestimos = "
    SELECT 
        e.id AS emprestimo_id,
        l.titulo,
        l.editora,
        l.isbn,
        e.data_emprestimo,
        e.data_devolucao
    FROM emprestimos e
    INNER JOIN livros l ON e.livro_id = l.id
    WHERE e.usuario_id = ?
    ORDER BY e.data_emprestimo DESC
";
$stmt = $con->prepare($sqlEmprestimos);
$stmt->execute([$usuario_id]);
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Detalhes do Usuário</title>
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main class="form-container">
    <h2>Detalhes do Usuário</h2>

    <?php if ($usuario): ?>
      <div class="usuario-info">
        <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
        <p><strong>Idade:</strong> <?= htmlspecialchars($usuario['idade']) ?></p>
        <p><strong>Sexo:</strong> <?= htmlspecialchars($usuario['sexo']) ?></p>
        <p><strong>CPF:</strong> <?= htmlspecialchars($usuario['cpf']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Telefone 1:</strong> <?= htmlspecialchars($usuario['telefone1']) ?></p>
        <p><strong>Telefone 2:</strong> <?= htmlspecialchars($usuario['telefone2']) ?></p>
        <p><strong>Endereço:</strong> <?= htmlspecialchars($usuario['endereco']) ?>, Nº <?= htmlspecialchars($usuario['numero']) ?> <?= $usuario['complemento'] ? '- ' . htmlspecialchars($usuario['complemento']) : '' ?></p>
      </div>

      <h3>Empréstimos Realizados</h3>
      <?php if (count($emprestimos) > 0): ?>
        <ul>
          <?php foreach ($emprestimos as $emp): ?>
            <li style="margin-bottom: 10px;">
              <strong><?= htmlspecialchars($emp['titulo']) ?></strong> - 
              <?= htmlspecialchars($emp['editora']) ?> | ISBN: <?= htmlspecialchars($emp['isbn']) ?><br>
              <small>Empréstimo: <?= date("d/m/Y", strtotime($emp['data_emprestimo'])) ?> | 
              Devolução: <?= $emp['data_devolucao'] ? date("d/m/Y", strtotime($emp['data_devolucao'])) : "Ainda não devolvido" ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>Este usuário ainda não realizou empréstimos.</p>
      <?php endif; ?>
    <?php else: ?>
      <p>Usuário não encontrado.</p>
    <?php endif; ?>

    <div class="centralizado" style="margin-top: 30px;">
      <a href="status-emprestimos.php" class="btn-secondary btn-small">Voltar</a>
    </div>
  </main>
</body>
</html>
