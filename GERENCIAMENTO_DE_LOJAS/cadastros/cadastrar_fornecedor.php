<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Fornecedor</title>
    <script src="https://unpkg.com/imask"></script>
    <link rel="stylesheet" href="../css/cadastros.css">
</head>
<body>
    <h2>Cadastrar Fornecedor</h2>
    <form action="../processa/processa_fornecedor.php" method="post">
        <label for="nome_fornecedor">
            Nome:
            <input type="text" name="nome_fornecedor" id="nome_fornecedor" required>
            <br/>
        </label>
        <label for="tipo_fornecedor">
            Tipo:
            <input type="text" name="tipo_fornecedor" id="tipo_fornecedor" required>
            <br/>
        </label>
        <label for="telefone_estoque">
            Telefone: 
            <input type="tel" name="telefone_fornecedor" id="telefone_fornecedor" placeholder="(00) 00000-0000" required>
            <br/>
        </label>
        <label for="cnpj_fornecedor">
            CNPJ: 
            <input type="text" name="cnpj_fornecedor" id="cnpj_fornecedor" placeholder="00.000.000/0000-00" required>
            <br/>
        </label>
        <label for="email_fornecedor">
            Email
            <input type="email" name="email_fornecedor" id="email_fornecedor" required>
            <br/>
        </label>
        <label for="endereco_fornecedor">
            Endereço: 
            <input type="text" name="endereco_fornecedor" id="endereco_fornecedor" required>
            <br/>
        </label>
        <label for="obs_fornecedor" required>
            Observação:
            <input type="text" name="obs_fornecedor" id="obs_fornecedor" required>
            <br/>
        </label>
        <input type="submit" value="Cadastar Fornecedor" required>


    </form>
    <script>
        //mascaras
        const telefone = document.getElementById('telefone_fornecedor');
        IMask(telefone, {
        mask: '(00) 00000-0000'
        });

        const cnpj = document.getElementById('cnpj_fornecedor');
        IMask(cnpj, {
            mask: '00.000.000/0000-00'
        });
    </script>
</body>
</html>