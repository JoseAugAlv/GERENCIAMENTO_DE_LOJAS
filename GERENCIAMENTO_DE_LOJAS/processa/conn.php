<?php
    header("Content-Type: text/html; charset=UTF-8");

    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $db = "gerenciamento_lojas";

    $conexao = new mysqli($servidor, $usuario, $senha, $db);
    if($conexao->connect_error){
        die("A conexão falhou: " . $conexao->connect_error);
    }

    $conexao->set_charset("utf8mb4");
?>
