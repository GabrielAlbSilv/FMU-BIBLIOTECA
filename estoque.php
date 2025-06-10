<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

include("conexao.php");

// Busca todos os livros
$stmt = $con->prepare("SELECT * FROM livros ORDER BY titulo ASC");
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Biblioteca Novo Horizonte - Estoque</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" href="assets/images/icon.png" type="image/x-icon" />
</head>
<body>

 <div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
  </div>

  <main>
    <h2>Estoque de Livros</h2>

    <div class="estoque-lista">
      <?php if (count($livros) === 0): ?>
        <p>Nenhum livro cadastrado.</p>
      <?php else: ?>
        <?php foreach ($livros as $livro): ?>
          <div class="livro-card">
            <h3><?= htmlspecialchars($livro['titulo']) ?></h3>
            <p><strong>Autor:</strong> <?= htmlspecialchars($livro['autor']) ?></p>
            <p><strong>Editora:</strong> <?= htmlspecialchars($livro['editora']) ?></p>
            <p><strong>País de Publicação:</strong> <?= htmlspecialchars($livro['pais_publicacao']) ?></p>
            <p><strong>Gêneros:</strong> <?= htmlspecialchars($livro['genero']) ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
            <p><strong>Ano de Publicação:</strong> <?= htmlspecialchars($livro['ano_publicacao']) ?></p>
            <p><strong>Quantidade em Estoque:</strong> <?= (int)$livro['quantidade'] ?></p>
            <p><strong>Páginas:</strong> <?= (int)$livro['paginas'] ?></p>
            <p><strong>Idade Recomendada:</strong> <?= htmlspecialchars($livro['idade_recomendada']) ?></p>
            <p><strong>Idioma:</strong> <?= htmlspecialchars($livro['idioma']) ?></p>
            <?php if (!empty($livro['capa']) && file_exists($livro['capa'])): ?>
              <img src="<?= htmlspecialchars($livro['capa']) ?>" alt="Capa do livro" class="capa-livro" />
            <?php endif; ?>
            <div class="button-group centralizado" style="margin-top: 10px;">
              <a href="editar_livro.php?id=<?= $livro['id'] ?>" class="btn-secondary">Editar</a>
              <a href="excluir_livro.php?id=<?= $livro['id'] ?>" class="btn-danger" onclick="return confirm('Tem certeza que deseja excluir este livro?');">Excluir</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="button-group centralizado" style="margin-top: 40px;">
      <a href="biblioteca.php" class="btn-secondary">Voltar</a>
    </div>
  </main>

<script src="assets/js/validacao.js"></script>

</body>
</html>
