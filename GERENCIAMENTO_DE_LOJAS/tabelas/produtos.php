<?php
$id_loja = $_SESSION['id_loja'];

$sql2 = "
    SELECT 
        produto.id_produto,
        produto.nome AS nome_produto,
        produto.marca,
        produto.dt_validade,
        produto.preco_varejo,
        produto.preco_venda,
        estoque.id_estoque,
        fornecedor.nome AS nome_fornecedor,
        ep.quantidade AS qtd_estoque
    FROM produto
    INNER JOIN estoque ON produto.id_estoque = estoque.id_estoque
    INNER JOIN fornecedor ON produto.id_fornecedor = fornecedor.id_fornecedor
    INNER JOIN estoque_produto ep ON ep.id_produto = produto.id_produto AND ep.id_estoque = estoque.id_estoque
    WHERE estoque.id_loja = $id_loja
    ORDER BY produto.nome
";

$resultado2 = mysqli_query($conexao, $sql2);

if ($resultado2 && mysqli_num_rows($resultado2) > 0) {

    echo '<div class="table-container">';
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Marca</th>
            <th>Fornecedor</th>
            <th>Data de Validade</th>
            <th>Preço de Varejo</th>
            <th>Preço de Venda</th>
            <th>ID do Estoque</th>
            <th>Quantidade em Estoque</th>
          </tr>";

    while ($produto = mysqli_fetch_assoc($resultado2)) {

        $preco_varejo = number_format($produto['preco_varejo'], 2, ',', '.');
        $preco_venda  = number_format($produto['preco_venda'], 2, ',', '.');
        $dt_validade  = date('d/m/Y', strtotime($produto['dt_validade']));

        echo "<tr>
                <td>{$produto['id_produto']}</td>
                <td>{$produto['nome_produto']}</td>
                <td>{$produto['marca']}</td>
                <td>{$produto['nome_fornecedor']}</td>
                <td>{$dt_validade}</td>
                <td>R$ {$preco_varejo}</td>
                <td>R$ {$preco_venda}</td>
                <td>{$produto['id_estoque']}</td>
                <td>{$produto['qtd_estoque']}</td>
            </tr>";
    }

    echo "</table>";
    echo "</div>";

} else {
    echo "Nenhum produto encontrado.";
}
?>
