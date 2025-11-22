<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Estoque</title>
    <link rel="stylesheet" href="../css/cadastros.css">
    <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h2>Cadastrar Estoque</h2>

    <form id="formEstoque" action="../processa/processa_estoque.php" method="post">
        <label for="identificacao_estoque">
            Identificação: 
            <input type="text" name="identificacao_estoque" id="identificacao_estoque" required>
            <br/>
        </label>
        <label for="loja_estoque">
            Loja:
            <select name="loja_estoque" id="loja_estoque" required>
                <option value="">Selecione a loja</option>
                <?php
                    session_start();

                    $id_loja = $_SESSION['id_loja'];
                    
                    require_once("../processa/conn.php");

                    // Busca todas as lojas
                    $sql = "SELECT id_loja, nome FROM loja WHERE id_loja = $id_loja";
                    $resultado = mysqli_query($conexao, $sql);

                    if ($resultado) {
                        //para cada loja no banco
                        while ($loja = mysqli_fetch_assoc($resultado)) {
                            //cria um selec
                            echo '<option value="' .  $loja['id_loja'] . '">' . $loja['nome'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Erro ao carregar lojas</option>';
                    }

                    mysqli_close($conexao);
                ?>
            </select>
            <br/>
        </label>
        <label for="obs_estoque">
            Observação:
            <input type="text" name="obs_estoque" id="obs_estoque">
            <br/>
        </label>
        <input type="submit" value="Cadastrar Estoque">
    </form>

    <script>
        const form = document.getElementById('formEstoque');
        form.addEventListener('submit', function(e){
            e.preventDefault(); // impede envio imediato

            // Captura os dados do formulário
            const identificacao = document.getElementById('identificacao_estoque').value;
            const Loja = document.getElementById('loja_estoque').selectedOptions[0].text;
            const obs = document.getElementById('obs_estoque').value;
            

            // SweetAlert2 - Mostra os dados para confirmação
            Swal.fire({
                title: 'Confirme os dados',
                html: `
                    <b>Nome:</b> ${identificacao}<br>
                    <b>Loja:</b> ${Loja}<br>
                    <b>Observação:</b> ${obs}<br>
                    
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
