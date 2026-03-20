<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Cine-Crítica</title>
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>
    <div class="form-card">
        <h2>Criar Conta</h2>

        <?php if(isset($_GET['erro'])): ?>
            <div class="alert error">
                <?php 
                    if($_GET['erro'] == 'email_duplicado') echo "Este e-mail já existe!";
                    elseif($_GET['erro'] == 'dados_invalidos') echo "Preencha todos os campos corretamente (Senha mín. 6 caracteres).";
                    else echo "Erro no sistema. Tente mais tarde.";
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['sucesso'])): ?>
            <div class="alert success">Usuário cadastrado com sucesso!</div>
        <?php endif; ?>

        <form action="../modules/cadastro/salvar_usuario.php" method="POST">
            <div class="input-group">
                <label>Nome Completo</label>
                <input type="text" name="nome_usuario" required>
            </div>
            <div class="input-group">
                <label>E-mail</label>
                <input type="email" name="email_usuario" required>
            </div>
            <div class="input-group">
                <label>Senha (mín. 6 caracteres)</label>
                <input type="password" name="senha_usuario" required minlength="6">
            </div>
            <button type="submit">CADASTRAR</button>
        </form>
    </div>
</body>
</html>