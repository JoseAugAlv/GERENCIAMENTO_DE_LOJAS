<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastar Produto</title>
    <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/cadastros.css">
</head>
<body>
    <h2>Cadastar Produto</h2>
    <form id="formProduto" action="../processa/processa_produto.php" method="post">
        <label for="nome_produto">
            Nome: 
            <input type="text" name="nome_produto" id="nome_produto" required>
            <br/>
        </label>
        <label for="marca_produto">
            Marca: 
            <input type="text" name="marca_produto" id="marca_produto" required>
            <br/>
        </label>
        <label for="fornecedor_produto">
            Fornecedor: 
            <select name="fornecedor_produto" id="fornecedor_produto" required>
                <option value="">Selecione o Fornecedor</option>
                <?php
                    require_once("../processa/conn.php");

                    // Busca todas as lojas
                    $sql = "SELECT id_fornecedor, nome FROM fornecedor ORDER BY nome";
                    $resultado = mysqli_query($conexao, $sql);

                    if ($resultado) {
                        //para cada loja no banco
                        while ($fornecedor = mysqli_fetch_assoc($resultado)) {
                            //cria um selec
                            echo '<option value="' . $fornecedor['id_fornecedor'] . '">' . $fornecedor['nome'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Erro ao carregar fornecedores</option>';
                    }
                ?>
            </select>
            <br/>
        </label>
        <label for="estoque_produto">
            Estoque:
            <select name="estoque_produto" id="estoque_produto" required>
                <option value="">Selecione o Estoque</option>
                <?php
                    require_once("../processa/conn.php");

                    $sql = "SELECT id_estoque, identificacao FROM estoque ORDER BY identificacao";
                    $resultado = mysqli_query($conexao, $sql);

                    if ($resultado && mysqli_num_rows($resultado) > 0) {
                        while ($estoque = mysqli_fetch_assoc($resultado)) {
                            echo '<option value="' . $estoque['id_estoque'] . '">' . $estoque['identificacao'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Nenhum estoque encontrado</option>';
                    }
                ?>
            </select>

            </select>
            <br/>
        </label>
        <label for="tipo_produto">
            Tipo:
            <input type="text" name="tipo_produto" id="tipo_produto" required>
            <br/>
        </label>
        <label for="dt_validade_produto">
            Data de Validade:
            <input type="date" name="dt_validade_produto" id="dt_validade_produto" required>
            <br/>
        </label>
        <label for="preco_venda_produto">
            Preço de Venda:
            <input type="text" name="preco_venda_produto" id="preco_venda_produto" required>
            <br/>
        </label>
        <label for="preco_varejo_produto">
            Preço de Varejo: 
            <input type="text" name="preco_varejo_produto" id="preco_varejo_produto" required>
            <br/>
        </label>
        <label for="qtd_produto">
            Quantidade:
            <input type="number" name="qtd_produto" id="qtd_produto" required>
            <br/>
        </label>
        <label for="obs_produto">
            Observação:
            <input type="text" name="obs_produto" id="obs_produto" >
            <br/>
        </label>
        <input type="submit" value="Cadastrar Produto">
    </form>

    <script>
        const maskOptions = {
            mask: 'R$ num',
            blocks: {
            num: {
                mask: Number,
                thousandsSeparator: '.',
                radix: ',',
                scale: 2,
                signed: false
            }
            }
        };

        IMask(document.getElementById('preco_venda_produto'), maskOptions);
        IMask(document.getElementById('preco_varejo_produto'), maskOptions);

        const form = document.getElementById('formProduto');
        form.addEventListener('submit', function(e){
            e.preventDefault(); // impede envio imediato

            // Captura os dados do formulário
            const nome = document.getElementById('nome_produto').value;
            const fornecedor = document.getElementById('fornecedor_produto').selectedOptions[0].text;
            const estoque = document.getElementById('estoque_produto').selectedOptions[0].text;
            const tipo = document.getElementById('tipo_produto').value;
            const validade = document.getElementById('dt_validade_produto').value;
            const precoVenda = document.getElementById('preco_venda_produto').value;
            const precoVarejo = document.getElementById('preco_varejo_produto').value;
            const quantidade = document.getElementById('qtd_produto').value;
            const obs = document.getElementById('obs_produto').value;

            // SweetAlert2 - Mostra os dados para confirmação
            Swal.fire({
                title: 'Confirme os dados',
                html: `
                    <b>Nome:</b> ${nome}<br>
                    <b>Fornecedor:</b> ${fornecedor}<br>
                    <b>Estoque:</b> ${estoque}<br>
                    <b>Tipo:</b> ${tipo}<br>
                    <b>Validade:</b> ${validade}<br>
                    <b>Preço de Venda:</b> ${precoVenda}<br>
                    <b>Preço de Varejo:</b> ${precoVarejo}<br>
                    <b>Quantidade:</b> ${quantidade}<br>
                    <b>Observação:</b> ${obs}
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Cadastrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // envia formulário se confirmado
                }
            });
        });
            </script>

    
</body>
</html>
<?php
    mysqli_close($conexao);
?>
