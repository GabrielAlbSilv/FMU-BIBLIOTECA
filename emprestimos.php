<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca Novo Horizonte</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon">
</head>
<body>
 <div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
  </div>

  <main class="form-container">
    <h2>Empréstimos</h2>

    <div class="card-group">
      <a href="novo-emprestimo.php" class="card-opcao">
        <img src="assets/images/emprestarlivro.png" alt="Emprestar livro">
        <span>Emprestar livro</span>
      </a>
      <a href="status-emprestimos.php" class="card-opcao">
        <img src="assets/images/statusemprestimo.png" alt="Consultar empréstimos">
        <span>Consultar empréstimos</span>
      </a>
      <a href="devolucao.php" class="card-opcao">
        <img src="assets/images/devolucao.png" alt="Devolução">
        <span>Devolução</span>
      </a>
    </div>

    <div class="centralizado">
      <a href="biblioteca.php" class="btn-secondary btn-small">Voltar</a>
    </div>
  </main>

  <script src="assets/js/validacao.js"></script>
</body>


</html>
