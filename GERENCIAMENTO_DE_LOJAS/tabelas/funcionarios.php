<?php
require_once("processa/conn.php");

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

if ($resultado && mysqli_num_rows($resultado) > 0) {

    echo '<div class="table-container">';

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Telefone</th>
            <th>Hora Entrada</th>
            <th>Hora Saída</th>
        </tr>";

    while ($func = mysqli_fetch_assoc($resultado)) {
        echo "<tr>
                <td>{$func['id_funcionario']}</td>
                <td>{$func['nome']}</td>
                <td>{$func['cargo']}</td>
                <td>{$func['telefone']}</td>
                <td>{$func['hora_entrada']}</td>
                <td>{$func['hora_saida']}</td>
            </tr>";
    }

    echo "</table>";
    echo "</div>";

} else {
    echo "Nenhum funcionário encontrado.";
}
?>
