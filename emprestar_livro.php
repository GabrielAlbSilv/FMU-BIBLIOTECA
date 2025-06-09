<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $usuario_id = $_POST["usuario_id"];
  $livro_id = $_POST["livro_id"];
  $data = date("Y-m-d");

  $sql = "INSERT INTO emprestimos (usuario_id, livro_id, data_emprestimo)
          VALUES ('$usuario_id', '$livro_id', '$data')";

  if ($conn->query($sql) === TRUE) {
    header("Location: emprestimos.html");
  } else {
    echo "Erro ao registrar empréstimo: " . $conn->error;
  }

  $conn->close();
}
?>
