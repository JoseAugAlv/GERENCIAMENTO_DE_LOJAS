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

<h1>Olá caixa <?php echo $_SESSION['login_usuario']; ?></h1>


<!-- ##################      Compras      ##################-->

<div class="view" id="view_Compras">
    <h3>Compras:</h3>
    <?php include 'tabelas/compras.php'; ?>
    <br/>
    <button class="btn_cadastro" onclick="abrirModal('modalCompras')">Cadastrar</button>
</div>

<div class="modal" id="modalCompras">
    <div class="modal-conteudo">
        <span class="fechar-modal" onclick="fecharModal('modalCompras')">X</span>
        <iframe src="cadastros/cadastrar_compra.php"></iframe>
    </div>
</div>

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
