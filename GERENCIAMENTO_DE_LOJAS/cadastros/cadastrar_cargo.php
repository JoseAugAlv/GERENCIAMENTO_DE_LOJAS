<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cargo</title>
     <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/cadastros.css">
</head>
<body>
    <h2>Cadastrar Cargo</h2>
    <form id="Cargo" action="../processa/processa_cargo.php" method="post">
        <label for="nome_cargo">
            Nome do Cargo:
            <input type="text" name="nome_cargo" id="nome_cargo" required>
        </label>
        <br><br>

        <label for="descricao_cargo">
            Descrição:
            <input type="text" name="descricao_cargo" id="descricao_cargo">
        </label>
        <br><br>

        <h3>Permissões:</h3>
        <?php
            // Conexão com o banco
            require_once("../processa/conn.php");

            // Busca todas as permissões
            $sql = "SELECT id_permicao, nome FROM permicao ORDER BY nome";
            $resultado = mysqli_query($conexao, $sql);

            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo '<label>';
                    echo '<input type="checkbox" id="permicoes_cargo" name="permicoes[]" value="' . $linha['id_permicao'] . ' required"> ' . $linha['nome'];
                    echo '</label><br>';
                }
            } else {
                echo '<p>Nenhuma permissão cadastrada.</p>';
            }

            mysqli_close($conexao);
        ?>

        <br>
        <input type="submit" value="Cadastrar Cargo">
    </form>

    <script>
        const form = document.getElementById('formCargo');
        form.addEventListener('submit', function(e){
            e.preventDefault(); // impede envio imediato

            // Captura os dados do formulário
            const nome = document.getElementById('nome_cargo').value;
            const descricao = document.getElementById('descricao_cargo').value;
            const permicoes = document.getElementById('permicoes_cargo').selectedOptions[0].text;
            

            // SweetAlert2 - Mostra os dados para confirmação
            Swal.fire({
                title: 'Confirme os dados',
                html: `
                    <b>Nome:</b> ${nome}<br>
                    <b>Descrição:</b> ${descricao}<br>
                    <b>Permições:</b> ${permicoes}<br>
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
