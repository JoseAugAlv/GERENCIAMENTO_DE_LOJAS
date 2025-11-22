<?php
    require_once("../processa/conn.php"); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
     <script src="https://unpkg.com/imask"></script>
     <link rel="stylesheet" href="../css/cadastros.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<h2>Cadastrar Funcionário</h2>
<form action="../processa/processa_funcionario.php" id="formFuncionario" method="post">
    <label for="nome_funcionario">
        Nome:
        <input type="text" name="nome_funcionario" id="nome_funcionario" required>
        <br/>
    </label>
    <label for="id_loja">
        Loja:
        <select name="id_loja" id="id_loja" required>
            <option value="">Selecione a loja</option>
            <?php
                $sql = "SELECT id_loja, nome FROM loja ORDER BY nome";
                $resultado = mysqli_query($conexao, $sql);

                if ($resultado) {
                    while ($loja = mysqli_fetch_assoc($resultado)) {
                        echo '<option value="' . $loja['id_loja'] . '">' . $loja['nome'] . '</option>';
                    }
                } else {
                    echo '<option value="">Erro ao carregar lojas</option>';
                }

            ?>
        </select>
        <br/>
    </label>
    <label for="id_cargo">
        Cargo:
        <select name="id_cargo" id="id_cargo" required>
            <option value="" >Selecione o cargo</option>
            <?php
                $sql = "SELECT id_cargo, nome FROM cargo ORDER BY nome";
                $resultado = mysqli_query($conexao, $sql);

                if ($resultado) {
                    while ($cargo = mysqli_fetch_assoc($resultado)) {
                        echo '<option value="' . $cargo['id_cargo'] . '">' . $cargo['nome'] . '</option>';
                    }
                } else {
                    echo '<option value="">Erro ao carregar cargos</option>';
                }
            ?>
        </select>
        <br/>
    </label>

    <label for="telefone_funcionario" >
        Telefone:
        <input type="text" name="telefone_funcionario" id="telefone_funcionario" placeholder="(00) 00000-0000" required>
        <br/>
    </label>

    <label for="login_funcionario">
        Login:
        <input type="text" name="login_funcionario" id="login_funcionario" required>
        <br/>
    </label>

    <label for="senha_funcionario">
        Senha:
        <input type="text" name="senha_funcionario" id="senha_funcionario" required>
        <br/>
    </label>

    <label for="hora_entrada">
        Hora Entrada:
        <input type="time" name="hora_entrada" id="hora_entrada" required>
        <br/>
    </label>

    <label for="hora_saida">
        Hora Saída:
        <input type="time" name="hora_saida" id="hora_saida" required>
        <br/>
    </label>
    <label for="obs_funcionario">
        Observação:
        <input type="text" name="obs_funcionario" id="obs_funcionario">
        <br/>
    </label>

    <input type="submit" value="Cadastrar Funcionário">
</form>
    <script>

        //mascaras
        const telefone = document.getElementById('telefone_funcionario');
        IMask(telefone, {
        mask: '(00) 00000-0000'
        });

         // SweetAlert2 - confirmação antes de enviar o formulário
        const form = document.getElementById('formFuncionario');
        form.addEventListener('submit', function(e){
            e.preventDefault(); // impede envio imediato

            // Captura os dados do formulário
            const nome = document.getElementById('nome_funcionario').value;
            const loja = document.getElementById('id_loja').selectedOptions[0].text;
            const cargo = document.getElementById('id_cargo').selectedOptions[0].text;
            const telefoneVal = document.getElementById('telefone_funcionario').value;
            const login = document.getElementById('login_funcionario').value;
            const horaEntrada = document.getElementById('hora_entrada').value;
            const horaSaida = document.getElementById('hora_saida').value;

            // SweetAlert2 - Mostra os dados para confirmação
            Swal.fire({
                title: 'Confirme os dados', // Título do alert
                html: `
                    <b>Nome:</b> ${nome}<br>
                    <b>Loja:</b> ${loja}<br>
                    <b>Cargo:</b> ${cargo}<br>
                    <b>Telefone:</b> ${telefoneVal}<br>
                    <b>Login:</b> ${login}<br>
                    <b>Hora Entrada:</b> ${horaEntrada}<br>
                    <b>Hora Saída:</b> ${horaSaida}
                `,
                icon: 'question', // tipo de ícone
                showCancelButton: true, // botão cancelar
                confirmButtonText: 'Cadastrar', // texto do botão de confirmação
                cancelButtonText: 'Cancelar' // texto do botão de cancelar
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // envia formulário se confirmado
                }
            });
        });
    </script>
</body>
</html>
<?php
    mysqli_close($conexao);
?>
