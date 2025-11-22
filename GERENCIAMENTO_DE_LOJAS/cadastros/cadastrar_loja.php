<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Loja</title>
    <script src="https://unpkg.com/imask"></script>
    <link rel="stylesheet" href="../css/cadastros.css">
</head>
<body>
    <h2>Cadastrar Loja</h2>
    <form action="../processa/processa_loja.php" method="post">
        <label for="nome_loja">
            Nome da Loja: 
            <input type="text" name="nome_loja" id="nome_loja" requered>
            <br/>
        </label>
        <label for="tipo_loja">
            Tipo da Loja:
            <input type="text" name="tipo_loja" id="tipo_loja" requered>
            <br/>
        </label>
        <label for="telefone_loja">
            Telefone: 
            <input type="text" name="telefone_loja" id="telefone_loja" placeholder="(00) 00000-0000" requered>
            <br/>
        </label>
        <label for="cnpj_loja">
            CNPJ:
            <input type="text" name="cnpj_loja" id="cnpj_loja" placeholder="00.000.000/0000-00" required>
            <br/>
        </label>
        <label for="email_loja">
            Email:
            <input type="email" name="email_loja" id="email_loja" requered>
            <br/>
        </label>
        <label for="endereco_loja">
            Endereço: 
            <input type="text" name="endereco_loja" id="endereco_loja" requered>
            <br/>
        </label>
        <label for="login_loja">
            Login:
            <input type="text" name="login_loja" id="login_loja" requered>
            <br/>
        </label>
        <label for="senha_loja">
            Senha:
            <input type="password" name="senha_loja" id="senha_loja" requered>
            <br/>
        </label>
        <label for="obs_loja">
            Observação: 
            <input type="text" name="obs_loja" id="obs_loja">
            <br/>
        </label>
        <input type="submit" value="Cadastar">
    </form>

    <script>

        //mascaras
        const telefone = document.getElementById('telefone_loja');
        IMask(telefone, {
        mask: '(00) 00000-0000'
        });

        const cnpj = document.getElementById('cnpj_loja');
        IMask(cnpj, {
            mask: '00.000.000/0000-00'
        });
    </script>
</body>
</html>