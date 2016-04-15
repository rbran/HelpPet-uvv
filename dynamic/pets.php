<?php
/*
Irá receber o a id do usuario ou id do pet (TODO: adicionar pesquisa por nome, dono, etc...) e ira retornar informações sobre o pet.
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("config.php");

header('Content-type: application/json');

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



$bancoDados = [['id' => 1, 'idDono' => '1', 'nome' => 'pet1', 'especie' => 'cachorro'],
               ['id' => 2, 'idDono' => '1', 'nome' => 'pet2', 'especie' => 'gato'],
               ['id' => 3, 'idDono' => '2', 'nome' => 'pet3', 'especie' => 'gato'],
               ['id' => 4, 'idDono' => '2', 'nome' => 'pet4', 'especie' => 'gato'],
               ['id' => 5, 'idDono' => '2', 'nome' => 'pet5', 'especie' => 'gato'],
               ['id' => 6, 'idDono' => '2', 'nome' => 'pet6', 'especie' => 'gato'],
               ['id' => 7, 'idDono' => '2', 'nome' => 'pet7', 'especie' => 'gato'],
               ['id' => 8, 'idDono' => '2', 'nome' => 'pet8', 'especie' => 'gato'],
               ['id' => 9, 'idDono' => '2', 'nome' => 'pet9', 'especie' => 'gato'],
               ['id' => 10, 'idDono' => '2', 'nome' => 'pet10', 'especie' => 'gato'],
               ['id' => 11, 'idDono' => '2', 'nome' => 'pet11', 'especie' => 'gato']
              ];

$input = @json_decode(utf8_encode(file_get_contents("php://input")));

if($input == null or !(isset($input->idDono) or isset($input->id))) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}

$petsPorDono = array();

if(isset($input->idDono)) 
    foreach($bancoDados as $registro) 
        if($input->idDono == $registro['idDono'])
            $petsPorDono[] = $registro;
    
$petsPorID = null;
if(isset($input->id)) {
    foreach($bancoDados as $registro) {
        if($input->id == $registro['id']) {
            $petsPorID = $registro;
            break;
        }
    }
}

$JsonReturn = array('resposta' => 'sucesso');
if(count($petsPorDono) > 0)
    $JsonReturn['idDono'] = $petsPorDono;
    
if($petsPorID != null)
    $JsonReturn['id'] = $petsPorID;

echo json_encode($JsonReturn);

?>