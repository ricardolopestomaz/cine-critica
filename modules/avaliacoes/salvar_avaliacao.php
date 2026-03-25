<?php
session_start();
require '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método inválido.");
}

$id_usuario = 1; // usuário fixo para teste

$id_filme   = isset($_POST['id_filme']) ? (int) $_POST['id_filme'] : 0;
$nota       = isset($_POST['nota']) ? (int) $_POST['nota'] : -1;
$critica    = isset($_POST['critica']) ? trim($_POST['critica']) : '';

if ($id_filme <= 0) {
    die("Filme inválido.");
}

if ($nota < 0 || $nota > 5) {
    die("Nota inválida.");
}

/* Verifica se o usuário já avaliou esse filme */
$sql_check = "SELECT id_avaliacao FROM avaliacao WHERE id_usuario = ? AND id_filme = ?";
$stmt_check = $conexao->prepare($sql_check);

if (!$stmt_check) {
    die("Erro ao preparar verificação: " . $conexao->error);
}

$stmt_check->bind_param("ii", $id_usuario, $id_filme);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    /* Atualiza avaliação existente */
    $sql_update = "UPDATE avaliacao
                   SET nota = ?, critica = ?, data_avaliacao = NOW()
                   WHERE id_usuario = ? AND id_filme = ?";
    $stmt_update = $conexao->prepare($sql_update);

    if (!$stmt_update) {
        die("Erro ao preparar atualização: " . $conexao->error);
    }

    $stmt_update->bind_param("isii", $nota, $critica, $id_usuario, $id_filme);

    if (!$stmt_update->execute()) {
        die("Erro ao atualizar avaliação: " . $stmt_update->error);
    }
} else {
    /* Insere nova avaliação */
    $sql_insert = "INSERT INTO avaliacao (id_usuario, id_filme, nota, critica)
                   VALUES (?, ?, ?, ?)";
    $stmt_insert = $conexao->prepare($sql_insert);

    if (!$stmt_insert) {
        die("Erro ao preparar inserção: " . $conexao->error);
    }

    $stmt_insert->bind_param("iiis", $id_usuario, $id_filme, $nota, $critica);

    if (!$stmt_insert->execute()) {
        die("Erro ao inserir avaliação: " . $stmt_insert->error);
    }
}

header("Location: ../../public/avaliacao.php?id_filme=" . $id_filme);
exit;
?>