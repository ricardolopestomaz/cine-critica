<?php

require_once __DIR__ . '/../../config/db_connect.php';

$termo = isset($_GET['busca']) ? mysqli_real_escape_string($conexao, $_GET['busca']) : '';

$sql = "SELECT * FROM filme WHERE titulo_filme LIKE '%$termo%' OR resumo LIKE '%$termo%'";
$resultado = mysqli_query($conexao, $sql);

