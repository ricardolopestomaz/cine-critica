<?php
require "../config/db_connect.php";

$id_filme = isset($_GET['id_filme']) ? (int) $_GET['id_filme'] : 0;

if ($id_filme <= 0) {
    die("Filme inválido.");
}

/* Buscar dados do filme */
$sql_filme = "SELECT titulo_filme, resumo, url_poster FROM filme WHERE id_filme = ?";
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
    <link rel="stylesheet" href="css/avaliacao.css">
</head>
<body>
    <div class="container-avaliacao">

        <div class="topo-avaliacao">
            <h1><?php echo htmlspecialchars($filme['titulo_filme']); ?></h1>

            <?php if (!empty($filme['url_poster'])): ?>
                <div class="poster-filme">
                    <img src="<?php echo htmlspecialchars($filme['url_poster']); ?>" alt="Poster do filme">
                </div>
            <?php endif; ?>

            <p class="resumo-filme">
                <?php echo htmlspecialchars($filme['resumo'] ?? ''); ?>
            </p>
        </div>

        <div class="resumo-box">
            <h2>Resumo das avaliações</h2>
            <p><strong>Média:</strong> <?php echo $media; ?></p>
            <p><strong>Total de avaliações:</strong> <?php echo $total; ?></p>
        </div>

        <div class="form-avaliacao">
            <h2>Enviar avaliação</h2>

            <form action="../modules/avaliacoes/salvar_avaliacao.php" method="POST">
                <input type="hidden" name="id_filme" value="<?php echo $id_filme; ?>">

                <div class="form-group">
                    <label>Sua nota</label>

                    <div class="estrelas">
                        <input type="radio" name="nota" id="estrela5" value="5" required>
                        <label for="estrela5">★</label>

                        <input type="radio" name="nota" id="estrela4" value="4">
                        <label for="estrela4">★</label>

                        <input type="radio" name="nota" id="estrela3" value="3">
                        <label for="estrela3">★</label>

                        <input type="radio" name="nota" id="estrela2" value="2">
                        <label for="estrela2">★</label>

                        <input type="radio" name="nota" id="estrela1" value="1">
                        <label for="estrela1">★</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="critica">Crítica</label>
                    <textarea name="critica" id="critica" placeholder="Escreva sua opinião sobre o filme"></textarea>
                </div>

                <button type="submit" class="btn-enviar">Enviar avaliação</button>
            </form>
        </div>

        <div class="lista-avaliacoes">
            <h2>Avaliações dos usuários</h2>

            <?php if ($result_avaliacoes->num_rows > 0): ?>
                <?php while ($avaliacao = $result_avaliacoes->fetch_assoc()): ?>
                    <div class="card-avaliacao">
                        <div class="usuario">
                            <?php echo htmlspecialchars($avaliacao['nome_usuario']); ?>
                        </div>

                        <div class="nota">
                            Nota: <?php echo (int) $avaliacao['nota']; ?>
                        </div>

                        <small class="data">
                            <?php echo htmlspecialchars($avaliacao['data_avaliacao']); ?>
                        </small>

                        <p class="critica">
                            <?php echo nl2br(htmlspecialchars($avaliacao['critica'] ?? '')); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="sem-avaliacoes">Ainda não há avaliações para este filme.</p>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>