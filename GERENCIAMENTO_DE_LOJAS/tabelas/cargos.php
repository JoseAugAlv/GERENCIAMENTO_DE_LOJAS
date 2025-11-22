<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tabela de Cargos</title>

    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>

<?php
require_once("processa/conn.php");

$sql = "
    SELECT 
        cargo.id_cargo,
        cargo.nome,
        CASE 
            WHEN COUNT(DISTINCT cargo_permicao.id_permicao) = (SELECT COUNT(*) FROM permicao) 
            THEN 'Todas as Permições' 
            ELSE GROUP_CONCAT(DISTINCT permicao.nome SEPARATOR ', ') 
        END
    FROM cargo
    LEFT JOIN cargo_permicao ON cargo.id_cargo = cargo_permicao.id_cargo
    LEFT JOIN permicao ON cargo_permicao.id_permicao = permicao.id_permicao
    GROUP BY cargo.id_cargo, cargo.nome;

    ";
$resultado = mysqli_query($conexao, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {

    echo '<div class="table-container">';

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>cargo</th>
            <th>Permicao</th>
        </tr>";

    while ($cargo = mysqli_fetch_row($resultado)) {
        echo "<tr>
                <td>" . $cargo[0] . "</td>
                <td>" . $cargo[1] . "</td>
                <td>" . $cargo[2] . "</td>
            </tr>";
    }

    echo "</table>";
    echo "</div>"; // FECHA O CONTAINER

} else {
    echo "Nenhum Cargo encontrado.";
}
?>

</body>
</html>
