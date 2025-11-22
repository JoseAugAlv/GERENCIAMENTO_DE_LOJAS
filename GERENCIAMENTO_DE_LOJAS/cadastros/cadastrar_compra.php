<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../processa/conn.php');

$id_loja = $_SESSION['id_loja'] ?? 1;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cadastrar compra</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="../css/cadastros.css">
<style>
.produto-item { margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
</style>
</head>
<body>
<h2>Cadastrar Compra</h2>

<form id="form_compra_completa" action="../processa/processa_compra.php" method="post">

    <h3>Dados da Compra</h3>

    <label>Nome do Cliente:
        <input type="text" name="nome_cliente_compra" required>
    </label>

    <label>Forma de Pagamento:
        <select name="pagamento_compra" required>
            <option value="">Selecione</option>
            <?php
            $sql = "SELECT id_pagamento, tipo FROM forma_pagamento;";
            $res = mysqli_query($conexao, $sql);
            while($row = mysqli_fetch_assoc($res)){
                echo "<option value='{$row['id_pagamento']}'>{$row['tipo']}</option>";
            }
            ?>
        </select>
    </label>

    <label>Funcionário:
        <select name="funcionario_compra" required>
            <option value="">Selecione</option>
            <?php
            $sql = "SELECT id_funcionario, nome FROM funcionario 
                    WHERE id_loja = $id_loja AND id_cargo = 2";
            $res = mysqli_query($conexao, $sql);
            while($row = mysqli_fetch_assoc($res)){
                echo "<option value='{$row['id_funcionario']}'>{$row['nome']}</option>";
            }
            ?>
        </select>
    </label>

    <label>Loja:
        <select name="loja_compra" required>
            <option value="">Selecione</option>
            <?php
            $sql = "SELECT id_loja, nome FROM loja WHERE id_loja = $id_loja";
            $res = mysqli_query($conexao, $sql);
            while($row = mysqli_fetch_assoc($res)){
                echo "<option value='{$row['id_loja']}'>{$row['nome']}</option>";
            }
            ?>
        </select>
    </label>

    <label>Data da Compra:
        <input type="date" name="data_compra" value="<?php echo date('Y-m-d'); ?>">
    </label>

    <label>Observação:
        <input type="text" name="obs_compra">
    </label>

    <br><br>

    <h3>Produtos da Compra</h3>

    <div id="produtos-container">
        <div class="produto-item">
            <label>Produto:
                <select name="produto[]" class="produto-select" required>
                    <option value="">Selecione</option>
                    <?php
                    $sql_prod = "SELECT p.id_produto, p.id_estoque, p.nome, p.marca, p.preco_venda
                                 FROM produto p
                                 WHERE p.id_estoque IN (SELECT id_estoque FROM estoque WHERE id_loja = $id_loja)";
                    $res_prod = mysqli_query($conexao, $sql_prod);
                    while($p = mysqli_fetch_assoc($res_prod)){
                        $display = "{$p['nome']} | {$p['marca']} | R$ ".number_format($p['preco_venda'],2,',','.');
                        echo "<option value='{$p['id_produto']}' 
                                     data-preco='{$p['preco_venda']}'
                                     data-estoque='{$p['id_estoque']}'>$display</option>";
                    }
                    ?>
                </select>
            </label>

            <label>Quantidade:
                <input type="number" name="quantidade[]" class="quantidade" min="1" value="1" required>
            </label>

            <label>Valor Unitário:
                <input type="number" class="valor-unit" step="0.01" readonly>
            </label>
        </div>
    </div>

    <button type="button" id="add-produto">Adicionar outro produto</button>

    <br><br>

    <input type="submit" value="Finalizar Compra">

</form>

<script>
function atualizarValor(select){
    const valorInput = select.closest('.produto-item').querySelector('.valor-unit');
    valorInput.value = select.selectedOptions[0].dataset.preco || '';
}

document.querySelectorAll('.produto-select').forEach(sel=>{
    sel.addEventListener('change', function(){ atualizarValor(this); });
});

document.getElementById('add-produto').addEventListener('click', function(){
    const container = document.getElementById('produtos-container');
    const first = container.querySelector('.produto-item');
    const clone = first.cloneNode(true);

    clone.querySelector('.produto-select').value = '';
    clone.querySelector('.quantidade').value = 1;
    clone.querySelector('.valor-unit').value = '';

    clone.querySelector('.produto-select').addEventListener('change', function(){
        atualizarValor(this);
    });

    container.appendChild(clone);
});

document.getElementById('form_compra_completa').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;

    let html = "";
    let total = 0;

    const itens = document.querySelectorAll('.produto-item');

    itens.forEach((item, i)=>{
        let nome = item.querySelector('.produto-select').selectedOptions[0].text;
        let qtd = parseFloat(item.querySelector('.quantidade').value) || 0;
        let preco = parseFloat(item.querySelector('.valor-unit').value) || 0;
        let subtotal = qtd * preco;
        total += subtotal;

        html += `<b>${i+1}. ${nome}</b> — Qtd: ${qtd} — R$ ${subtotal.toFixed(2)}<br>`;
    });

    html += `<br><b>Total Geral:</b> R$ ${total.toFixed(2)}`;

    Swal.fire({
        title: "Finalizar Compra?",
        html: html,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then(res=>{
        if(res.isConfirmed){
            form.submit();
        }
    });

});
</script>

<?php mysqli_close($conexao); ?>
</body>
</html>
