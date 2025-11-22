<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("conn.php");

$id_loja = $_POST['id_loja'];
$data_compra = $_POST['data_compra'];
$valor_total = $_POST['valor_total'];
$id_fornecedor = $_POST['id_fornecedor'];
$id_pagamento = $_POST['id_pagamento'];

$query = "INSERT INTO compra (id_loja, id_fornecedor, id_pagamento, data_compra, valor_total)
VALUES ('$id_loja', '$id_fornecedor', '$id_pagamento', '$data_compra', '$valor_total')";

if (mysqli_query($conexao, $query)) {

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
            title: 'Compra cadastrada!',
            html: '<b>Valor total:</b> R$ ".addslashes($valor_total)."',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // FECHA O MODAL CORRETO
                window.parent.document.getElementById('modalCompras').style.display = 'none';

                // ATUALIZA A TELA PRINCIPAL
                window.parent.location.reload();
            }
        });
    </script>

    </body>
    </html>";
} else {

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erro ao cadastrar!',
            text: '".mysqli_error($conexao)."'
        });
    </script>";
}

mysqli_close($conexao);
?>
