<?php
/*
#Dados Recebidos do usuario:
consulta: retorna Todas especies
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("config.php");
require_once("verifica_usuario.php");

header('Content-type: application/json');

$input = @json_decode(utf8_encode(file_get_contents("php://input")));

if($input == null or !(isset($input->retornaDados) or isset($input->atualizaDados))) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}

$bancoDados = [['id' => 1, 'nome' => 'cachorro'],
               ['id' => 2, 'email' => 'gato'],
               ['id' => 3, 'email' => 'outros'],
              ];

$jsonReturn = array('resposta' => 'sucesso');

$jsonReturn['consulta'] = $bancoDados;

echo json_encode($jsonReturn);
?>