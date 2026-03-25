<?php
require_once '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpeza dos dados
    $nome  = mysqli_real_escape_string($conexao, $_POST['nome_usuario']);
    $email = mysqli_real_escape_string($conexao, $_POST['email_usuario']);
    $senha = $_POST['senha_usuario'];

    // Validação de senha curta
    if (strlen($senha) < 6) {
        header("Location: ../../public/cadastro_usuario.php?erro=dados_invalidos");
        exit;
    }

    // Criptografia
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Query
    $sql = "INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)";
    
    // Preparando a query para evitar SQL Injection
    $stmt = mysqli_prepare($conexao, $sql);
    
    if ($stmt) {
        // OBS do Ricardo ;-; "sss" significa que os 3 parâmetros são Strings
        mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senhaHash);
        
        if (mysqli_stmt_execute($stmt)) {
            // Sucesso!
            header("Location: ../../public/cadastro_usuario.php?sucesso=1");
        } else {
            // Erro de e-mail duplicado ou outro erro de banco
            if (mysqli_errno($conexao) == 1062) {
                header("Location: ../../public/cadastro_usuario.php?erro=email_duplicado");
            } else {
                header("Location: ../../public/cadastro_usuario.php?erro=sistema");
            }
        }
        mysqli_stmt_close($stmt);
    }
    exit;
}