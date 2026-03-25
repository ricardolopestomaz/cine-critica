<?php 
require_once '../config/db_connect.php'; 

// ===============================================
// CONSULTA SQL PARA BUSCAR TODOS OS FILMES
// ===============================================
$termo = isset($_GET['busca']) ? mysqli_real_escape_string($conexao, $_GET['busca']) : '';

$sql_filmes = "
    SELECT 
        f.id_filme,
        f.titulo_filme,
        f.ano_lancamento,
        f.duracao_minutos,
        f.url_poster,
        f.resumo,
        f.diretor,
        GROUP_CONCAT(g.nome_genero SEPARATOR ', ') AS generos
    FROM filme f
    LEFT JOIN filme_genero fg ON f.id_filme = fg.id_filme
    LEFT JOIN genero g ON fg.id_genero = g.id_genero
";

if (!empty($termo)) {
    $sql_filmes .= " WHERE f.titulo_filme LIKE '%$termo%' OR f.resumo LIKE '%$termo%'";
}

$sql_filmes .= "
    GROUP BY f.id_filme
    ORDER BY f.data_cadastro DESC
";

$resultado_filmes = mysqli_query($conexao, $sql_filmes);

// Verifica se a consulta executou corretamente
if (!$resultado_filmes) {
    die("Erro na consulta: " . mysqli_error($conexao));
}

// Conta quantos filmes existem
$total_filmes = mysqli_num_rows($resultado_filmes);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cine-Crítica | Sua Base de Dados Cinematográfica</title>
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- HEADER / NAVEGAÇÃO -->
    <header class="main-header">
        <div class="header-container">
            <div class="logo-section">
                <h1 class="logo">🎬 Cine-Crítica</h1>
                <p class="tagline">Sua base de dados cinematográfica</p>
            </div>

            <nav class="nav-menu">
                <a href="index.php" class="nav-link active">Home</a>
                <a href="cadastro_filme.php" class="nav-link">➕ Novo Filme</a>
                <a href="cadastro_usuario.php" class="nav-link">👤 Cadastro</a>
            </nav>
        </div>
    </header>

    <!-- SEÇÃO HERO / DESTAQUE -->
    <section class="hero-section">
        <div class="hero-content">
            <h2>Bem-vindo ao Cine-Crítica</h2>
            <p>Explore nossa coleção de <strong><?php echo $total_filmes; ?> filmes</strong> e descubra suas próximas obras-primas</p>
            <a href="cadastro_filme.php" class="btn-primary">Adicionar Novo Filme</a>
        </div>
    </section>

    <!-- FILTROS (OPCIONAL - Para Futuro) -->
    <section class="filter-section">
        <div class="filter-container">
            <input type="text" id="search-input" class="search-box" placeholder="🔍 Buscar por título...">
            <select id="genre-filter" class="filter-select">
                <option value="">Todos os Gêneros</option>
                <option value="Ação">Ação</option>
                <option value="Aventura">Aventura</option>
                <option value="Animação">Animação</option>
                <option value="Comédia">Comédia</option>
                <option value="Drama">Drama</option>
                <option value="Ficção Científica">Ficção Científica</option>
                <option value="Terror">Terror</option>
                <option value="Romance">Romance</option>
            </select>
        </div>
    </section>

    <!-- GRID DE FILMES -->
    <main class="movies-container">
        <div class="movies-grid">

            <?php 
            // Verifica se existem filmes cadastrados
            if ($total_filmes == 0): 
            ?>
                <div class="empty-state">
                    <p>📽️ Nenhum filme cadastrado ainda</p>
                    <a href="cadastro_filme.php" class="btn-primary">Cadastre o Primeiro!</a>
                </div>

            <?php 
            else:
                // Itera sobre cada filme e cria um card
                while ($filme = mysqli_fetch_assoc($resultado_filmes)): 
            ?>
                    <article class="movie-card">
                        <!-- POSTER -->
                        <div class="card-image">
                            <img src="<?php echo htmlspecialchars($filme['url_poster']); ?>" 
                                 alt="<?php echo htmlspecialchars($filme['titulo_filme']); ?>"
                                 loading="lazy">
                            <div class="card-overlay">
                                <a href="detalhes.php?id=<?php echo $filme['id_filme']; ?>" class="btn-view">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>

                        <!-- INFORMAÇÕES DO FILME -->
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($filme['titulo_filme']); ?></h3>
                            
                            <p class="card-meta">
                                <span class="year">📅 <?php echo $filme['ano_lancamento']; ?></span>
                                <span class="duration">⏱️ <?php echo $filme['duracao_minutos']; ?> min</span>
                            </p>

                            <p class="card-director">
                                <strong>Dir:</strong> <?php echo htmlspecialchars($filme['diretor']); ?>
                            </p>

                            <p class="card-synopsis">
                                <?php echo htmlspecialchars(substr($filme['resumo'], 0, 100)) . '...'; ?>
                            </p>

                            <!-- GÊNEROS -->
                            <div class="card-genres">
                                <?php 
                                $generos = array_slice(explode(', ', $filme['generos']), 0, 3);
                                foreach ($generos as $genero): 
                                ?>
                                    <span class="genre-badge"><?php echo htmlspecialchars(trim($genero)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>

            <?php 
                endwhile;
            endif; 
            ?>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="main-footer">
        <div class="footer-content">
            <p>&copy; 2026 Cine-Crítica | Desenvolvido pela Equipe UFT</p>
            <p>
                <strong>Membros:</strong> Anna Beatriz | Grazyelle Nayara | Pedro Ryan | Ricardo Lopes
            </p>
            <p><em>Disciplina: Engenharia de Software | Professor: Edeilson Milhomem</em></p>
        </div>
    </footer>

    <!-- SCRIPTS PARA FILTROS (OPCIONAL) -->
    <script>
        // Filtro de busca por título
        document.getElementById('search-input').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.movie-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                card.style.display = title.includes(searchTerm) ? 'flex' : 'none';
            });
        });

        // Filtro por gênero
        document.getElementById('genre-filter').addEventListener('change', function() {
            const selectedGenre = this.value.toLowerCase();
            const cards = document.querySelectorAll('.movie-card');
            
            cards.forEach(card => {
                if (!selectedGenre) {
                    card.style.display = 'flex';
                } else {
                    const genres = card.querySelector('.card-genres').textContent.toLowerCase();
                    card.style.display = genres.includes(selectedGenre) ? 'flex' : 'none';
                }
            });
        });
    </script>
</body>
</html>