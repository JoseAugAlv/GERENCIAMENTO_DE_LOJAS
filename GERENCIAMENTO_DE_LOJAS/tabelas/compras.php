<?php
require_once(__DIR__ . '/../processa/conn.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Tabela de Compras</title>
<link rel="stylesheet" href="css/tabelas.css">

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 20px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

th, td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background: #3498db;

}

tr:nth-child(even) {
    background: #f9f9f9;
}

.view-btn {
    padding: 6px 12px;
    background: #3498db;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.3s;
}

.view-btn:hover {
    background: #2980b9;
}

/* Modal */
.modal-bg {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-bg.show {
    display: flex;
    opacity: 1;
}

.modal-box {
    background: #fff;
    padding: 20px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    border-radius: 10px;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    transform: translateY(-50px);
    transition: transform 0.3s ease;
}

.modal-bg.show .modal-box {
    transform: translateY(0);
}

.close-btn {
    background: #e74c3c;
    color: white;
    padding: 6px 12px;
    border: none;
    cursor: pointer;
    position: absolute;
    right: 15px;
    top: 15px;
    border-radius: 6px;
}

#modal-conteudo ul {
    list-style: none;
    padding: 0;
}

#modal-conteudo ul li {
    padding: 8px;
    margin-bottom: 4px;
    border-radius: 4px;
}

#modal-conteudo ul li:nth-child(odd) {
    background: #f2f2f2;
}

#modal-conteudo ul li:nth-child(even) {
    background: #e8f4fc;
}
</style>
</head>
<body>

<h2>Compras</h2>

<?php
$sql = "
SELECT
    compra.id_compra,
    compra.nome_cliente,
    funcionario.nome AS funcionario,
    loja.nome AS loja,
    forma_pagamento.tipo AS pagamento,
    compra.preco_total,
    compra.dt_compra,
    compra.obs
FROM compra
INNER JOIN funcionario ON compra.id_funcionario = funcionario.id_funcionario
INNER JOIN loja ON compra.id_loja = loja.id_loja
INNER JOIN forma_pagamento ON compra.id_pagamento = forma_pagamento.id_pagamento
ORDER BY compra.id_compra DESC
";

$resultado = mysqli_query($conexao, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    echo '<div class="table-container">';
    echo "<table border='0' cellpadding='6' cellspacing='0'>";
    echo "
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Funcionário</th>
        <th>Loja</th>
        <th>Pagamento</th>
        <th>Total (R$)</th>
        <th>Data</th>
        <th>Obs</th>
        <th>Itens</th>
    </tr>";

    while ($compra = mysqli_fetch_assoc($resultado)) {
        $idCompra = $compra['id_compra'];

        // Pega os itens da compra
        $sqlItens = "SELECT ic.quantidade, ic.preco_unitario, p.nome, p.marca 
                     FROM item_compra ic
                     INNER JOIN produto p ON ic.id_produto = p.id_produto
                     WHERE ic.id_compra = $idCompra";

        $resItens = mysqli_query($conexao, $sqlItens);

        $itensHTML = "<ul>";
        if ($resItens && mysqli_num_rows($resItens) > 0) {
            while ($item = mysqli_fetch_assoc($resItens)) {
                $total_item = $item['quantidade'] * $item['preco_unitario'];
                $itensHTML .= "<li>{$item['nome']} ({$item['marca']}) - {$item['quantidade']} x R$ ".number_format($item['preco_unitario'],2,",",".")." = R$ ".number_format($total_item,2,",",".")."</li>";
            }
        } else {
            $itensHTML .= "<li>Nenhum item encontrado.</li>";
        }
        $itensHTML .= "</ul>";

        echo "<tr>
            <td>{$compra['id_compra']}</td>
            <td>{$compra['nome_cliente']}</td>
            <td>{$compra['funcionario']}</td>
            <td>{$compra['loja']}</td>
            <td>{$compra['pagamento']}</td>
            <td>" . number_format($compra['preco_total'], 2, ',', '.') . "</td>
            <td>{$compra['dt_compra']}</td>
            <td>{$compra['obs']}</td>
            <td>
                <button class='view-btn' data-itens='" . htmlspecialchars($itensHTML, ENT_QUOTES) . "'>Ver Itens</button>
            </td>
        </tr>";
    }

    echo "</table>";
    echo "</div>";
} else {
    echo "<p style='text-align:center;'>Nenhuma compra encontrada.</p>";
}
?>

<!-- MODAL -->
<div id="modal-bg" class="modal-bg">
    <div class="modal-box">
        <button class="close-btn">Fechar</button>
        <div id="modal-conteudo"></div>
    </div>
</div>

<script>

const modalBg = document.getElementById('modal-bg');
const modalContent = document.getElementById('modal-conteudo');
const closeBtn = document.querySelector('.close-btn');


closeBtn.addEventListener('click', () => {
    modalBg.classList.remove('show');
});


document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('view-btn')) {
        const itens = e.target.getAttribute('data-itens');
        modalContent.innerHTML = itens;
        modalBg.classList.add('show');
    }
});
</script>

</body>
</html>
