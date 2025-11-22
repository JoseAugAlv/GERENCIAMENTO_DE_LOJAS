<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $identificacao_estoque = $_POST['identificacao_estoque'];
    $loja_estoque = $_POST['loja_estoque'];
    $obs_estoque = $_POST['obs_estoque'];

    if($obs_estoque == ""){
        $obs_estoque = "Nenhuma";
    }

    require_once("conn.php");

    $query = "SELECT nome FROM loja WHERE id_loja = $loja_estoque";
    $resultado = mysqli_query($conexao, $query);
    $loja_nome = "";

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $linha = mysqli_fetch_assoc($resultado);
        $loja_nome = $linha['nome'];
    }


    $query = "INSERT INTO estoque (identificacao, id_loja, obs) VALUES(
    '$identificacao_estoque',
    '$loja_estoque',
    '$obs_estoque'
    )";

    if(mysqli_query($conexao, $query)){
        // 7. BUSCAR NOME DO estoque PARA O ALERTA

        $query = "SELECT id_estoque FROM estoque WHERE identificacao = $identificacao_estoque";
        $resultado = mysqli_query($conexao, $query);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $id_estoque = $linha['id_estoque'];
        }

        $sqlIdentificacao = "SELECT Identificação FROM estoque WHERE id_estoque =  $id_estoque";
        $resIdentificacao = mysqli_query($conexao, $sqlIdentificacao);
        $identificacao_estoque= mysqli_fetch_assoc($resIdentificacao)['identificacao'];

        echo "<!DOCTYPE html>
            <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'>
                <title>Sucesso</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
            <script>
                Swal.fire({
                    title: 'Estoque cadastrado!',
                    html: '<b>identificação:</b> ".addslashes($identificacao_estoque)."<br><b>Loja:</b> ".addslashes($loja_estoque)."',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if(result.isConfirmed){
                        window.parent.document.getElementById(\"modalEstoque\").style.display = \"none\";
                        window.parent.location.reload();
                    }
                });
            </script>
            </body>
            </html>";
        }
    else{
        echo "Erro ao inserir " . mysqli_error($conexao);
    }


?>