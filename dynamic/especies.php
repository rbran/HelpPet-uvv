<?php
/*
#Dados Recebidos do usuario:
consulta: retorna Todas especies
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("connect_sqlite.php");
require_once("config.php");
//require_once("verifica_usuario.php"); //usuarios não logados podem receber a lista de espécies

header('Content-type: application/json; charset=utf-8');

$sql = 'SELECT * FROM `Especie`';
$resultado = $bancoDados->query($sql);

$jsonReturn = array('resposta' => 'sucesso');

$jsonReturn['consulta'] = [];
while ($linha = $resultado->fetchArray(SQLITE3_ASSOC)) {
    $jsonReturn['consulta'][] = $linha;
}

echo json_encode($jsonReturn);
?>