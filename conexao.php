<?php 
    //arquivo de conexão com o banco de dados
    //PDO - Classe PHP Data Object - trabalhar com BD.
    //variávies: host, banco, usuário, senha
    $host="localhost";
    $db="biblioteca_fmu";
    $user="root";
    $password="";


    try {
        //instanciando a classe PDO
        $con = new PDO("mysql:host=$host;dbname=$db",$user,$password);
        
    }
    catch(PDOException $e){
        echo "Erro ao conectar: ".$e->getMessage();
    }
    
?>