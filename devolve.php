<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.html");
    exit;
}

include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = $_POST['cpf'] ?? '';
    $isbn = $_POST['isbn'] ?? '';
    $dataDevolucao = $_POST['data_devolucao'] ?? '';

    if (empty($cpf) || empty($isbn) || empty($dataDevolucao)) {
        echo "CPF, ISBN e data de devolução são obrigatórios.";
        exit;
    }

    try {
        // Buscar ID do usuário pelo CPF
        $stmtUser = $con->prepare("SELECT id FROM usuarios WHERE cpf = ?");
        $stmtUser->execute([$cpf]);
        $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo "Usuário com esse CPF não foi encontrado.";
            exit;
        }

        // Buscar ID do livro pelo ISBN
        $stmtLivro = $con->prepare("SELECT id FROM livros WHERE isbn = ?");
        $stmtLivro->execute([$isbn]);
        $livro = $stmtLivro->fetch(PDO::FETCH_ASSOC);

        if (!$livro) {
            echo "Livro com esse ISBN não foi encontrado.";
            exit;
        }

        $usuario_id = $usuario['id'];
        $livro_id = $livro['id'];

        // Buscar empréstimo ativo (sem data de devolução) para esse usuário e livro
        $stmtEmprestimo = $con->prepare("
            SELECT id FROM emprestimos
            WHERE usuario_id = ? AND livro_id = ? AND data_devolucao IS NULL
            LIMIT 1
        ");
        $stmtEmprestimo->execute([$usuario_id, $livro_id]);
        $emprestimo = $stmtEmprestimo->fetch(PDO::FETCH_ASSOC);

        if (!$emprestimo) {
            echo "Nenhum empréstimo ativo encontrado para este CPF e ISBN.";
            exit;
        }

        $emprestimo_id = $emprestimo['id'];

        // Atualizar a data de devolução com o valor enviado no formulário
        $stmtUpdate = $con->prepare("UPDATE emprestimos SET data_devolucao = ? WHERE id = ?");
        $stmtUpdate->execute([$dataDevolucao, $emprestimo_id]);

        // Atualizar a quantidade de livros (incrementar)
        $stmtUpdateLivro = $con->prepare("UPDATE livros SET quantidade = quantidade + 1 WHERE id = ?");
        $stmtUpdateLivro->execute([$livro_id]);

        echo "Livro devolvido com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao processar a devolução: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
}
?>
