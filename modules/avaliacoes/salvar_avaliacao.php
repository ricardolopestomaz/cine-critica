<?php
require '../../config/db_connect.php';

$id_usuario = 1; // usuário fixo para teste

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método inválido.");
}

$id_filme = isset($_POST['id_filme']) ? (int) $_POST['id_filme'] : 0;
$nota = isset($_POST['nota']) ? (int) $_POST['nota'] : 0;
$critica = isset($_POST['critica']) ? trim($_POST['critica']) : '';

if ($id_filme <= 0) {
    die("Filme inválido.");
}

if ($nota < 1 || $nota > 5) {
    die("Nota inválida.");
}

/* Verifica se já avaliou */
$sql_check = "SELECT id_avaliacao FROM avaliacao WHERE id_usuario=? AND id_filme=?";
$stmt_check = $conexao->prepare($sql_check);
$stmt_check->bind_param("ii", $id_usuario, $id_filme);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {

    $sql_update = "UPDATE avaliacao
                   SET nota=?, critica=?, data_avaliacao=NOW()
                   WHERE id_usuario=? AND id_filme=?";
    $stmt_update = $conexao->prepare($sql_update);
    $stmt_update->bind_param("isii", $nota, $critica, $id_usuario, $id_filme);
    $stmt_update->execute();

} else {

    $sql_insert = "INSERT INTO avaliacao (id_usuario,id_filme,nota,critica)
                   VALUES (?,?,?,?)";
    $stmt_insert = $conexao->prepare($sql_insert);
    $stmt_insert->bind_param("iiis", $id_usuario, $id_filme, $nota, $critica);
    $stmt_insert->execute();
}

header("Location: ../../public/avaliacao.php?id_filme=".$id_filme);
exit;
?>