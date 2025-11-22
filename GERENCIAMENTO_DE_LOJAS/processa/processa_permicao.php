<?php
    $nome_permicao = $_POST['nome_permicao'];
    $descricao_permicao = $_POST['descricao_permicao'];

    require_once("conn.php");

    echo $nome_permicao . "<br/>";
    echo $descricao_permicao . "<br/>";

    $query = "INSERT INTO permicao (nome, descricao) VALUES(
    '$nome_permicao',
    '$descricao_permicao'
    )";

    if(mysqli_query($conexao, $query)){
        echo "Dados enviados com sucesso" . "<br/>";
    }
    else{
        echo "Erro ao inserir " . mysqli_error($conexao);
    }


?>