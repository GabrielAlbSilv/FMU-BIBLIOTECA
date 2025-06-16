<?php
session_start(); // Inicia a sessão
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Consulta o usuário pelo e-mail
    $stmt = $con->prepare("SELECT id, nome, senha FROM usuarios WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha digitada confere com o hash no banco
        if (password_verify($senha, $usuario["senha"])) {
            // Login bem-sucedido: salva dados na sessão e redireciona
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];

            header("Location: biblioteca.php");
            exit;
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href='index.html';</script>";
            exit;
            header("Location: index.html");
        }
    } else {
        echo "<script>alert('E-mail não encontrado.'); window.location.href='index.html';</script>";
        exit;
    }
}
?>
