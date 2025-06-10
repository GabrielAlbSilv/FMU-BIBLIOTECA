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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Biblioteca Novo Horizonte</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon" />
</head>
<body>
  <div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
  </div>
  <main class="form-container">
    <h2>Cadastro de Livro</h2>
    <form action="cadastrar_livro.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="titulo" placeholder="Título" required />
      <input type="text" name="autor" placeholder="Autor" required />
      <input type="text" name="editora" placeholder="Editora" required />
      <input type="text" name="pais_publicacao" placeholder="País de Publicação" required />

      <p>Gêneros (selecione um ou mais):</p>
      <input type="checkbox" id="gen-ficcao" name="genero[]" value="Ficção" />
      <label for="gen-ficcao">Ficção</label>

      <input type="checkbox" id="gen-romance" name="genero[]" value="Romance" />
      <label for="gen-romance">Romance</label>

      <input type="checkbox" id="gen-aventura" name="genero[]" value="Aventura" />
      <label for="gen-aventura">Aventura</label>

      <input type="checkbox" id="gen-fantasia" name="genero[]" value="Fantasia" />
      <label for="gen-fantasia">Fantasia</label>

      <input type="checkbox" id="gen-historico" name="genero[]" value="Histórico" />
      <label for="gen-historico">Histórico</label>

      <input type="checkbox" id="gen-biografia" name="genero[]" value="Biografia" />
      <label for="gen-biografia">Biografia</label>

      <input type="checkbox" id="gen-tecnico" name="genero[]" value="Técnico" />
      <label for="gen-tecnico">Técnico</label>

      <br /><br />

      <input type="text" name="isbn" id="isbn" placeholder="ISBN (ex: 978-3-16-148410-0)" maxlength="17" required />
      <input type="number" name="ano_publicacao" placeholder="Ano de Publicação" min="1000" max="9999" required />
      <input type="number" name="quantidade" placeholder="Quantidade em estoque" min="0" required />
      <input type="number" name="paginas" placeholder="Quantidade de Páginas" min="1" required />
      <input type="text" name="idade_recomendada" placeholder="Idade Recomendada (ex: a partir de 12 anos)" required />
      <input type="text" name="idioma" placeholder="Idioma" required />

      <label for="capa">Capa do Livro:</label>
      <input type="file" name="capa" id="capa" accept="image/*" required />

      <button type="submit">Cadastrar</button>

      <div class="centralizado">
        <a href="biblioteca.php" class="btn-secondary btn-small">Voltar</a>
      </div>
    </form>
  </main>
<script src="assets/js/validacao.js"></script>

</body>
</html>
