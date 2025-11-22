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
}

.modal-box {
    background: white;
    padding: 20px;
    width: 500px;
    max-height: 80vh;
    overflow-y: auto;
    border-radius: 10px;
    position: relative;
}

.close-btn {
    background: red;
    color: white;
    padding: 6px 12px;
    border: none;
    cursor: pointer;
    position: absolute;
    right: 15px;
    top: 15px;
}

.view-btn {
    padding: 6px 12px;
    background: #3498db;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 6px;
}

.items-list {
    display: none; /* escondido no início */
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
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
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

        // Pega os itens da compra da tabela correta
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
    echo "Nenhuma compra encontrada.";
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
// Seleciona modal e elementos
const modalBg = document.getElementById('modal-bg');
const modalContent = document.getElementById('modal-conteudo');
const closeBtn = document.querySelector('.close-btn');

// Fechar modal
closeBtn.addEventListener('click', () => {
    modalBg.style.display = 'none';
});

// Delegação de eventos para os botões dentro da tabela
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('view-btn')) {
        const itens = e.target.getAttribute('data-itens');
        modalContent.innerHTML = itens;
        modalBg.style.display = 'flex';
    }
});
</script>

</body>
</html>
