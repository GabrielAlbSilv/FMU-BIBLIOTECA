<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST["nome"];
  $idade = $_POST["idade"];
  $sexo = $_POST["sexo"];
  $cpf = $_POST["cpf"];
  $endereco = $_POST["endereco"];
  $numero = $_POST["numero"];
  $complemento = $_POST["complemento"];
  $telefone1 = $_POST["telefone1"];
  $telefone2 = $_POST["telefone2"];
  $email = $_POST["email"];
  $senha = trim($_POST["senha"]);
  $hash = password_hash($senha, PASSWORD_DEFAULT);

  // Verifica se o e-mail já está cadastrado
  $verifica_email = $con->prepare("SELECT id FROM usuarios WHERE email = :email");
  $verifica_email->bindParam(':email', $email);
  $verifica_email->execute();

  if ($verifica_email->rowCount() > 0) {
    echo "E-MAIL OU CPF JÁ CADASTRADOS";
    exit;
  }

  // Verifica se o CPF já está cadastrado
  $verifica_cpf = $con->prepare("SELECT id FROM usuarios WHERE cpf = :cpf");
  $verifica_cpf->bindParam(':cpf', $cpf);
  $verifica_cpf->execute();

  if ($verifica_cpf->rowCount() > 0) {
    echo "E-MAIL OU CPF JÁ CADASTRADOS";
    exit;
  }

  // Prepara e executa o INSERT
  $insert = $con->prepare('INSERT INTO usuarios 
    (nome, idade, sexo, cpf, endereco, numero, complemento, telefone1, telefone2, email, senha) 
    VALUES 
    (:nome, :idade, :sexo, :cpf, :endereco, :numero, :complemento, :telefone1, :telefone2, :email, :senha)');

  $insert->bindParam(':nome', $nome);
  $insert->bindParam(':idade', $idade);
  $insert->bindParam(':sexo', $sexo);
  $insert->bindParam(':cpf', $cpf);
  $insert->bindParam(':endereco', $endereco);
  $insert->bindParam(':numero', $numero);
  $insert->bindParam(':complemento', $complemento);
  $insert->bindParam(':telefone1', $telefone1);
  $insert->bindParam(':telefone2', $telefone2);
  $insert->bindParam(':email', $email);
  $insert->bindParam(':senha', $hash);

  if ($insert->execute()) {
    header('Location: cadastro.html');
    exit;
  } else {
    echo "Erro ao cadastrar: " . $insert->errorInfo()[2];
  }
}
?>
