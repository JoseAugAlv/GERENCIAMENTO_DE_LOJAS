<?php
require_once '../conn.php';
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Cargo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../css/tabelas_excluir.css">
</head>
<body>

    <?php
        // LISTA OS CARGOS NA TABELA
        $sql = "SELECT id_cargo, nome FROM cargo ORDER BY id_cargo";
        $res = mysqli_query($conexao, $sql);

        if ($res && mysqli_num_rows($res) > 0) {

            echo '<div class="table-container">';
            echo '<table>';
            echo '<tr><th>ID</th><th>Nome</th></tr>';

            while ($cargo = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$cargo['id_cargo']}</td>
                        <td>{$cargo['nome']}</td>
                    </tr>";
            }

            echo '</table>';
            echo '</div>';

        } else {
            echo "Nenhum cargo encontrado.";
        }
    ?>

    <form id="formExcluir" method="post">
        <label for="sltCargo">Selecione o cargo para excluir:</label>

        <select name="sltCargo" id="sltCargo" required>
            <option value="">Selecione o cargo</option>

            <?php
            $res2 = mysqli_query($conexao, "SELECT id_cargo, nome FROM cargo");

            while ($c = mysqli_fetch_assoc($res2)) {
                echo '<option value="'.$c['id_cargo'].'">'.$c['id_cargo'].' - '.$c['nome'].'</option>';
            }
            ?>
        </select>

        <input type="submit" value="Excluir">
    </form>

    <button type="button">Fechar</button>

    <?php
    // PROCESSAR EXCLUSÃO
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['sltCargo'])) {

            $id_cargo = intval($_POST['sltCargo']);

            // Dependência 1: existe funcionário usando o cargo?
            $check1 = mysqli_query($conexao, 
                "SELECT COUNT(*) AS qt FROM funcionario WHERE id_cargo = $id_cargo"
            );
            $row1 = mysqli_fetch_assoc($check1);

            if ($row1['qt'] > 0) {
                echo "<script>
                    Swal.fire({
                        title: 'Não é possível excluir',
                        html: 'Existem <b>{$row1['qt']}</b> funcionário(s) usando este cargo.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                </script>";
                exit;
            }

            // Buscar nome para exibir
            $buscar = mysqli_query($conexao, 
                "SELECT nome FROM cargo WHERE id_cargo = $id_cargo"
            );
            $nomeCargo = mysqli_fetch_assoc($buscar)['nome'];
            $nomeCargo = addslashes($nomeCargo);

            // Dependência 2: apagar permissões do cargo
            mysqli_query($conexao, "DELETE FROM cargo_permicao WHERE id_cargo = $id_cargo");

            // Excluir cargo
            mysqli_query($conexao, "DELETE FROM cargo WHERE id_cargo = $id_cargo");
            ?>

            <script>
            Swal.fire({
                title: 'Cargo excluído',
                html: '<b>Cargo:</b> <?= $nomeCargo ?>',
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

            const cargo = document.getElementById("sltCargo").selectedOptions[0].text;

            Swal.fire({
                title: "Excluir Cargo",
                html: "<b>Deseja excluir:</b> " + cargo,
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
