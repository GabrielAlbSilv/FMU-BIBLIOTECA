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


  <header>
    <h1>Biblioteca Novo Horizonte</h1>
    
  </header>

 <div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
  </div>

  <main>
    <section class="opcoes">
      <h2>Menu Principal</h2>
      <div class="card-group">
        <a href="cadastro-livros.html" class="card-opcao">
          <img src="assets/images/cadastrar.png" alt="Cadastrar Livro">
          <span>Cadastrar Livro</span>
        </a>
        <a href="estoque.html" class="card-opcao">
          <img src="assets/images/estoque.png" alt="Consultar Estoque">
          <span>Consultar Estoque</span>
        </a>
        <a href="emprestimos.html" class="card-opcao">
          <img src="assets/images/emprestimos.png" alt="Verificar Emprestados">
          <span>Empréstimos</span>
        </a>
      </div>
    </section>

    <div class="centralizado">
      <a href="index.html" class="btn-secondary btn-small">Voltar</a>
      <a href="logout.php" class="btn-secondary btn-small">Sair</a>
    </div>
  </main>

</div>


<script src="assets/js/validacao.js"></script>
</body>
</html>
