<?php
session_start();
require_once("../processa/conn.php");

$erro = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $usuario = $_POST['login_usuario_user'];
    $senha = md5($_POST['login_senha_user']);

    $query = "SELECT * FROM funcionario WHERE login = ? AND senha = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

       $linha = $result->fetch_assoc();

       $_SESSION['login_usuario'] = $linha['nome'];
       $_SESSION['cargo_usuario'] = $linha['id_cargo'];

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
    <title>Login Funcionário</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="login-container">
    <h1>Login do Funcionário</h1>

    <?php if ($erro != "") { ?>
        <p class="login-erro"><?php echo $erro; ?></p>
    <?php } ?>

    <form action="" method="post">

        <label for="login_usuario_user">Login:</label>
        <input type="text" name="login_usuario_user" id="login_usuario_user" required>

        <label for="login_senha_user">Senha:</label>
        <input type="password" name="login_senha_user" id="login_senha_user" required>

        <input type="submit" value="Entrar">
    </form>
</div>

</body>
</html>
