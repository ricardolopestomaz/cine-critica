<?php
require "../modules/avaliacoes/salvar_avaliacao.php"
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Avaliações</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($filme['titulo_filme']); ?></h1>
    <p><?php echo htmlspecialchars($filme['resumo'] ?? ''); ?></p>

    <h2>Resumo das avaliações</h2>
    <p><strong>Média:</strong> <?php echo $media; ?></p>
    <p><strong>Total de avaliações:</strong> <?php echo $total; ?></p>

    <h2>Enviar avaliação</h2>
    <form action="salvar_avaliacao.php" method="POST">
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
                <small><?php echo $avaliacao['data_avaliacao']; ?></small>
                <p><?php echo nl2br(htmlspecialchars($avaliacao['critica'] ?? '')); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Ainda não há avaliações para este filme.</p>
    <?php endif; ?>
</body>
</html>

