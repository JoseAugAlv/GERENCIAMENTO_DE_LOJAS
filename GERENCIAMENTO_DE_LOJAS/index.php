<?php
    session_start();


    if (!isset($_SESSION['login_loja']) || $_SESSION['login_loja'] == "") {
        header("Location: login/login_loja.php");
        exit;
    }


    if (!isset($_SESSION['login_usuario']) || $_SESSION['login_usuario'] == "") {
        header("Location: login/login_usuario.php");
        exit;
    }
    $cargo = $_SESSION['cargo_usuario'];
    

?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Lojas</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/tabelas.css">
</head>
<body>
    <div class="index-container">
    <h2>Sistema de Gerenciamento de Lojas</h2>

    <p class="index-info">
        Olá <?php echo $_SESSION['login_usuario']; ?>, 
        você entrou na página da loja 
        <?php echo $_SESSION['login_loja']; ?>
    </p>

    <hr>

    <div class="index-conteudo">
        <?php
            $pagina = $cargo;

            switch ($pagina) {
                case 1:
                    include 'paginas/gerente.php';
                    break;
                case 2:
                    include 'paginas/caixa.php';
                    break;  
                default:
                    echo "Erro";
            }
        ?>
    </div>

    <a class="index-sair" href="login/sair.php">Sair</a>
</div>
</body>
</html>