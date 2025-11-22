<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("conn.php"); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido');
}

$id_loja = intval($_POST['loja_compra'] ?? ($_SESSION['id_loja'] ?? 0));
$id_funcionario = intval($_POST['funcionario_compra'] ?? 0);
$id_pagamento = intval($_POST['pagamento_compra'] ?? 0);
$nome_cliente = trim($_POST['nome_cliente_compra'] ?? '');
$data_compra = $_POST['data_compra'] ?? date('Y-m-d');
$obs = trim($_POST['obs_compra'] ?? '');
$produtos = $_POST['produto'] ?? [];
$quantidades = $_POST['quantidade'] ?? [];

// Validações mínimas
if ($nome_cliente === '' || $id_loja <= 0 || $id_funcionario <= 0 || $id_pagamento <= 0) {
    exit("<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>Swal.fire({icon:'error', title:'Dados inválidos', text:'Preencha cliente, loja, funcionário e forma de pagamento.'});</script>");
}
if (!is_array($produtos) || count($produtos) === 0 || count($produtos) !== count($quantidades)) {
    exit("<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>Swal.fire({icon:'error', title:'Erro', text:'Produtos ou quantidades inválidas.'});</script>");
}

// Inicia transação
mysqli_begin_transaction($conexao);

try {
    $total = 0.0;

    // Calcula total da compra
    foreach ($produtos as $i => $id_prod) {
        $id_prod = intval($id_prod);
        $qtd = max(0, intval($quantidades[$i]));
        if ($qtd <= 0) continue;

        $res = mysqli_query($conexao, "SELECT preco_venda FROM produto WHERE id_produto = $id_prod");
        $row = mysqli_fetch_assoc($res);
        if (!$row) throw new Exception("Produto ID {$id_prod} não encontrado.");
        $total += $row['preco_venda'] * $qtd;
    }

    // Insere compra
    $stmtC = mysqli_prepare($conexao, "INSERT INTO compra (nome_cliente, id_funcionario, id_loja, id_pagamento, preco_total, dt_compra, obs) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmtC, 'siiidss', $nome_cliente, $id_funcionario, $id_loja, $id_pagamento, $total, $data_compra, $obs);
    if (!mysqli_stmt_execute($stmtC)) throw new Exception(mysqli_stmt_error($stmtC));
    $id_compra = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmtC);

    // Insere itens e atualiza estoque
    foreach ($produtos as $i => $id_prod) {
        $id_prod = intval($id_prod);
        $qtd = max(0, intval($quantidades[$i]));
        if ($qtd <= 0) continue;

        // Pega id_estoque e preco_venda do produto
        $res = mysqli_query($conexao, "SELECT id_estoque, preco_venda FROM produto WHERE id_produto = $id_prod");
        $row = mysqli_fetch_assoc($res);
        if (!$row) throw new Exception("Produto ID {$id_prod} não encontrado.");

        $id_estoque = intval($row['id_estoque']);
        $preco_unit = floatval($row['preco_venda']);

        // Insere item_compra
        $stmtI = mysqli_prepare($conexao, "INSERT INTO item_compra (id_compra, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmtI, 'iiid', $id_compra, $id_prod, $qtd, $preco_unit);
        if (!mysqli_stmt_execute($stmtI)) throw new Exception(mysqli_stmt_error($stmtI));
        mysqli_stmt_close($stmtI);

        // Atualiza estoque_produto (subtrai quantidade vendida)
        $resE = mysqli_query($conexao, "SELECT id_estoque_produto, quantidade FROM estoque_produto WHERE id_estoque = $id_estoque AND id_produto = $id_prod FOR UPDATE");
        if ($rowE = mysqli_fetch_assoc($resE)) {
            $nova_qtd = intval($rowE['quantidade']) - $qtd; // linha corrigida
            if ($nova_qtd < 0) $nova_qtd = 0; // evita estoque negativo
            mysqli_query($conexao, "UPDATE estoque_produto SET quantidade = $nova_qtd WHERE id_estoque_produto = {$rowE['id_estoque_produto']}");
        } else {
            // Se não houver registro de estoque, cria com a quantidade (opcional)
            mysqli_query($conexao, "INSERT INTO estoque_produto (id_estoque, id_produto, quantidade) VALUES ($id_estoque, $id_prod, $qtd)");
        }
    }

    mysqli_commit($conexao);

    $safe_total = number_format($total, 2, ',', '.');
    $safe_cliente = addslashes($nome_cliente);
    echo "
    <!DOCTYPE html>
    <html lang='pt-br'><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
    <body>
    <script>
    Swal.fire({
        title: 'Compra registrada!',
        html: '<b>Cliente:</b> {$safe_cliente}<br><b>Total:</b> R$ {$safe_total}',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        if (window.parent && window.parent.document) {
            try { window.parent.document.getElementById('modalCompras').style.display = 'none'; } catch(e){}
            try { window.parent.location.reload(); } catch(e){}
        }
    });
    </script>
    </body></html>";

    } catch (Exception $e) {
        mysqli_rollback($conexao);
        $msg = addslashes($e->getMessage());
        echo "<!DOCTYPE html>
    <html lang='pt-br'><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
    <body>
    <script>
    Swal.fire({
        title: 'Erro ao processar',
        html: '{$msg}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
    </script>
    </body></html>";
    }
?>
