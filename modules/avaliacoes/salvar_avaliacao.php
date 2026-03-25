<?php
require '../config/db_connect.php';

$id_filme = isset($_GET['id_filme']) ? (int) $_GET['id_filme'] : 0;

if ($id_filme <= 0) {
    die("Filme inválido.");
}

/* Buscar dados do filme */
$sql_filme = "SELECT titulo_filme, resumo FROM filme WHERE id_filme = ?";
$stmt_filme = $conexao->prepare($sql_filme);
$stmt_filme->bind_param("i", $id_filme);
$stmt_filme->execute();
$result_filme = $stmt_filme->get_result();

if ($result_filme->num_rows === 0) {
    die("Filme não encontrado.");
}

$filme = $result_filme->fetch_assoc();

/* Buscar média e total de avaliações */
$sql_media = "SELECT AVG(nota) AS media, COUNT(*) AS total 
              FROM avaliacao 
              WHERE id_filme = ?";
$stmt_media = $conexao->prepare($sql_media);
$stmt_media->bind_param("i", $id_filme);
$stmt_media->execute();
$result_media = $stmt_media->get_result();
$dados_media = $result_media->fetch_assoc();

$media = $dados_media['media'] ? number_format($dados_media['media'], 1) : "0.0";
$total = $dados_media['total'];

/* Buscar avaliações com nome do usuário */
$sql_avaliacoes = "SELECT u.nome_usuario, a.nota, a.critica, a.data_avaliacao
                   FROM avaliacao a
                   INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                   WHERE a.id_filme = ?
                   ORDER BY a.data_avaliacao DESC";
$stmt_avaliacoes = $conexao->prepare($sql_avaliacoes);
$stmt_avaliacoes->bind_param("i", $id_filme);
$stmt_avaliacoes->execute();
$result_avaliacoes = $stmt_avaliacoes->get_result();
?>

