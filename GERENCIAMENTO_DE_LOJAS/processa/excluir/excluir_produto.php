<?php
    require_once '../conn.php'; 
    session_start();

    
    if(!isset($_SESSION['id_loja'])){
        die("Erro: Loja não identificada.");
    }

    $id_loja = $_SESSION['id_loja'];

    $sql = "
        SELECT 
            produto.id_produto, 
            produto.nome,
            produto.tipo,
            produto.preco_venda,
            produto.dt_validade,
            estoque.identificacao AS estoque_nome
        FROM produto
        INNER JOIN estoque ON estoque.id_estoque = produto.id_estoque
        WHERE estoque.id_loja = $id_loja
        ORDER BY produto.id_produto
    ";

    $resultado = mysqli_query($conexao, $sql);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/tabelas_excluir.css">
</head>
<body>

    <?php
        if ($resultado && mysqli_num_rows($resultado) > 0) {

            echo '<div class="table-container">';
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                </tr>";

            while ($prod = mysqli_fetch_assoc($resultado)) {

                echo "<tr>
                        <td>{$prod['id_produto']}</td>
                        <td>{$prod['nome']}</td>
                        <td>R$ {$prod['preco_venda']}</td>
                    </tr>";
            }

            echo "</table>";
            echo "</div>";

        } else {
            echo "<p>Nenhum produto encontrado para esta loja.</p>";
        }
    ?>



    <form id="excluir_produto" method="post">
        <label for="slt_excluir_produto">Selecione o produto para excluir:</label>

        <select name="slt_excluir_produto" id="slt_excluir_produto" required>
            <option value="">Selecione o Produto</option>

            <?php
                $sql2 = "
                    SELECT produto.id_produto, produto.nome
                    FROM produto
                    INNER JOIN estoque ON estoque.id_estoque = produto.id_estoque
                    WHERE estoque.id_loja = $id_loja
                ";

                $resultado2 = mysqli_query($conexao, $sql2);

                while ($produto = mysqli_fetch_assoc($resultado2)) {
                    echo '<option value="'.$produto['id_produto'].'">'.
                        $produto['id_produto'].' - '.$produto['nome'].
                        '</option>';
                }
            ?>
        </select>

        <input type="submit" value="Excluir">
    </form>

    <button type="button">Fechar</button>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['slt_excluir_produto'])) {

            $id_produto = $_POST['slt_excluir_produto'];

            // Buscar nome para exibir
            $buscar = mysqli_query($conexao, 
                "SELECT nome FROM produto WHERE id_produto = $id_produto"
            );
            $dados = mysqli_fetch_assoc($buscar);
            $nome_produto = addslashes($dados['nome']);

            // EXCLUIR O VÍNCULO DO ESTOQUE
            mysqli_query($conexao, 
                "DELETE FROM estoque_produto WHERE id_produto = $id_produto"
            );

            // EXCLUIR ITEM_COMPRA
            mysqli_query($conexao, 
                "DELETE FROM item_compra WHERE id_produto = $id_produto"
            );

            // EXCLUIR COMPRA SE PRECISAR
            mysqli_query($conexao, 
                "DELETE FROM compra WHERE id_produto = $id_produto"
            );

            // EXCLUIR PRODUTO
            mysqli_query($conexao, 
                "DELETE FROM produto WHERE id_produto = $id_produto"
            );
            ?>

            <script>
            Swal.fire({
                title: 'Produto Excluído',
                html: '<b>Produto:</b> <?= $nome_produto ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if(result.isConfirmed){
                    window.parent.document.getElementById("modalCargo").style.display = "none";
                    window.parent.location.reload();
                }
            });
            </script>

            <?php
            exit;
        }
    }
    ?>


    <script>
        const form = document.getElementById('excluir_produto');

        form.addEventListener('submit', function(e){
            e.preventDefault();

            const produto = document.getElementById('slt_excluir_produto')
                                .selectedOptions[0].text;

            Swal.fire({
                title: 'Excluir Produto',
                html: `
                    <b>Tem certeza que deseja excluir:</b><br>
                    ${produto}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>
