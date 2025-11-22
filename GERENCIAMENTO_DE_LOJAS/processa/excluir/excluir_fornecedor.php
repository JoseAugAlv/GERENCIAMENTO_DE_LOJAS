<?php
    require_once '../conn.php';
    session_start();

    // Lista fornecedores
    $sql = "SELECT id_fornecedor, nome, telefone FROM fornecedor";
    $resultado = mysqli_query($conexao, $sql);
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Excluir Fornecedor</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="../../css/tabelas_excluir.css">
    </head>
    <body>

<?php
    if ($resultado && mysqli_num_rows($resultado) > 0) {

        echo '<div class="table-container">';
        echo "<table>";
        echo "<tr><th>Nome</th><th>Telefone</th></tr>";

        while ($f = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                    <td>{$f['nome']}</td>
                    <td>{$f['telefone']}</td>
                </tr>";
        }

        echo "</table>";
        echo "</div>";

    } else {
        echo "<p>Nenhum fornecedor encontrado.</p>";
    }
    ?>

    <form id="excluir_fornecedor" method="post">
        <label for="slt_excluir_fornecedor">Selecione o fornecedor:</label>

        <select name="slt_excluir_fornecedor" id="slt_excluir_fornecedor" required>
            <option value="">Selecione o Fornecedor</option>

            <?php
                $sql2 = "SELECT id_fornecedor, nome FROM fornecedor";
                $resultado2 = mysqli_query($conexao, $sql2);

                while ($f2 = mysqli_fetch_assoc($resultado2)) {
                    echo '<option value="'.$f2['id_fornecedor'].'">'.
                        $f2['id_fornecedor'].' - '.$f2['nome'].
                        '</option>';
                }
            ?>
        </select>

        <input type="submit" value="Excluir">
    </form>

    <button type="button" onclick="window.parent.location.reload()">Fechar</button>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['slt_excluir_fornecedor'])) {

            $id_fornecedor = intval($_POST['slt_excluir_fornecedor']);

            // Verificar vínculo com produto
            $check = mysqli_query($conexao,
                "SELECT COUNT(*) AS qt FROM produto WHERE id_fornecedor = $id_fornecedor");

            $row = mysqli_fetch_assoc($check);

            if ($row['qt'] > 0) {
                echo "<script>
                    Swal.fire({
                        title: 'Não é possível excluir',
                        html: 'Este fornecedor possui <b>{$row['qt']} produto(s)</b> vinculado(s).<br>Remova ou altere os produtos antes.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                </script>";
                exit;
            }

            // Buscar nome
            $buscar = mysqli_query($conexao,
                "SELECT nome FROM fornecedor WHERE id_fornecedor = $id_fornecedor");

            $dados = mysqli_fetch_assoc($buscar);
            $nome_fornecedor = addslashes($dados['nome']);

            // Excluir
            mysqli_query($conexao,
                "DELETE FROM fornecedor WHERE id_fornecedor = $id_fornecedor");
            ?>

            <script>
            Swal.fire({
                title: 'Fornecedor Excluído',
                html: '<b>Nome:</b> <?= $nome_fornecedor ?>',
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
    const form = document.getElementById('excluir_fornecedor');

    form.addEventListener('submit', function(e){
        e.preventDefault();

        const fornecedor = document.getElementById('slt_excluir_fornecedor')
                            .selectedOptions[0].text;

        Swal.fire({
            title: 'Excluir Fornecedor',
            html: `<b>Tem certeza que deseja excluir:</b><br>${fornecedor}`,
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
