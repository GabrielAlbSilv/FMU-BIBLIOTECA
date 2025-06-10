<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redireciona se não houver id
    header("Location: estoque.php");
    exit;
}

include("conexao.php");

$id = (int)$_GET['id'];

// Primeiro, pegar o caminho da capa para apagar o arquivo
$stmt = $con->prepare("SELECT capa FROM livros WHERE id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if ($livro) {
    if (!empty($livro['capa']) && file_exists($livro['capa'])) {
        unlink($livro['capa']); // Apaga o arquivo da capa
    }

    // Apaga o livro no banco
    $delete = $con->prepare("DELETE FROM livros WHERE id = :id");
    $delete->bindParam(":id", $id, PDO::PARAM_INT);
    $delete->execute();
}

// Redireciona para estoque
header("Location: estoque.php");
exit;
