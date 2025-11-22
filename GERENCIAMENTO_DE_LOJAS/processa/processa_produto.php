<?php
    // Ativa todos os erros para depuração
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $nome_produto = $_POST['nome_produto'];               
    $marca_produto = $_POST['marca_produto'];               
    $fornecedor_produto = $_POST['fornecedor_produto'];  
    $estoque_produto = $_POST['estoque_produto'];        
    $tipo_produto = $_POST['tipo_produto'];              
    $dt_validade_produto = $_POST['dt_validade_produto']; 
    $preco_venda_produto = $_POST['preco_venda_produto']; 
    $preco_varejo_produto = $_POST['preco_varejo_produto']; 
    $qtd_produto = $_POST['qtd_produto'];                
    $obs_produto = $_POST['obs_produto'];                

    if($obs_produto == ""){
        $obs_produto = "Nenhuma";
    }

   
    $preco_venda_produto = str_replace(['R$', ' ', '.'], '', $preco_venda_produto);
    $preco_venda_produto = str_replace(',', '.', $preco_venda_produto);

    $preco_varejo_produto = str_replace(['R$', ' ', '.'], '', $preco_varejo_produto);
    $preco_varejo_produto = str_replace(',', '.', $preco_varejo_produto);

    
    require_once("conn.php");

    $query = "INSERT INTO produto 
    (id_fornecedor, id_estoque, nome, marca, tipo, dt_validade, preco_venda, preco_varejo, obs) VALUES (
        '$fornecedor_produto',
        '$estoque_produto',
        '$nome_produto',
        '$marca_produto',
        '$tipo_produto',
        '$dt_validade_produto',
        '$preco_venda_produto',
        '$preco_varejo_produto',
        '$obs_produto'
    )";

    if(mysqli_query($conexao, $query)) {

        $query = "SELECT id_produto FROM produto 
                  WHERE nome = '$nome_produto' 
                  ORDER BY id_produto DESC LIMIT 1";

        $resultado = mysqli_query($conexao, $query);
        $id_produto = mysqli_fetch_assoc($resultado)['id_produto'];


        $queryEstoque = "INSERT INTO estoque_produto (id_estoque, id_produto, quantidade) VALUES (
            '$estoque_produto',
            '$id_produto',
            '$qtd_produto'
        )";

        mysqli_query($conexao, $queryEstoque);


        
        // 7. BUSCAR NOME DO FORNECEDOR PARA O ALERTA
        
        $sqlFornecedor = "SELECT nome FROM fornecedor WHERE id_fornecedor = $fornecedor_produto";
        $resFornecedor = mysqli_query($conexao, $sqlFornecedor);
        $nome_fornecedor = mysqli_fetch_assoc($resFornecedor)['nome'];

        // 8. MENSAGEM DE SUCESSO USANDO SWEETALERT
    
        echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Sucesso</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                title: 'Produto cadastrado!',
                html: '<b>Nome:</b> ".addslashes($nome_produto)."<br><b>Fornecedor:</b> ".addslashes($nome_fornecedor)."',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if(result.isConfirmed){
                    window.parent.document.getElementById(\"modalProduto\").style.display = \"none\";
                    window.parent.location.reload();
                }
            });
        </script>
        </body>
        </html>";
    }

    mysqli_close($conexao);
?>
