<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tabela de Estoques</title>

    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>

<?php
require_once("processa/conn.php");

$sql = "SELECT * FROM estoque";
$resultado = mysqli_query($conexao, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {

    // CONTAINER RESPONSIVO QUE NÃO ALTERA A TABELA
    echo '<div class="table-container">';

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Identificação</th>
            <th>Observação</th>
        </tr>";

    while ($estoque = mysqli_fetch_assoc($resultado)) {
        echo "<tr>
                <td>" . $estoque['id_estoque'] . "</td>
                <td>" . $estoque['identificacao'] . "</td>
                <td>" . $estoque['obs'] . "</td>
            </tr>";
    }

    echo "</table>";
    echo "</div>"; // FECHA O CONTAINER

} else {
    echo "Nenhum fornecedor encontrado.";
}
?>

</body>
</html>
