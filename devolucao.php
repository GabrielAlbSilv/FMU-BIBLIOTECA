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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Devolução de Livros</title>
  <link rel="stylesheet" href="assets/css/style.css"/>
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon"/>
  <script src="assets/js/validacao.js" defer></script>
</head>

<body>
  <main class="form-container">
    <h2>Devolução de Livros</h2>

    <form action="devolve.php" method="POST">
      <label for="cpf">CPF:</label>
      <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" oninput="mascaraCPF(this)" required />

      <label for="isbn">ISBN:</label>
      <input type="text" id="isbn" name="isbn" placeholder="978-0-00-000000-0" required />

      <label for="data_devolucao">Data da Devolução:</label>
      <input type="date" id="data_devolucao" name="data_devolucao" value="<?= date('Y-m-d') ?>" required />

      <div class="centralizado" style="margin-top: 20px;">
        <button type="submit">Confirmar Devolução</button>
        <a href="emprestimos.php" class="btn-secondary btn-small">Voltar</a>
      </div>
    </form>
  </main>
</body>
</html>
