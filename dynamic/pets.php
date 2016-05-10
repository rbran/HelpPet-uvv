<?php
/*
#Dados Recebidos do usuario:
cadastrar: recebe todas informações para cadastrar um pet (nome, idEspecie, idUsuario(pode omitir), perdido(null||descricao, localizacao), adocao(null||idPet, descricao)), retorna id cadastrado
atualiza: igual ao cadastra mas recebe o idPet, retorna true ou false (valores omitidos não são alterados, obrigatório id e qualquer outro)
deletar: recebe o idPet para excluir
consultar: recebe o idPet e retorna todas informações
consultarPerdido: recebe qualquer valor (true recomendado), envia todos pets perdidos
consultarAdocao: recebe qualquer valor (true recomendado), envia todos pets para adocao
consultarDono: recebe idDono e retorna todos os pets desse dono
pesquisaNome: recebe nome parcial do pet e retorna pets que conferem
pesquisaPerdidoLocalizacao: recebe pets perdidos proximos, recebe qualquer valor (true recomendado)
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
include("config.php");
include("verifica_usuario.php");

header('Content-type: application/json');

$bancoDados = [['id' => 1, 'idDono' => 1, 'nome' => 'pet1', 'especie' => 1, 'especieNome' => 'cachorro', 'perdido' => null, 'adocao' => null],
               ['id' => 2, 'idDono' => 1, 'nome' => 'pet2', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => null],
               ['id' => 3, 'idDono' => 2, 'nome' => 'pet3', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => null],
               ['id' => 4, 'idDono' => 2, 'nome' => 'pet4', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => ['descricao' => 'descrição1234123', 'localizacao' => ['latitude' => '-20.341164', 'longitude' => '-40.313314']], 'adocao' => null],
               ['id' => 5, 'idDono' => 2, 'nome' => 'pet5', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => null],
               ['id' => 6, 'idDono' => 2, 'nome' => 'pet6', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => null],
               ['id' => 7, 'idDono' => 3, 'nome' => 'pet7', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => ['descricao' => 'descrição1234123', 'localizacao' => ['latitude' => '-20.347460', 'longitude' => '-40.330949']], 'adocao' => null],
               ['id' => 8, 'idDono' => 2, 'nome' => 'pet8', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => null],
               ['id' => 9, 'idDono' => 2, 'nome' => 'pet9', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => ['descricao' => 'descrição1234123']],
               ['id' => 10, 'idDono' => 2, 'nome' => 'pet10', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => null],
               ['id' => 11, 'idDono' => 2, 'nome' => 'pet11', 'especie' => 2, 'especieNome' => 'gato', 'perdido' => null, 'adocao' => ['descricao' => 'descrição1234123']]
              ];

$input = @json_decode(utf8_encode(file_get_contents("php://input")));

if($input == null) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}

$jsonReturn = array('resposta' => 'sucesso');

if(isset($input->cadastrar)) {
    if(!isset($input->cadastrar->nome) or !isset($input->cadastrar->idEspecie)) {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para cadastro']); //envia resposta de erro
        exit;
    }
    //perdido e adotado podem ser omitidos ou null
    
    //TODO
    $jsonReturn['cadastrar'] = 1;
}

if(isset($input->atualiza)) {
    if(!isset($input->atualiza->id) or 
        (!isset($input->atualiza->nome) and
        !isset($input->atualiza->idEspecie) and
        !isset($input->atualiza->idUsuario) and
        !isset($input->atualiza->perdido) and
        !isset($input->atualiza->adotado))
    ) {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para Atualização']); //envia resposta de erro
        exit;
    }
    //TODO
    $jsonReturn['atualiza'] = true;
}

if(isset($input->deletar)) {
    if(gettype($input->deletar) != 'integer') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para Deleção']); //envia resposta de erro
        exit;
    }
    //TODO
    $jsonReturn['deletar'] = true;
}

if(isset($input->consultar)) {
    if(gettype($input->consultar) != 'integer') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para consulta']); //envia resposta de erro
        exit;
    }
    foreach($bancoDados as $registro){
        if($registro['id'] == $input->consultar){
            $jsonReturn['consultar'] = $registro;
            break;
        }
    }
}

if(isset($input->consultarPerdido)) {
    foreach($bancoDados as $registro){
        if($registro['perdido'] !== null){
            $jsonReturn['consultarPerdido'][] = $registro;
        }
    }
}

if(isset($input->consultarAdocao)) {
    foreach($bancoDados as $registro){
        if($registro['adocao'] !== null){
            $jsonReturn['consultarAdocao'][] = $registro;
        }
    }
}

if(isset($input->consultarDono)) {
    if(gettype($input->consultarDono) != 'integer') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para consulta']); //envia resposta de erro
        exit;
    }
    $jsonReturn['consultarDono'] = array();
    foreach($bancoDados as $registro){
        if($registro['idDono'] == $input->consultarDono){
            $jsonReturn['consultarDono'][] = $registro;
        }
    }
}

if(isset($input->pesquisaNome)) {
    if(gettype($input->pesquisaNome) != 'string') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para consulta']); //envia resposta de erro
        exit;
    }
    $jsonReturn['pesquisaNome'] = array();
    foreach($bancoDados as $registro){
        if(strpos($registro['nome'], $input->pesquisaNome) !== false){
            $jsonReturn['pesquisaNome'][] = $registro;
        }
    }
}

if(isset($input->pesquisaPerdidoLocalizacao)) {
    $jsonReturn['pesquisaPerdidoLocalizacao'] = array();
    //TODO recebe 'n' pets mais proximos (token contem localização), por enquanto retorna todos que tem perdido
    foreach($bancoDados as $registro){
        if($registro['perdido'] !== null){
            $jsonReturn['pesquisaPerdidoLocalizacao'][] = $registro;
        }
    }
}

echo json_encode($jsonReturn);