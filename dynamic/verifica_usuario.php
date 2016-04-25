<?php
use \Firebase\JWT\JWT;

$jwt = sscanf(apache_request_headers()["authorization"], 'Bearer %s')[0];
if (!$jwt) { //verfifica se o usuario foi autenticado
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Usuario não autenticado']); //envia resposta de erro
    exit;
}
$horaAtual = time();

$token = '';
try{
    $token = JWT::decode($jwt, $JWTkey, array('HS256'));
}catch(BeforeValidException $e) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Autenticação feita no futudo (verifique relógio): ' . $e->getMessage()]); //envia resposta de erro
    exit;
}catch(ExpiredException $e) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Autenticação Expirada: ' . $e->getMessage()]); //envia resposta de erro
    exit;
}catch(Exception $e){
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Autenticação invalida: ' . $e->getMessage()]); //envia resposta de erro
    exit;
}