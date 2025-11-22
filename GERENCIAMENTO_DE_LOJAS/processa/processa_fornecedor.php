<?php
    $nome_fornecedor = $_POST['nome_fornecedor'];
    $tipo_fornecedor = $_POST['tipo_fornecedor'];
    $telefone_fornecedor = $_POST['telefone_fornecedor'];
    $cnpj_fornecedor = $_POST['cnpj_fornecedor'];
    $email_fornecedor = $_POST['email_fornecedor'];
    $endereco_fornecedor = $_POST['endereco_fornecedor'];
    $obs_fornecedor = $_POST['obs_fornecedor'];


    require_once("conn.php");

    echo $nome_fornecedor . "<br/>";
    echo $tipo_fornecedor . "<br/>";
    echo $telefone_fornecedor . "<br/>";
    echo $cnpj_fornecedor . "<br/>";
    echo $email_fornecedor . "<br/>";
    echo $endereco_fornecedor . "<br/>";
    echo $obs_fornecedor . "<br/>";


    $query = "INSERT INTO fornecedor (nome, tipo, telefone, cnpj, email, endereco, obs) VALUES(
    '$nome_fornecedor',
    '$tipo_fornecedor',
    '$telefone_fornecedor',
    '$cnpj_fornecedor',
    '$email_fornecedor',
    '$endereco_fornecedor',
    '$obs_fornecedor')";
    
    if(mysqli_query($conexao, $query)){
        echo "Dados enviados com sucesso";
    }
    else{
        echo "Erro ao inserir " . mysqli_error($conexao);
    }

  
    


?>