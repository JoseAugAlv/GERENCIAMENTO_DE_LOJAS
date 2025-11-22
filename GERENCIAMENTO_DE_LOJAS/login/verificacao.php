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