<?php
    require_once '../conn.php';
    session_start();

    $id_loja = $_SESSION['id_loja'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Estoque</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/tabelas_excluir.css">
</head>
<body>

    <?php
        $sql = "SELECT id_estoque, identificacao, obs 
                FROM estoque 
                WHERE id_loja = $id_loja
                ORDER BY id_estoque";

        $res = mysqli_query($conexao, $sql);

        if ($res && mysqli_num_rows($res) > 0) {

            echo '<div class="table-container">';
            echo '<table>';
            echo '<tr>
                    <th>ID</th>
                    <th>Identificação</th>
                    <th>Observação</th>
                </tr>';

            while ($e = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$e['id_estoque']}</td>
                        <td>{$e['identificacao']}</td>
                        <td>{$e['obs']}</td>
                    </tr>";
            }

            echo '</table>';
            echo '</div>';

        } else {
            echo "Nenhum estoque encontrado.";
        }
    ?>

    <form id="formExcluir" method="post">
        <label for="sltEstoque">Selecione o estoque para excluir:</label>

        <select name="sltEstoque" id="sltEstoque" required>
            <option value="">Selecione o estoque</option>

            <?php
            $res2 = mysqli_query($conexao,
                "SELECT id_estoque, identificacao 
                FROM estoque 
                WHERE id_loja = $id_loja");

            while ($e2 = mysqli_fetch_assoc($res2)) {
                echo '<option value="'.$e2['id_estoque'].'">'.
                        $e2['id_estoque'].' - '.$e2['identificacao'].
                    '</option>';
            }
            ?>
        </select>

        <input type="submit" value="Excluir">
    </form>

    <button type="button">Fechar</button>

    <?php
    // PROCESSAR EXCLUSÃO
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['sltEstoque'])) {

            $id_estoque = intval($_POST['sltEstoque']);

            // VERIFICAR PRODUTOS VINCULADOS
            $checkProd = mysqli_query($conexao,
                "SELECT COUNT(*) AS qt 
                FROM produto 
                WHERE id_estoque = $id_estoque");

            $rowProd = mysqli_fetch_assoc($checkProd);

            if ($rowProd['qt'] > 0) {
                echo "<script>
                    Swal.fire({
                        title: 'Não é possível excluir',
                        html: 'Existem <b>{$rowProd['qt']}</b> produto(s) vinculados a este estoque.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                </script>";
                exit;
            }

            // BUSCA NOME PARA EXIBIR NO ALERT
            $buscar = mysqli_query($conexao,
                "SELECT identificacao 
                FROM estoque 
                WHERE id_estoque = $id_estoque");
                
            $nomeEstoque = mysqli_fetch_assoc($buscar)['identificacao'];
            $nomeEstoque = addslashes($nomeEstoque);

            // APAGAR RELAÇÃO EM estoque_produto
            mysqli_query($conexao,
                "DELETE FROM estoque_produto WHERE id_estoque = $id_estoque");

            // EXCLUIR ESTOQUE
            mysqli_query($conexao,
                "DELETE FROM estoque WHERE id_estoque = $id_estoque");

            ?>
            <script>
            Swal.fire({
                title: 'Estoque excluído',
                html: '<b>Identificação:</b> <?= $nomeEstoque ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.parent.location.reload();
            });
            </script>
            <?php
            exit;
        }
    }
    ?>

    <script>
    const form = document.getElementById("formExcluir");

    form.addEventListener("submit", function(e){
        e.preventDefault();

        const est = document.getElementById("sltEstoque").selectedOptions[0].text;

        Swal.fire({
            title: "Excluir Estoque",
            html: "<b>Deseja excluir:</b> " + est,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Excluir",
            cancelButtonText: "Cancelar"
        }).then((result)=>{
            if(result.isConfirmed){
                form.submit();
            }
        });
    });
    </script>

</body>
</html>
