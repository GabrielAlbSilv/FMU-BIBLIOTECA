<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $autor = trim($_POST["autor"]);
    $editora = trim($_POST["editora"]);
    $pais_publicacao = trim($_POST["pais_publicacao"]);
    $isbn = trim($_POST["isbn"]);
    $ano_publicacao = (int)$_POST["ano_publicacao"];
    $quantidade = (int)$_POST["quantidade"];
    $paginas = (int)$_POST["paginas"];
    $idade_recomendada = trim($_POST["idade_recomendada"]);
    $idioma = trim($_POST["idioma"]);

    // Recebe gêneros (array) e converte para string separada por vírgulas
    if (isset($_POST['genero']) && is_array($_POST['genero'])) {
        $genero = implode(', ', $_POST['genero']);
    } else {
        $genero = '';
    }

    // Verifica se arquivo de capa foi enviado
    if (isset($_FILES["capa"]) && $_FILES["capa"]["error"] == 0) {
        $nomeImagem = $_FILES["capa"]["name"];
        $caminhoTemporario = $_FILES["capa"]["tmp_name"];
        $diretorioDestino = "uploads/capas/";
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0755, true);
        }
        $caminhoCompleto = $diretorioDestino . uniqid() . "_" . basename($nomeImagem);

        if (!move_uploaded_file($caminhoTemporario, $caminhoCompleto)) {
            echo "Erro ao salvar imagem.";
            exit;
        }
    } else {
        $caminhoCompleto = null; // ou caminho padrão se desejar
    }

    $insert = $con->prepare("INSERT INTO livros 
        (titulo, autor, genero, isbn, ano_publicacao, quantidade, editora, pais_publicacao, paginas, idade_recomendada, idioma, capa)
        VALUES
        (:titulo, :autor, :genero, :isbn, :ano_publicacao, :quantidade, :editora, :pais_publicacao, :paginas, :idade_recomendada, :idioma, :capa)");

    $insert->bindParam(":titulo", $titulo);
    $insert->bindParam(":autor", $autor);
    $insert->bindParam(":genero", $genero);
    $insert->bindParam(":isbn", $isbn);
    $insert->bindParam(":ano_publicacao", $ano_publicacao);
    $insert->bindParam(":quantidade", $quantidade);
    $insert->bindParam(":editora", $editora);
    $insert->bindParam(":pais_publicacao", $pais_publicacao);
    $insert->bindParam(":paginas", $paginas);
    $insert->bindParam(":idade_recomendada", $idade_recomendada);
    $insert->bindParam(":idioma", $idioma);
    $insert->bindParam(":capa", $caminhoCompleto);

    if ($insert->execute()) {
        header("Location: biblioteca.index");
        exit;
    } else {
        echo "Erro ao cadastrar livro: " . $insert->errorInfo()[2];
    }
} else {
    echo "Requisição inválida.";
}
