<?php
session_start();
require_once '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método inválido.");
}

/* Verifica se o usuário está logado */
if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não está logado.");
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_filme   = isset($_POST['id_filme']) ? (int) $_POST['id_filme'] : 0;
$nota       = isset($_POST['nota']) ? (int) $_POST['nota'] : -1;
$critica    = isset($_POST['critica']) ? trim($_POST['critica']) : '';

if ($id_filme <= 0) {
    die("Filme inválido.");
}

if ($nota < 0 || $nota > 5) {
    die("Nota inválida.");
}

$sql = "INSERT INTO avaliacao (id_usuario, id_filme, nota, critica, data_avaliacao)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($conexao, $sql);

if (!$stmt) {
    die("Erro ao preparar a query: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param($stmt, "iiis", $id_usuario, $id_filme, $nota, $critica);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: avaliar_filme.php?id_filme=" . $id_filme);
    exit;
} else {
    die("Erro ao salvar avaliação: " . mysqli_stmt_error($stmt));
}
?>