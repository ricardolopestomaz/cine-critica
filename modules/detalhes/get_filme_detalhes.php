<?php 

include_once __DIR__ . "/../../config/db_connect.php";


// Verificação de Segurança: Existe um ID na URL?
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=id_nao_encontrado");
    exit;
}

// Sanitização: Evita SQL Injection
$id_filme = (int)$_GET['id'];

$sql_filme = "SELECT * FROM filme WHERE id_filme = $id_filme";
$resultado_filme = mysqli_query($conexao, $sql_filme);

// Verifica se o filme existe no banco
if(mysqli_num_rows($resultado_filme) == 0) {
    header("Location: index.php?erro=filme_inexistente");
    exit;
}

// Transforma o resultado em um Array
$filme = mysqli_fetch_assoc($resultado_filme);

// Consulta Secundária: Busca os Gêneros
$sql_generos = "SELECT g.nome_genero 
                FROM genero g
                INNER JOIN filme_genero fg ON g.id_genero = fg.id_genero
                WHERE fg.id_filme = $id_filme";
$res_generos = mysqli_query($conexao, $sql_generos);


// Tratamento: O YouTube normal não roda dentro de <iframe>, precisa ser o link /embed/

function prepararTrailer($url) {
    if (strpos($url, 'youtu.be') !== false) {
        $id = explode('youtu.be/', $url)[1];
        $id = explode('?', $id)[0]; // Remove o ?si=...
        return "https://www.youtube.com/embed/" . $id;
    } 
    if (strpos($url, 'v=') !== false) {
        $id = explode('v=', $url)[1];
        $id = explode('&', $id)[0];
        return "https://www.youtube.com/embed/" . $id;
    }
    return $url; // Se já for embed ou outro formato
}

// Criamos uma variável fácil para usar no HTML depois
$trailer_final = prepararTrailer($filme['url_trailer']);
?>

