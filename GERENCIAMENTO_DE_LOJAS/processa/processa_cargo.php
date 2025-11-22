<?php
    // Ativa todos os erros para depuração
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Recebe os dados do formulário
    $nome_cargo = $_POST['nome_cargo'];
    $descricao_cargo = $_POST['descricao_cargo'];
    $permicoes = isset($_POST['permicoes']) ? $_POST['permicoes'] : [];

    require_once("conn.php");

    // Insere o cargo na tabela cargo
    $query_cargo = "INSERT INTO cargo (nome) VALUES ('$nome_cargo')";
    if (mysqli_query($conexao, $query_cargo)) {

        // Recupera o ID do cargo recém-inserido
        $id_cargo = mysqli_insert_id($conexao);

        // Se houver permissões selecionadas
        if (!empty($permicoes)) {
            foreach ($permicoes as $id_permicao) {
                $query_relacao = "INSERT INTO cargo_permicao (id_cargo, id_permicao) 
                                  VALUES ('$id_cargo', '$id_permicao')";
                mysqli_query($conexao, $query_relacao);
            }
        }

        // SWEETALERT DE SUCESSO
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
                title: 'Cargo cadastrado',
                html: '<b>Nome:</b> " . addslashes($nome_cargo) . "<br>' +
                      '<b>Permições:</b> " . (empty($permicoes) ? "Nenhuma" : count($permicoes)) . "',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if(result.isConfirmed){
                    window.parent.document.getElementById(\"modalCargo\").style.display = \"none\";
                    window.parent.location.reload();
                }
            });
        </script>
        </body>
        </html>";

    } else {

        // SWEETALERT DE ERRO
        $erro = addslashes(mysqli_error($conexao));

        echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Erro</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                title: 'Erro ao cadastrar',
                html: 'Detalhes: $erro',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
        </body>
        </html>";
    }

    mysqli_close($conexao);
?>
