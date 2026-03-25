<?php

require_once __DIR__ . '/../modules/busca/busca.php';


//require_once('header.php'); 
?>

<div class="container">
    <h1>🔍 Resultados da Busca</h1>
    <div class="movies-grid">
        <?php

        if (mysqli_num_rows($resultado) > 0) {
            while ($filme = mysqli_fetch_assoc($resultado)) {
                ?>
                <div class="movie-card">
                    <h3><?php echo $filme['titulo_filme']; ?></h3>
                    <a href="detalhes.php?id=<?php echo $filme['id_filme']; ?>">Ver Detalhes do Filme</a>
                </div>
                <?php
            }
        } else {
            echo "Nenhum filme encontrado.";
        }
        ?>
    </div>
</div>

<?php //require_once('footer.php'); ?>