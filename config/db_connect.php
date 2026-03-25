<?php
// Configurações do Banco de Dados para a equipe
$host = 'localhost';
$db   = 'cine_critica';
$user = 'root';
$pass = '';

$conexao = mysqli_connect($host, $user, $pass, $db);

if($conexao->connect_error){
    die("Conexão falhou: " . $conexao->connect_error);
}
?>