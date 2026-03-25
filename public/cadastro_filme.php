<?php 
require_once '../config/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Filme | Cine-Crítica</title>
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>
    <div class="form-card">
        <h2>Cadastrar Novo Filme</h2>
        
        <form action="../modules/cadastro/processar_filme.php" method="POST" enctype="multipart/form-data">
            
            <div class="input-group">
                <label>Título do Filme</label>
                <input type="text" name="titulo_filme" required placeholder="Ex: Oppenheimer">
            </div>

            <div class="input-group">
                <label>Gêneros</label>
                <div class="dropdown-container" id="generoDropdown">
                    <div class="dropdown-button" id="btnSelected">Selecionar categorias...</div>
                    <div class="dropdown-content" id="dropdownList">
                        <div class="genero-grid">
                            <?php
                            $res = mysqli_query($conexao, "SELECT * FROM genero ORDER BY nome_genero ASC");
                            while ($g = mysqli_fetch_assoc($res)) {
                                echo "<label class='genero-item'>
                                        <input type='checkbox' name='generos[]' value='{$g['id_genero']}' data-label='{$g['nome_genero']}'> 
                                        {$g['nome_genero']}
                                      </label>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>Ano</label>
                    <input type="number" name="ano_lancamento" min="1888" max="2030" placeholder="AAAA">
                </div>
                <div class="input-group">
                    <label>Duração (min)</label>
                    <input type="number" name="duracao_minutos" placeholder="Ex: 120">
                </div>
                <div class="input-group">
                    <label>Diretor</label>
                    <input type="text" name="diretor" placeholder="Nome do Diretor">
                </div>
            </div>

            <div class="form-row">
                <div class="input-group" style="flex: 2;">
                    <label>Link do Trailer (YouTube)</label>
                    <input type="url" name="url_trailer" placeholder="https://youtube.com/watch?v=...">
                </div>
                <div class="input-group" style="flex: 3;">
                    <label>Resumo Curto (Card)</label>
                    <input type="text" name="resumo" maxlength="255" placeholder="Uma breve frase sobre o filme">
                </div>
            </div>

            <div class="input-group">
                <label>Elenco Principal</label>
                <textarea name="elenco" rows="2" placeholder="Ator 1, Ator 2, Atriz 3..."></textarea>
            </div>

            <div class="upload-grid">
                <div class="upload-box">
                    <label>Poster (Vertical)</label>
                    <input type="file" name="foto_poster" accept="image/*" onchange="previewImg(this, 'p-poster')">
                    <div class="preview-container">
                        <img id="p-poster" src="" alt="Preview Poster" style="display:none;">
                    </div>
                </div>
                <div class="upload-box">
                    <label>Banner (Horizontal)</label>
                    <input type="file" name="foto_banner" accept="image/*" onchange="previewImg(this, 'p-banner')">
                    <div class="preview-container banner">
                        <img id="p-banner" src="" alt="Preview Banner" style="display:none;">
                    </div>
                </div>
            </div>

            <div class="input-group">
                <label>Sinopse Completa</label>
                <textarea name="sinopse" rows="5" placeholder="Descreva a história do filme em detalhes..."></textarea>
            </div>

            <button type="submit" class="btn-yellow">Finalizar Cadastro</button>
            <a href="index.php" class="btn-cancel">Cancelar e Voltar</a>
        </form>
    </div>

    <script>
        // Lógica do Dropdown
        const dropdown = document.getElementById('generoDropdown');
        const btn = document.getElementById('btnSelected');
        const list = document.getElementById('dropdownList');
        const checkboxes = document.querySelectorAll('input[name="generos[]"]');

        btn.addEventListener('click', (e) => {
            list.classList.toggle('show');
            e.stopPropagation();
        });

        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) list.classList.remove('show');
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const selected = Array.from(checkboxes)
                    .filter(c => c.checked)
                    .map(c => c.getAttribute('data-label'));
                
                btn.innerText = selected.length > 0 ? selected.join(', ') : 'Selecionar categorias...';
            });
        });

        // Lógica de Preview
        function previewImg(input, id) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.getElementById(id);
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>