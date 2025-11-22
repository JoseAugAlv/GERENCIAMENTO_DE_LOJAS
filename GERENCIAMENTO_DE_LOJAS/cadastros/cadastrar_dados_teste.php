<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$db = "gerenciamento_lojas";

$conexao = new mysqli($servidor, $usuario, $senha, $db);
if($conexao->connect_error){
    die("A conexão falhou: " . $conexao->connect_error);
}

// Inserir cargos e permissões
if(isset($_POST['inserir_dados'])){
    $cargos = [
        ['Gerente', 'Supervisiona toda a loja, produtos, funcionários e relatórios'],
        ['Caixa', 'Responsável pelo atendimento e vendas no caixa'],
        ['Estoquista', 'Gerencia e consulta o estoque de produtos'],
        ['Repositor', 'Reabastece produtos no estoque'],
        ['Fiscal', 'Audita e visualiza informações do sistema']
    ];

    foreach($cargos as $cargo){
        $nome = $conexao->real_escape_string($cargo[0]);
        $descricao = $conexao->real_escape_string($cargo[1]);
        $conexao->query("INSERT INTO cargo (nome, descricao) VALUES ('$nome', '$descricao')");
    }

    $permissoes = [
        ['Gerenciar produtos','Adicionar, editar ou remover produtos do estoque'],
        ['Vender produtos','Registrar vendas no caixa'],
        ['Visualizar estoque','Consultar quantidade e localização dos produtos'],
        ['Gerenciar funcionários','Cadastrar, editar ou remover funcionários'],
        ['Gerenciar lojas','Cadastrar e editar informações de lojas'],
        ['Emitir relatórios','Gerar relatórios de vendas e estoque'],
        ['Gerenciar fornecedores','Cadastrar, editar ou remover fornecedores'],
        ['Realizar compras','Registrar compras de produtos'],
        ['Atualizar estoque','Alterar quantidade de produtos no estoque']
    ];

    foreach($permissoes as $perm){
        $nome = $conexao->real_escape_string($perm[0]);
        $descricao = $conexao->real_escape_string($perm[1]);
        $conexao->query("INSERT INTO permicao (nome, descricao) VALUES ('$nome', '$descricao')");
    }

    // Relacionar cargos e permissões
    $gerente_permissoes = range(1,9);
    foreach($gerente_permissoes as $id_perm){
        $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (1, $id_perm)");
    }

    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (2, 2)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (2, 6)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (3, 1)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (3, 3)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (3, 9)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (4, 9)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (5, 3)");
    $conexao->query("INSERT INTO cargo_permicao (id_cargo, id_permicao) VALUES (5, 6)");

    echo "<p>Cargos e permissões inseridos com sucesso!</p>";
}

// Inserir fornecedores, estoque e produtos
if(isset($_POST['inserir_produtos'])){

    // Fornecedores
    $fornecedores = [
        ['Fornecedor A', 'Alimentos', '(11) 11111-1111', '11.111.111/0001-11', 'fornecedorA@email.com', 'Rua A, 100', 'Nenhuma observação'],
        ['Fornecedor B', 'Bebidas', '(22) 22222-2222', '22.222.222/0001-22', 'fornecedorB@email.com', 'Rua B, 200', 'Nenhuma observação']
    ];

    foreach($fornecedores as $f){
        $nome = $conexao->real_escape_string($f[0]);
        $tipo = $conexao->real_escape_string($f[1]);
        $telefone = $f[2];
        $cnpj = $f[3];
        $email = $f[4];
        $endereco = $conexao->real_escape_string($f[5]);
        $obs = $conexao->real_escape_string($f[6]);
        $conexao->query("INSERT INTO fornecedor (nome, tipo, telefone, cnpj, email, endereco, obs) 
                        VALUES ('$nome', '$tipo', '$telefone', '$cnpj', '$email', '$endereco', '$obs')");
    }

    // Loja
    $conexao->query("INSERT INTO loja (nome, tipo, telefone, cnpj, email, endereco, login, senha, obs)
                    VALUES ('Loja Centro', 'Varejo', '(33) 33333-3333', '33.333.333/0001-33', 'lojacentro@email.com', 'Av. Centro, 50', 'loja1', MD5('123456'), 'Loja modelo')");

    // Estoque
    $conexao->query("INSERT INTO estoque (id_loja, identificacao, obs) VALUES (1, 'Estoque Principal', 'Estoque principal da loja')");

    // Produtos
    $produtos = [
        [1, 1, 'Arroz 5kg', 'Alimento', '2025-12-31', 25.50, 5.100, 'Pacote padrão'],
        [1, 1, 'Feijão 1kg', 'Alimento', '2025-06-30', 10.75, 2.150, 'Saco de feijão'],
        [2, 1, 'Refrigerante 2L', 'Bebida', '2025-08-31', 8.90, 1.780, 'Garrafa PET'],
        [2, 1, 'Suco Laranja 1L', 'Bebida', '2025-09-15', 7.50, 1.500, 'Caixa Tetrapak']
    ];

    foreach($produtos as $p){
        $id_fornecedor = $p[0];
        $id_estoque = $p[1];
        $nome = $conexao->real_escape_string($p[2]);
        $tipo = $conexao->real_escape_string($p[3]);
        $dt_validade = $p[4];
        $preco_venda = $p[5];
        $preco_varejo = $p[6];
        $obs = $conexao->real_escape_string($p[7]);

        $conexao->query("INSERT INTO produto (id_fornecedor, id_estoque, nome, tipo, dt_validade, preco_venda, preco_varejo, obs) 
                        VALUES ($id_fornecedor, $id_estoque, '$nome', '$tipo', '$dt_validade', $preco_venda, $preco_varejo, '$obs')");
    }

    echo "<p>Fornecedores, estoque e produtos inseridos com sucesso!</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Popular Banco de Dados</title>
</head>
<body>
    <h2>Popular banco de dados de teste</h2>

    <form method="post">
        <button type="submit" name="inserir_dados">Inserir cargos e permissões</button>
    </form>

    <form method="post">
        <button type="submit" name="inserir_produtos">Inserir produtos e estoque</button>
    </form>
</body>
</html>
