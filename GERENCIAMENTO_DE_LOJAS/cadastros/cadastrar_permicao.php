<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar permição</title>
    <link rel="stylesheet" href="../css/cadastros.css">
</head>
<body>
    <h2>Cadastrar Permição</h2>
    <form action="../processa/processa_permicao.php" method="post">
        <label for="nome_permicao">
            Nome: 
            <input type="text" name="nome_permicao" id="nome_permicao" required>
            <br/>
        </label>
        <label for="descricao_permicao">
            Descrição:
            <input type="text" name="descricao_permicao" id="descricao_permicao" required>
            <br/>
        </label>
        <input type="submit" value="Cadastrar">
    </form>
    
</body>
</html>