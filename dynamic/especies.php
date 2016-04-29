<?php
/*
#Dados Recebidos do usuario:
consulta: retorna Todas especies
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("config.php");
//require_once("verifica_usuario.php"); //usuarios não logados podem receber a lista de espécies

header('Content-type: application/json');

$bancoDados = [['id' => 1, 'nome' => 'cachorro'],
               ['id' => 2, 'nome' => 'gato'],
               ['id' => 3, 'nome' => 'outros'],
              ];

$jsonReturn = array('resposta' => 'sucesso');

$jsonReturn['consulta'] = $bancoDados;

echo json_encode($jsonReturn);
?>