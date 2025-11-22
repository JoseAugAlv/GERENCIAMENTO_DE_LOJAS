<?php
    require_once '../conn.php'; 
    session_start();

    $id_loja = $_SESSION['id_loja'];

    $sql = "
        SELECT funcionario.id_funcionario, funcionario.nome, cargo.nome AS cargo,
            funcionario.telefone, funcionario.hora_entrada, funcionario.hora_saida
        FROM funcionario
        INNER JOIN cargo ON funcionario.id_cargo = cargo.id_cargo
        WHERE funcionario.id_loja = $id_loja
        ORDER BY funcionario.id_funcionario
    ";

    $resultado = mysqli_query($conexao, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir funcionário</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/tabelas_excluir.css">
</head>
<body>

    <?php
        if ($resultado && mysqli_num_rows($resultado) > 0) {

            echo '<div class="table-container">';
            echo "<table>";
            echo "<tr><th>ID</th><th>Nome</th></tr>";

            while ($func = mysqli_fetch_assoc($resultado)) {
                echo "<tr>
                        <td>{$func['id_funcionario']}</td>
                        <td>{$func['nome']}</td>
                    </tr>";
            }

            echo "</table>";
            echo "</div>";

        } else {
            echo "Nenhum funcionário encontrado.";
        }
    ?>

    <form id="excluir_funcionario" action="" method="post">
        <label for="slt_excluir_funcionario">Selecione quem deseja excluir:</label>
        <select name="slt_excluir_funcionario" id="slt_excluir_funcionario" required>
            <option value="">Selecione o Funcionário</option>
            <?php
                require_once("../conn.php");
                $sql2 = "SELECT id_funcionario, nome FROM funcionario WHERE id_loja = $id_loja";
                $resultado2 = mysqli_query($conexao, $sql2);

                while ($funcionario = mysqli_fetch_assoc($resultado2)) {
                    echo '<option value="'.$funcionario['id_funcionario'].'">'.
                        $funcionario['id_funcionario'].' - '.$funcionario['nome'].
                        '</option>';
                }
            ?>
        </select>
        <input type="submit" value="Excluir">
    </form>

    <button type="button">Fechar</button>

    <?php
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!empty($_POST['slt_excluir_funcionario'])) {

                $id_funcionario = $_POST['slt_excluir_funcionario'];

                $buscar = mysqli_query($conexao, "SELECT nome FROM funcionario WHERE id_funcionario = $id_funcionario");
                $dados = mysqli_fetch_assoc($buscar);
                $nome_funcionario = addslashes($dados['nome']);

                $query = "DELETE FROM funcionario WHERE id_funcionario = $id_funcionario";
                mysqli_query($conexao, $query);
                ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                Swal.fire({
                    title: 'Funcionário Excluído',
                    html: '<b>Nome:</b> <?= $nome_funcionario ?>',
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
        const form = document.getElementById('excluir_funcionario');
        form.addEventListener('submit', function(e){
            e.preventDefault(); // impede envio imediato

            // Captura os dados do formulário
            const funcionario = document.getElementById('slt_excluir_funcionario').selectedOptions[0].text;
            

            // SweetAlert2 - Mostra os dados para confirmação
            Swal.fire({
                title: 'Excluir',
                html: `
                    <b>Deseja mesmo excluir:</b> ${funcionario}<br>
                    
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // envia formulário se confirmado
                }
            });
        });
    </script>

</body>
</html>
