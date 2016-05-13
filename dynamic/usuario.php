<?php
/*
#Dados Recebidos do usuario:
retornaDados: (qualquer valor, True recomendado) envia os dados do usuario logado
atualizaDados: recebe dados do usuario (nome, email,senha, localizacao)
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("connect_sqlite.php");
require_once("config.php");
require_once("verifica_usuario.php");

header('Content-type: application/json');

$input = @json_decode(utf8_encode(file_get_contents("php://input")));

if($input == null or !(isset($input->retornaDados) or isset($input->atualizaDados))) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}
/*
$bancoDados = [['id' => 1, 'nome' => 'Usuario1', 'email' => 'usuario1@example.com', 'localizacao' => ['latitude' => -20.341164, 'longitude' => -40.313314]],
               ['id' => 2, 'nome' => 'Usuario2', 'email' => 'usuario2@example.com', 'localizacao' => ['latitude' => -20.341164, 'longitude' => -40.313314]],
               ['id' => 3, 'nome' => 'Usuario3', 'email' => 'admin@example.com', 'localizacao' => ['latitude' => -20.341164, 'longitude' => -40.313314]],
              ];*/

$jsonReturn = array('resposta' => 'sucesso');

if(isset($input->retornaDados)) {
    $id = $token->data->id;
    
    $usuario = null;

    $sql = 'SELECT * FROM `Usuario` WHERE `id` = :id';
    $stmt = $bancoDados->prepare($sql);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $resultado = $stmt->execute();
    
    if($resultado != null and $linha = $resultado->fetchArray(SQLITE3_ASSOC)){
        unset($linha['senha']);
        $localizacao = $linha['localizacao'];
        list($latitude, $longitude) = sscanf($localizacao, "%f,%f");
        $linha['localizacao'] = ['latitude' => $latitude, 'longitude' => $longitude];
        $usuario = $linha;
    }
    
    if($usuario == null){
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Usuario não encontrado']); //envia resposta de erro
        exit;
    }
    $jsonReturn['usuario'] = $usuario;
}

if(isset($input->atualizaDados)) {
    $parameter = [];
    if(!isset($input->atualizaDados->id)){
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida, ID Usuario não encontrado']); //envia resposta de erro
        exit;
    }
    if(isset($input->atualizaDados->nome)){
         $parameter[] = '`nome` = :nome';
    }
    if(isset($input->atualizaDados->email)){
         $parameter[] = '`email` = :email';
    }
    if(isset($input->atualizaDados->senha)){
         $parameter[] = '`senha` = :senha';
    }
    if(isset($input->atualizaDados->localizacao)){
         $parameter[] = '`localizacao` = :localizacao';
    }
         
    $sql = 'UPDATE `Usuario` SET ' . implode(',', $parameter) . ' WHERE `id` = :id';
    $stmt = $bancoDados->prepare($sql);
    
    $stmt->bindValue(':id', $input->atualizaDados->id, SQLITE3_INTEGER);
    if(isset($input->atualizaDados->nome)){
        $stmt->bindValue(':nome', $input->atualizaDados->nome, SQLITE3_TEXT);
    }
    if(isset($input->atualizaDados->email)){
        $stmt->bindValue(':email', $input->atualizaDados->email, SQLITE3_TEXT);
    }
    if(isset($input->atualizaDados->senha)){
        $stmt->bindValue(':senha', $input->atualizaDados->senha, SQLITE3_TEXT);
    }
    if(isset($input->atualizaDados->localizacao)){
        if(!isset($input->atualizaDados->localizacao->latitude) or !isset($input->atualizaDados->localizacao->longitude)){
            echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida, Dados invalidos']); //envia resposta de erro
            exit;
        }
        $localizacao = ($input->atualizaDados->localizacao->latitude) . ',' . ($input->atualizaDados->localizacao->longitude);
        $stmt->bindValue(':localizacao', $localizacao, SQLITE3_TEXT);
    }
    
    if(!$stmt->execute()){
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Erro no Banco de Dados']); //envia resposta de erro
        exit;
    }
}

echo json_encode($jsonReturn);
?>