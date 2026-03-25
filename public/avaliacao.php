<?php
require "../config/db_connect.php";

$id_filme = isset($_GET['id_filme']) ? (int) $_GET['id_filme'] : 0;

if ($id_filme <= 0) {
    die("Filme inválido.");
}

/* Buscar dados do filme */
$sql_filme = "SELECT titulo_filme, resumo FROM filme WHERE id_filme = ?";
$stmt_filme = $conexao->prepare($sql_filme);

if (!$stmt_filme) {
    die("Erro ao preparar consulta do filme: " . $conexao->error);
}

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

if (!$stmt_media) {
    die("Erro ao preparar consulta da média: " . $conexao->error);
}

$stmt_media->bind_param("i", $id_filme);
$stmt_media->execute();
$result_media = $stmt_media->get_result();
$dados_media = $result_media->fetch_assoc();

$media = $dados_media['media'] !== null ? number_format($dados_media['media'], 1) : "0.0";
$total = $dados_media['total'];

/* Buscar avaliações com nome do usuário */
$sql_avaliacoes = "SELECT u.nome_usuario, a.nota, a.critica, a.data_avaliacao
                   FROM avaliacao a
                   INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                   WHERE a.id_filme = ?
                   ORDER BY a.data_avaliacao DESC";
$stmt_avaliacoes = $conexao->prepare($sql_avaliacoes);

if (!$stmt_avaliacoes) {
    die("Erro ao preparar consulta das avaliações: " . $conexao->error);
}

$stmt_avaliacoes->bind_param("i", $id_filme);
$stmt_avaliacoes->execute();
$result_avaliacoes = $stmt_avaliacoes->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($filme['titulo_filme']); ?></h1>
    <p><?php echo htmlspecialchars($filme['resumo'] ?? ''); ?></p>

    <h2>Resumo das avaliações</h2>
    <p><strong>Média:</strong> <?php echo $media; ?></p>
    <p><strong>Total de avaliações:</strong> <?php echo $total; ?></p>

    <h2>Enviar avaliação</h2>
    <form action="../modules/avaliacoes/salvar_avaliacao.php" method="POST">
        <input type="hidden" name="id_filme" value="<?php echo $id_filme; ?>">

        <label for="nota">Nota:</label><br>
        <select name="nota" id="nota" required>
            <option value="">Selecione</option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <br><br>

        <label for="critica">Crítica:</label><br>
        <textarea name="critica" id="critica" rows="5" cols="50"></textarea>
        <br><br>

        <button type="submit">Enviar avaliação</button>
    </form>

    <h2>Avaliações dos usuários</h2>

    <?php if ($result_avaliacoes->num_rows > 0): ?>
        <?php while ($avaliacao = $result_avaliacoes->fetch_assoc()): ?>
            <div style="margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                <strong><?php echo htmlspecialchars($avaliacao['nome_usuario']); ?></strong>
                - Nota: <?php echo (int) $avaliacao['nota']; ?><br>
                <small><?php echo htmlspecialchars($avaliacao['data_avaliacao']); ?></small>
                <p><?php echo nl2br(htmlspecialchars($avaliacao['critica'] ?? '')); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Ainda não há avaliações para este filme.</p>
    <?php endif; ?>
</body>
</html>