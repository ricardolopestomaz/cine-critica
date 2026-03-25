<?php
require_once '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo   = mysqli_real_escape_string($conexao, $_POST['titulo_filme']);
    $ano      = (int)$_POST['ano_lancamento'];
    $duracao  = (int)$_POST['duracao_minutos'];
    $trailer  = mysqli_real_escape_string($conexao, $_POST['url_trailer']);
    $diretor  = mysqli_real_escape_string($conexao, $_POST['diretor']);
    $elenco   = mysqli_real_escape_string($conexao, $_POST['elenco']);
    $resumo   = mysqli_real_escape_string($conexao, $_POST['resumo']);
    $sinopse  = mysqli_real_escape_string($conexao, $_POST['sinopse']);
    $generos  = $_POST['generos'] ?? [];

    // Função simples para tratar os dois uploads
    function tratarUpload($campo, $pasta) {
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] == 0) {
            $ext = pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION);
            $nome = time() . "_" . uniqid() . "." . $ext;
            if (move_uploaded_file($_FILES[$campo]['tmp_name'], "../../public/uploads/" . $nome)) {
                return "uploads/" . $nome;
            }
        }
        return "";
    }

    $url_poster = tratarUpload('foto_poster', 'uploads/');
    $url_banner = tratarUpload('foto_banner', 'uploads/');

    $sql = "INSERT INTO filme (titulo_filme, ano_lancamento, duracao_minutos, url_poster, url_banner, url_trailer, resumo, sinopse, diretor, elenco) 
            VALUES ('$titulo', '$ano', '$duracao', '$url_poster', '$url_banner', '$trailer', '$resumo', '$sinopse', '$diretor', '$elenco')";

    if (mysqli_query($conexao, $sql)) {
        $id_filme = mysqli_insert_id($conexao);
        foreach ($generos as $id_gen) {
            mysqli_query($conexao, "INSERT INTO filme_genero (id_filme, id_genero) VALUES ($id_filme, $id_gen)");
        }
        header("Location: ../../public/cadastro_filme.php?sucesso=1");
    } else {
        echo "Erro no banco: " . mysqli_error($conexao);
    }
}