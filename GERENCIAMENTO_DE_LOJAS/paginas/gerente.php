<?php

    if (!isset($_SESSION['login_loja']) || $_SESSION['login_loja'] == "") {
        header("Location: ../login/login_loja.php");
        exit;
    }

    if (!isset($_SESSION['login_usuario']) || $_SESSION['login_usuario'] == "") {
        header("Location: ../login/login_usuario.php");
        exit;
    }

    require_once(__DIR__ . "/../processa/conn.php");

    $id_loja = $_SESSION['id_loja'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Gerente</title>
    <link rel="stylesheet" href="css/paginas.css">
    <link rel="stylesheet" href="css/modal.css">
</head>
<body>

<h1>Olá gerente <?php echo $_SESSION['login_usuario']; ?></h1>


<!-- ##################      FUNCIONARIOS      ##################-->

<div class="view" id="view_funcionarios">
    <h3>Seus Funcionários:</h3>
    <?php include 'tabelas/funcionarios.php'; ?>
    <br/>
    <button class="btn_cadastro" onclick="abrirModal('modalFuncionario')">Cadastrar</button>
    <button class="btn_excluir" onclick="abrirModal('modalExcluirFuncionario')">Excluir</button>
</div>

<div class="modal" id="modalFuncionario">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalFuncionario')">X</span>
        <iframe src="cadastros/cadastrar_funcionario.php"></iframe>
    </div>
</div>

<div class="modal" id="modalExcluirFuncionario">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalExcluirFuncionario')">X</span>
        <iframe src="processa/excluir/excluir_funcionario.php"></iframe>
    </div>
</div>


<!-- ##################      CARGOS      ##################-->

<div class="view" id="view_cargos">
    <h3>Cargos:</h3>
    <?php include 'tabelas/cargos.php'; ?>
    <br/>
    <button class="btn_cadastro" onclick="abrirModal('modalCargo')">Cadastrar</button>
    <button class="btn_excluir" onclick="abrirModal('modalExcluirCargo')">Excluir</button>
</div>

<div class="modal" id="modalCargo">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalCargo')">X</span>
        <iframe src="cadastros/cadastrar_cargo.php"></iframe>
    </div>
</div>

<div class="modal" id="modalExcluirCargo">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalExcluirCargo')">X</span>
        <iframe src="processa/excluir/excluir_cargo.php"></iframe>
    </div>
</div>


<!-- ##################      FORNECEDORES      ##################-->

<div class="view" id="view_fornecedores">
    <h3>Fornecedores:</h3>
    <?php include 'tabelas/fornecedores.php'; ?>
    <br/>
    <button class="btn_cadastro" onclick="abrirModal('modalFornecedor')">Cadastrar</button>
    <button class="btn_excluir" onclick="abrirModal('modalExcluirFornecedor')">Excluir</button>
</div>

<div class="modal" id="modalFornecedor">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalFornecedor')">X</span>
        <iframe src="cadastros/cadastrar_fornecedor.php"></iframe>
    </div>
</div>

<div class="modal" id="modalExcluirFornecedor">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalExcluirFornecedor')">X</span>
        <iframe src="processa/excluir/excluir_fornecedor.php"></iframe>
    </div>
</div>


<!-- ##################      ESTOQUES      ##################-->

<div class="view" id="view_estoque">
    <h3>Estoques:</h3>
    <?php include 'tabelas/estoques.php'; ?>
    <br/>
    <button class="btn_cadastro" onclick="abrirModal('modalEstoque')">Cadastrar</button>
    <button class="btn_excluir" onclick="abrirModal('modalExcluirEstoque')">Excluir</button>
</div>

<div class="modal" id="modalEstoque">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalEstoque')">X</span>
        <iframe src="cadastros/cadastrar_estoque.php"></iframe>
    </div>
</div>

<div class="modal" id="modalExcluirEstoque">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalExcluirEstoque')">X</span>
        <iframe src="processa/excluir/excluir_estoque.php"></iframe>
    </div>
</div>


<!-- ##################      PRODUTOS      ##################-->

<div class="view" id="view_produtos">
    <h3>Produtos:</h3>
    <?php include 'tabelas/produtos.php'; ?>
    <br/>
    <button class="btn_cadastro" onclick="abrirModal('modalProduto')">Cadastrar</button>
    <button class="btn_excluir" onclick="abrirModal('modalExcluirProduto')">Excluir</button>
</div>

<div class="modal" id="modalProduto">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalProduto')">X</span>
        <iframe src="cadastros/cadastrar_produto.php"></iframe>
    </div>
</div>

<div class="modal" id="modalExcluirProduto">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalExcluirProduto')">X</span>
        <iframe src="processa/excluir/excluir_produto.php"></iframe>
    </div>
</div>

<!-- ##################      Compras      ##################-->

<div class="view" id="view_Compras">
    <h3>Compras:</h3>
    <?php include 'tabelas/compras.php'; ?>
    <br/>
</div>



<!-- ##################  SCRIPTS  ##################-->

<script>
    function abrirModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function fecharModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>

</body>
</html>
