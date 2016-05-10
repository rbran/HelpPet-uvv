<?php
/*
#Dados Recebidos do usuario:
retornaDados: (qualquer valor, True recomendado) envia os dados do usuario logado
atualizaDados: recebe dados do usuario (nome, email,senha, localizacao)
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

$bancoDados = [['id' => 1, 'nome' => 'Usuario1', 'email' => 'usuario1@example.com', 'localizacao' => ['latitude' => -20.341164, 'logitude' => -40.313314]],
               ['id' => 2, 'nome' => 'Usuario2', 'email' => 'usuario2@example.com', 'localizacao' => ['latitude' => -20.341164, 'logitude' => -40.313314]],
               ['id' => 3, 'nome' => 'Usuario3', 'email' => 'admin@example.com', 'localizacao' => ['latitude' => -20.341164, 'logitude' => -40.313314]],
              ];

$jsonReturn = array('resposta' => 'sucesso');

if(isset($input->retornaDados)) {
    $id = $token->data->id;
    
    $usuario = null;
    foreach($bancoDados as $registro) 
        if($registro['id'] == $id){
            $usuario = $registro;
            break;
        }
    if($usuario == null){
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Usuario não encontrado']); //envia resposta de erro
        exit;
    }
    $jsonReturn['usuario'] = $usuario;
}

if(isset($input->atualizaDados)) {
    //TODO com o SQL
}

echo json_encode($jsonReturn);
?>