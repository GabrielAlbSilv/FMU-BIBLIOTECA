<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = (int)$_POST["usuario_id"];
    $livro_id = (int)$_POST["livro_id"];

    try {
        $con->beginTransaction();

        // Verificar quantos empréstimos ativos o usuário possui
        $stmt = $con->prepare("SELECT COUNT(*) FROM emprestimos WHERE usuario_id = :usuario_id AND data_devolucao IS NULL");
        $stmt->execute([':usuario_id' => $usuario_id]);
        $qtdeAtivos = $stmt->fetchColumn();

        if ($qtdeAtivos >= 3) {
            throw new Exception("Este usuário já possui 3 empréstimos ativos.");
        }

        // Verificar se o usuário já tem empréstimo ativo do mesmo livro
        $stmt = $con->prepare("SELECT COUNT(*) FROM emprestimos WHERE usuario_id = :usuario_id AND livro_id = :livro_id AND data_devolucao IS NULL");
        $stmt->execute([':usuario_id' => $usuario_id, ':livro_id' => $livro_id]);
        $jaEmprestado = $stmt->fetchColumn();

        if ($jaEmprestado > 0) {
            throw new Exception("Este usuário já possui um empréstimo ativo desse livro.");
        }

        // Verificar se o livro está disponível
        $livro = $con->prepare("SELECT quantidade FROM livros WHERE id = :livro_id");
        $livro->execute([':livro_id' => $livro_id]);
        $quantidade = $livro->fetchColumn();

        if ($quantidade <= 0) {
            throw new Exception("Livro indisponível.");
        }

        // Inserir empréstimo
        $stmt = $con->prepare("INSERT INTO emprestimos (usuario_id, livro_id, data_emprestimo) VALUES (:usuario_id, :livro_id, NOW())");
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':livro_id' => $livro_id
        ]);

        // Atualizar estoque
        $con->prepare("UPDATE livros SET quantidade = quantidade - 1 WHERE id = :livro_id")
            ->execute([':livro_id' => $livro_id]);

        $con->commit();
        header("Location: novo-emprestimo.php?success=1");
        exit;
    } catch (Exception $e) {
        $con->rollBack();
        // Pode enviar o erro via GET para exibir na página ou exibir direto aqui
        header("Location: novo-emprestimo.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
?>
