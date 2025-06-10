<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

include("conexao.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: estoque.php");
    exit;
}

$id = (int)$_GET['id'];

// Buscar dados atuais do livro
$stmt = $con->prepare("SELECT * FROM livros WHERE id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "Livro não encontrado.";
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber dados do formulário
    $titulo = trim($_POST["titulo"]);
    $autor = trim($_POST["autor"]);
    $genero = trim($_POST["genero"]);
    $isbn = trim($_POST["isbn"]);
    $ano_publicacao = (int)$_POST["ano_publicacao"];
    $quantidade = (int)$_POST["quantidade"];
    $editora = trim($_POST["editora"]);
    $pais_publicacao = trim($_POST["pais_publicacao"]);
    $paginas = (int)$_POST["paginas"];
    $idade_recomendada = trim($_POST["idade_recomendada"]);
    $idioma = trim($_POST["idioma"]);

    // Verificar se um novo arquivo de capa foi enviado
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] == UPLOAD_ERR_OK) {
        $nomeImagem = $_FILES["capa"]["name"];
        $caminhoTemporario = $_FILES["capa"]["tmp_name"];
        $diretorioDestino = "uploads/capas/";
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0755, true);
        }
        $novoCaminho = $diretorioDestino . uniqid() . "_" . basename($nomeImagem);

        if (move_uploaded_file($caminhoTemporario, $novoCaminho)) {
            // Apagar a capa antiga, se existir
            if (!empty($livro['capa']) && file_exists($livro['capa'])) {
                unlink($livro['capa']);
            }
            $caminhoCapa = $novoCaminho;
        } else {
            $erro = "Erro ao salvar a nova imagem da capa.";
        }
    } else {
        // Mantém a capa antiga
        $caminhoCapa = $livro['capa'];
    }

    if (!$erro) {
        // Atualizar dados no banco
        $update = $con->prepare("UPDATE livros SET 
            titulo = :titulo,
            autor = :autor,
            genero = :genero,
            isbn = :isbn,
            ano_publicacao = :ano_publicacao,
            quantidade = :quantidade,
            editora = :editora,
            pais_publicacao = :pais_publicacao,
            paginas = :paginas,
            idade_recomendada = :idade_recomendada,
            idioma = :idioma,
            capa = :capa
            WHERE id = :id
        ");

        $update->bindParam(":titulo", $titulo);
        $update->bindParam(":autor", $autor);
        $update->bindParam(":genero", $genero);
        $update->bindParam(":isbn", $isbn);
        $update->bindParam(":ano_publicacao", $ano_publicacao);
        $update->bindParam(":quantidade", $quantidade);
        $update->bindParam(":editora", $editora);
        $update->bindParam(":pais_publicacao", $pais_publicacao);
        $update->bindParam(":paginas", $paginas);
        $update->bindParam(":idade_recomendada", $idade_recomendada);
        $update->bindParam(":idioma", $idioma);
        $update->bindParam(":capa", $caminhoCapa);
        $update->bindParam(":id", $id, PDO::PARAM_INT);

        if ($update->execute()) {
            $sucesso = "Livro atualizado com sucesso!";
            // Atualizar variável $livro para mostrar dados atualizados no formulário
            $livro = array_merge($livro, [
                'titulo' => $titulo,
                'autor' => $autor,
                'genero' => $genero,
                'isbn' => $isbn,
                'ano_publicacao' => $ano_publicacao,
                'quantidade' => $quantidade,
                'editora' => $editora,
                'pais_publicacao' => $pais_publicacao,
                'paginas' => $paginas,
                'idade_recomendada' => $idade_recomendada,
                'idioma' => $idioma,
                'capa' => $caminhoCapa
            ]);
        } else {
            $erro = "Erro ao atualizar o livro: " . implode(" ", $update->errorInfo());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Livro - Biblioteca Novo Horizonte</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="icon" href="assets/images/icon.png" type="image/x-icon" />
</head>
<body>

<div id="usuario-logado-box" class="usuario-box loading">
    Carregando usuário...
</div>

<main class="form-container">
    <h2>Editar Livro</h2>

    <?php if ($erro): ?>
        <p class="erro"><?=htmlspecialchars($erro)?></p>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <p class="sucesso"><?=htmlspecialchars($sucesso)?></p>
    <?php endif; ?>

    <form action="editar_livro.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" value="<?= htmlspecialchars($livro['titulo']) ?>" required />
        <input type="text" name="autor" placeholder="Autor" value="<?= htmlspecialchars($livro['autor']) ?>" required />
        <input type="text" name="editora" placeholder="Editora" value="<?= htmlspecialchars($livro['editora']) ?>" required />
        <input type="text" name="pais_publicacao" placeholder="País de Publicação" value="<?= htmlspecialchars($livro['pais_publicacao']) ?>" required />
        <input type="text" name="genero" placeholder="Gêneros (separe por vírgula)" value="<?= htmlspecialchars($livro['genero']) ?>" required />
        <input type="text" name="isbn" id="isbn" placeholder="ISBN (ex: 978-3-16-148410-0)" maxlength="17" value="<?= htmlspecialchars($livro['isbn']) ?>" required />
        <input type="number" name="ano_publicacao" placeholder="Ano de Publicação" min="1000" max="9999" value="<?= htmlspecialchars($livro['ano_publicacao']) ?>" required />
        <input type="number" name="quantidade" placeholder="Quantidade em estoque" min="0" value="<?= htmlspecialchars($livro['quantidade']) ?>" required />
        <input type="number" name="paginas" placeholder="Quantidade de Páginas" min="1" value="<?= htmlspecialchars($livro['paginas']) ?>" required />
        <input type="text" name="idade_recomendada" placeholder="Idade Recomendada (ex: a partir de 12 anos)" value="<?= htmlspecialchars($livro['idade_recomendada']) ?>" required />
        <input type="text" name="idioma" placeholder="Idioma" value="<?= htmlspecialchars($livro['idioma']) ?>" required />

        <label for="capa">Capa do livro (deixe em branco para manter a atual):</label><br />
        <?php if (!empty($livro['capa']) && file_exists($livro['capa'])): ?>
            <img src="<?= htmlspecialchars($livro['capa']) ?>" alt="Capa do livro" style="max-width:150px; margin-bottom:10px;"><br />
        <?php endif; ?>
        <input type="file" name="capa" id="capa" accept="image/*" />

        <button type="submit">Atualizar Livro</button>
        <div class="centralizado" style="margin-top: 10px;">
            <a href="estoque.php" class="btn-secondary btn-small">Voltar</a>
        </div>
    </form>
</main>

<script src="assets/js/validacao.js"></script>
</body>
</html>
