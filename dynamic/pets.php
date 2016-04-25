<?php
/*
#Dados Recebidos do usuario:
cadastrar: recebe todas informações para cadastrar um pet (nome, especie_id, usuario_id(pode omitir))
atualiza: igual ao cadastra mas recebe o id do pet
deletar: recebe o id do pet para excluir
consultar: recebe o id do pet e retorna todas informações
consultarDono: recebe id do dono e retorna todos os pets desse dono
pesquisaNome: recebe nome parcial do pet e retorna pets que conferem
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("config.php");
require_once("verifica_usuario.php");

header('Content-type: application/json');

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

if($input == null or !(isset($input->consultarDono) or isset($input->consultar))) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}

$petsPorDono = array();

if(isset($input->consultarDono)) 
    foreach($bancoDados as $registro) 
        if($input->consultarDono == $registro['idDono'])
            $petsPorDono[] = $registro;
    
$petsPorID = null;
if(isset($input->consultar)) {
    foreach($bancoDados as $registro) {
        if($input->consultar == $registro['id']) {
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