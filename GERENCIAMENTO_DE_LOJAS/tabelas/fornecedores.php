<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tabela de Fornecedores</title>

    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>

<?php
require_once("processa/conn.php");

$sql = "SELECT * FROM fornecedor";
$resultado = mysqli_query($conexao, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {

    // CONTAINER RESPONSIVO QUE NÃO ALTERA A TABELA
    echo '<div class="table-container">';

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Telefone</th>
            <th>CNPJ</th>
            <th>Email</th>
            <th>Endereço</th>
            <th>Observação</th>
        </tr>";

    while ($fornecedor = mysqli_fetch_assoc($resultado)) {
        echo "<tr>
                <td>" . $fornecedor['id_fornecedor'] . "</td>
                <td>" . $fornecedor['nome'] . "</td>
                <td>" . $fornecedor['tipo'] . "</td>
                <td>" . $fornecedor['telefone'] . "</td>
                <td>" . $fornecedor['cnpj'] . "</td>
                <td>" . $fornecedor['email'] . "</td>
                <td>" . $fornecedor['endereco'] . "</td>
                <td>" . $fornecedor['obs'] . "</td>
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
