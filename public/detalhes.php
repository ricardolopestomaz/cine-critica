<?php
require_once "../modules/detalhes/get_filme_detalhes.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $filme['titulo_filme']; ?> - Cine-Crítica</title>
    <link rel="stylesheet" href="css/detalhes.css">
</head>

<body>

    <header>
        <div class="header-container">
            <div>
                <h1><?php echo $filme['titulo_filme']; ?></h1>
                <p style="color: #bbb; margin-top: 5px;">
                    <?php echo $filme['ano_lancamento']; ?> • <?php echo $filme['duracao_minutos']; ?> min
                </p>
            </div>

            <div class="rating-header">
                <span
                    style="color: #bbb; font-size: 0.8rem; display: block; text-transform: uppercase; margin-bottom: 5px;">Avaliação
                    Cine-Crítica</span>
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 15px;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span class="star-yellow">★</span>
                        <div>
                            <span class="rating-value" style="color: #fff; font-size: 1.3rem;">8.5</span><span
                                style="color: #666;">/10</span>
                        </div>
                    </div>

                    <button class="btn-avaliar" onclick="abrirModalAvaliacao()">
                        <span class="star-outline">☆</span> Avaliar
                    </button>
                </div>
            </div>
        </div>
    </header>

    <section class="movie-hero">
        <div class="backdrop" style="background-image: url('<?php echo $filme['url_banner']; ?>');"></div>

        <div class="container-visual">
            <div class="poster-side">
                <img id="poster" src="<?php echo $filme['url_poster']; ?>" crossOrigin="anonymous" alt="Poster">

                <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>
                <script>
                    const colorThief = new ColorThief();
                    const img = document.getElementById('poster');

                    if (img.complete) {
                        setTheme();
                    } else {
                        img.addEventListener('load', function () {
                            setTheme();
                        });
                    }

                    function setTheme() {
                        try {
                            const color = colorThief.getColor(img);

                            // Escurece a cor (fator 0.3) para o fundo não ficar claro/esbranquiçado
                            const r = Math.floor(color[0] * 0.3);
                            const g = Math.floor(color[1] * 0.3);
                            const b = Math.floor(color[2] * 0.3);

                            const rgbEscuro = `rgb(${r}, ${g}, ${b})`;
                            const rgbReal = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;

                            // Aplica o degradê no fundo do site
                            document.body.style.background = `linear-gradient(to bottom, ${rgbEscuro} 0%, #000000 100%)`;

                            // Aplica a cor original na borda da caixa de crítica
                            const criticaBox = document.querySelector('.critica-box');
                            if (criticaBox) {
                                criticaBox.style.borderColor = rgbReal;
                            }
                        } catch (e) {
                            console.error("Erro ao processar cores da imagem", e);
                        }
                    }
                </script>
            </div>

            <div class="video-side">
                <iframe src="<?php echo $trailer_final; ?>" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <section class="info-secao">
        <div style="margin-bottom: 25px;">
            <?php
            mysqli_data_seek($res_generos, 0);
            while ($genero = mysqli_fetch_assoc($res_generos)): ?>
                <span class="tag-genero"><?php echo $genero['nome_genero']; ?></span>
            <?php endwhile; ?>
        </div>

        <p style="font-size: 1.1rem; line-height: 1.6;"><strong>Resumo:</strong> <?php echo $filme['resumo']; ?></p>

        <hr>

        <h3>Sinopse</h3>
        <p style="color: #ccc; line-height: 1.8;">
            <?php echo nl2br($filme['sinopse']); ?>
        </p>

        <p><strong>Direção:</strong> <span style="color: #5799ef;"><?php echo $filme['diretor']; ?></span></p>
        <p><strong>Elenco:</strong> <span style="color: #5799ef;"><?php echo $filme['elenco']; ?></span></p>

        <hr>

        <div class="banner-separator" style="background-image: url('<?php echo $filme['url_banner']; ?>');">
            <div class="overlay-banner"></div>
        </div>

        <h3>Crítica em Destaque</h3>
        <div class="critica-box">
            <p><em>"Aqui entrará o texto da crítica vindo do banco de dados na próxima etapa do projeto..."</em></p>
            <small style="color: #666;">— Enviado por Usuário Exemplo</small>
        </div>

        <footer style="margin-top: 50px; padding-bottom: 30px;">
            <a href="index.php" style="color: #f5c518; text-decoration: none; font-weight: bold;">← VOLTAR PARA A
                HOME</a>
        </footer>
    </section>

</body>

</html>