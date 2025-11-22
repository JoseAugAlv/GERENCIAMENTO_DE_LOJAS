<?php
session_start();
require_once("../processa/conn.php");

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['login_loja'];
    $senha = md5($_POST['senha_loja']);

    $query = "SELECT * FROM loja WHERE login = ? AND senha = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $linha = $result->fetch_assoc();
        $_SESSION['login_loja'] = $linha['nome'];
        $_SESSION['id_loja'] = $linha['id_loja'];
        header("Location: ../index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Loja</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="login-container">
    <h1>Login da Loja</h1>

    <?php if ($erro != "") { ?>
        <p class="login-erro"><?php echo $erro; ?></p>
    <?php } ?>

    <form action="" method="post">
        <label for="login_loja">Login:</label>
        <input type="text" name="login_loja" id="login_loja" required>

        <label for="senha_loja">Senha:</label>
        <input type="password" name="senha_loja" id="senha_loja" required>

        <input type="submit" value="Entrar">
    </form>
</div>

</body>
</html>
