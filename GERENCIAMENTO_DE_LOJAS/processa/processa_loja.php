<?php
    $nome_loja = $_POST['nome_loja'];
    $tipo_loja = $_POST['tipo_loja'];
    $telefone_loja = $_POST['telefone_loja'];
    $cnpj_loja = $_POST['cnpj_loja'];
    $email_loja = $_POST['email_loja'];
    $endereco_loja = $_POST['endereco_loja'];
    $login_loja = $_POST['login_loja'];
    $senha_loja = md5($_POST['senha_loja']);
    $obs_loja = $_POST['obs_loja'];

    echo $nome_loja . "<br/>";
    echo $tipo_loja . "<br/>";
    echo $telefone_loja . "<br/>";
    echo $cnpj_loja . "<br/>";
    echo $email_loja . "<br/>";
    echo $endereco_loja . "<br/>";
    echo $login_loja . "<br/>";
    echo $senha_loja . "<br/>";
    echo $obs_loja . "<br/>";

    require_once("conn.php");

    $query = "INSERT INTO loja (
        nome, tipo, telefone, cnpj, email, endereco, login, senha, obs
    ) VALUES (
        '$nome_loja',
        '$tipo_loja',
        '$telefone_loja',
        '$cnpj_loja', 
        '$email_loja', 
        '$endereco_loja', 
        '$login_loja', 
        '$senha_loja', 
        '$obs_loja'
    )";


    if (mysqli_query($conexao, $query)) {
         echo "Dados inseridos com sucesso!";
    } 
    else {
        echo "Erro ao inserir: " . mysqli_error($conexao);
    }



    


?>