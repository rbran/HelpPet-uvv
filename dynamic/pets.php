<?php
/*
#Dados Recebidos do usuario:
cadastra: recebe todas informações para cadastra um pet (nome, especie, perdido(null||descricao, localizacao), adocao(null||idPet, descricao)), retorna id cadastrado
atualiza: igual ao cadastra mas recebe o idPet, retorna true ou false (valores omitidos não são alterados, obrigatório id e qualquer outro)
deleta: recebe o idPet para excluir
consultar: recebe o idPet e retorna todas informações
consultarPerdido: recebe qualquer valor (true recomendado), envia todos pets perdidos
consultarAdocao: recebe qualquer valor (true recomendado), envia todos pets para adocao
consultarDono: recebe idDono e retorna todos os pets desse dono
pesquisaNome: recebe nome parcial do pet e retorna pets que conferem
pesquisaPerdidoLocalizacao: recebe pets perdidos proximos, recebe qualquer valor (true recomendado)
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("connect_sqlite.php");
include("config.php");
include("verifica_usuario.php");

header('Content-type: application/json');

/*
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
              ];*/

$input = @json_decode(utf8_encode(file_get_contents("php://input")));

if($input == null) {
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}

$jsonReturn = array('resposta' => 'sucesso');

if(isset($input->cadastra)) {
    if(!isset($input->cadastra->nome) or
        !isset($input->cadastra->especie) or
        (isset($input->cadastra->perdido) and (!isset($input->cadastra->perdido->ultimaLocalizacao) or !isset($input->cadastra->perdido->observacao))) or
        (isset($input->cadastra->adocao) and !isset($input->cadastra->adocao->observacao))
        ) {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para cadastro']); //envia resposta de erro
        exit;
    }
         
    $sql = 'INSERT INTO `Animal` (`nome`, `especie_id`, `usuario_id`) VALUES (:nome, :especie_id, :usuario_id)';
    $stmt = $bancoDados->prepare($sql);
    $stmt->bindValue(':nome', $input->cadastra->nome, SQLITE3_TEXT);
    $stmt->bindValue(':especie_id', $input->cadastra->especie, SQLITE3_INTEGER);
    $stmt->bindValue(':usuario_id', $token->data->id, SQLITE3_INTEGER);
    
    $resultado = $stmt->execute();
    
    $animal_id = $bancoDados->lastInsertRowID();
    
    if(isset($input->cadastra->perdido)){
        $localizacao = ($input->cadastra->perdido->ultimaLocalizacao->latitude) . ',' . ($input->cadastra->perdido->ultimaLocalizacao->longitude);
        
        $sql = 'INSERT INTO `AnimalPerdido` (`animal_id`, `ultimaLocalizacao`, `observacao`) VALUES (:animal_id, :ultimaLocalizacao, :observacao)';
        $stmt = $bancoDados->prepare($sql);
        $stmt->bindValue(':animal_id', $animal_id, SQLITE3_INTEGER);
        $stmt->bindValue(':ultimaLocalizacao', $localizacao, SQLITE3_TEXT);
        $stmt->bindValue(':observacao', $input->cadastra->perdido->observacao, SQLITE3_TEXT);
        
        $resultado = $stmt->execute();
    }
    
    if(isset($input->cadastra->adocao)){
        $sql = 'INSERT INTO `AnimalPerdido` (`animal_id`, `observacao`) VALUES (:animal_id, :observacao)';
        $stmt = $bancoDados->prepare($sql);
        $stmt->bindValue(':animal_id', $animal_id, SQLITE3_INTEGER);
        $stmt->bindValue(':observacao', $input->cadastra->adocao->observacao, SQLITE3_TEXT);
        
        $resultado = $stmt->execute();
    }
    
    $jsonReturn['cadastra'] = $animal_id;
}

if(isset($input->atualiza)) {
    if(!isset($input->atualiza->id)){
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para Atualização']); //envia resposta de erro
        exit;
    }
    
    if(isset($input->atualiza->nome) or isset($input->atualiza->especie_id) or isset($input->atualiza->usuario_id)){
        $parameter = [];
        if(isset($input->atualiza->nome)){
            $parameter[] = '`nome` = :nome';
        }
        if(isset($input->atualiza->especie_id)){
            $parameter[] = '`especie_id` = :especie_id';
        }
        if(isset($input->atualiza->usuario_id)){
            $parameter[] = '`usuario_id` = :usuario_id';
        }
        
        $sql = 'UPDATE `Animal` SET ' . implode(',', $parameter) . ' WHERE `id` = :id';
        $stmt = $bancoDados->prepare($sql);
        $stmt->bindValue(':id', $input->atualiza->id, SQLITE3_INTEGER);
        
        if(isset($input->atualiza->nome)){
            $stmt->bindValue(':nome', $input->atualiza->nome, SQLITE3_TEXT);
        }
        if(isset($input->atualiza->especie_id)){
            $stmt->bindValue(':especie_id', $input->atualiza->especie_id, SQLITE3_INTEGER);
        }
        if(isset($input->atualiza->usuario_id)){
            $stmt->bindValue(':usuario_id', $input->atualiza->usuario_id, SQLITE3_INTEGER);
        }
        $resultado = $stmt->execute();
    }
        
    if(property_exists($input->atualiza,'perdido')){
        if($input->atualiza->perdido != null){
            $localizacao = ($input->atualiza->perdido->localizacao->latitude) . ',' . ($input->atualiza->perdido->localizacao->longitude);
            
            $sql = 'INSERT OR REPLACE INTO `Perdido` (`animal_id`,`ultimaLocalizacao`,`observacao`) VALUES (:animal_id,:ultimaLocalizacao,:observacao)';
            $stmt = $bancoDados->prepare($sql);
            $stmt->bindValue(':animal_id', $input->atualiza->id, SQLITE3_INTEGER);
            $stmt->bindValue(':ultimaLocalizacao', $localizacao, SQLITE3_TEXT);
            $stmt->bindValue(':observacao', $input->atualiza->perdido->observacao, SQLITE3_TEXT);
            
            $resultado = $stmt->execute();
        }else{
            $sql = 'DELETE FROM `Perdido` WHERE `animal_id` = :animal_id';
            $stmt = $bancoDados->prepare($sql);
            $stmt->bindValue(':animal_id', $input->atualiza->id, SQLITE3_INTEGER);
            
            $resultado = $stmt->execute();
        }
    }
    if(property_exists($input->atualiza,'adocao')){
        if($input->atualiza->adocao != null){
            $sql = 'INSERT OR REPLACE INTO `Adocao` (`animal_id`,`observacao`) VALUES (:animal_id,:observacao)';
            $stmt = $bancoDados->prepare($sql);
            $stmt->bindValue(':animal_id', $input->atualiza->id, SQLITE3_INTEGER);
            $stmt->bindValue(':observacao', $input->atualiza->adocao->observacao, SQLITE3_TEXT);
            
            $resultado = $stmt->execute();
        }else{
            $sql = 'DELETE FROM `Adocao` WHERE `animal_id` = :animal_id';
            $stmt = $bancoDados->prepare($sql);
            $stmt->bindValue(':animal_id', $input->atualiza->id, SQLITE3_INTEGER);
            
            $resultado = $stmt->execute();
        }
    }
    
    $jsonReturn['atualiza'] = true;
}

if(isset($input->deleta)) {
    if(gettype($input->deleta) != 'integer') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para Deleção']); //envia resposta de erro
        exit;
    }
    $sql = 'DELETE FROM `Animal` WHERE `id` = :id';
    $stmt = $bancoDados->prepare($sql);
    $stmt->bindValue(':id', $input->deleta, SQLITE3_INTEGER);
    $resultado = $stmt->execute();
    
    $jsonReturn['deleta'] = true;
}

if(isset($input->consultar)) {
    if(gettype($input->consultar) != 'integer') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para consulta']); //envia resposta de erro
        exit;
    }
    $sql = 'SELECT `id`,'.
                '`nome`,'.
                '`especie_id`,'.
                '`usuario_id`,'.
                '`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Adocao`.`animal_id` AS `adocao_id`,'.
                '`ultimaLocalizacao`,`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Perdido`.`observacao` AS `observacao_perdido`,'.
                '`Adocao`.`observacao` AS `observacao_adocao`'.
            'FROM `Animal` '.
                'LEFT JOIN `Perdido` ON `Perdido`.`animal_id` = `Animal`.`id` '.
                'LEFT JOIN `Adocao` ON `Adocao`.`animal_id` = `Animal`.`id` '.
            'WHERE `Animal`.`id` = :id';
    $stmt = $bancoDados->prepare($sql);
    $stmt->bindValue(':id', $input->consultar, SQLITE3_INTEGER);
    $resultado = $stmt->execute();
    
    while ($linha = $resultado->fetchArray(SQLITE3_ASSOC)) {
        if($linha['adocao_id'] !== null){
            $linha['adocao'] = ['observacao' => $linha['observacao_adocao']];
        }else{
            $linha['adocao'] = null;
        }
        unset($linha['adocao_id']);
        unset($linha['observacao_adocao']);
        
        if($linha['perdido_id'] !== null){
            list($latitude, $longitude) = sscanf($linha['ultimaLocalizacao'], "%f,%f");
            $linha['perdido'] = ['observacao' => $linha['observacao_perdido'], 'localizacao' => ['latitude' => $latitude, 'longitude' => $longitude]];
        }else{
            $linha['perdido'] = null;
        }
        unset($linha['perdido_id']);
        unset($linha['observacao_perdido']);
        unset($linha['ultimaLocalizacao']);
        $jsonReturn['consultar'] = $linha;
    }
}

if(isset($input->consultarPerdido)) {
    $sql = 'SELECT `Animal`.`id`,'.
                '`Animal`.`nome`,'.
                '`Animal`.`especie_id`,'.
                '`Animal`.`usuario_id`,'.
                '`Perdido`.`ultimaLocalizacao`,'.
                '`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Perdido`.`observacao` AS `observacao_perdido`, '.
                '`Usuario`.`email` AS `email_dono`, '.
                '`Usuario`.`nome` AS `nome_dono` '.
            'FROM `Animal` '.
                'INNER JOIN `Perdido` ON `Perdido`.`animal_id` = `Animal`.`id` '.
                'LEFT JOIN `Usuario` ON `Usuario`.`id` = `Animal`.`usuario_id`';
    $resultado = $bancoDados->query($sql);
    
    $jsonReturn['consultarPerdido'] = [];
    while ($linha = $resultado->fetchArray(SQLITE3_ASSOC)) {
        if($linha['perdido_id'] != null){
            list($latitude, $longitude) = sscanf($linha['ultimaLocalizacao'], "%f,%f");
            $linha['perdido'] = ['observacao' => $linha['observacao_perdido'], 'localizacao' => ['latitude' => $latitude, 'longitude' => $longitude]];
        }else{
            $linha['perdido'] = null;
        }
        unset($linha['perdido_id']);
        unset($linha['observacao_perdido']);
        unset($linha['ultimaLocalizacao']);
        $jsonReturn['consultarPerdido'][] = $linha;
    }
}

if(isset($input->consultarAdocao)) {
    $sql = 'SELECT `Animal`.`id`,'.
                '`Animal`.`nome`,'.
                '`Animal`.`especie_id`,'.
                '`Animal`.`usuario_id`,'.
                '`Adocao`.`animal_id` AS `adocao_id`,'.
                '`Adocao`.`observacao` AS `observacao_adocao`,'.
                '`Usuario`.`email` AS `email_dono`,'.
                '`Usuario`.`nome` AS `nome_dono` '.
            'FROM `Animal` '.
                'INNER JOIN `Adocao` ON `Adocao`.`animal_id` = `Animal`.`id` '.
                'LEFT JOIN `Usuario` ON `Usuario`.`id` = `Animal`.`usuario_id`';
    $resultado = $bancoDados->query($sql);
    
    $jsonReturn['consultarAdocao'] = [];
    while ($linha = $resultado->fetchArray(SQLITE3_ASSOC)) {
        if($linha['adocao_id'] != null){
            $linha['adocao'] = ['observacao' => $linha['observacao_adocao']];
        }else{
            $linha['adocao'] = null;
        }
        unset($linha['adocao_id']);
        unset($linha['observacao_adocao']);
        
        $jsonReturn['consultarAdocao'][] = $linha;
    }
}

if(isset($input->consultarDono)) {
    if(gettype($input->consultarDono) != 'integer') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para consulta']); //envia resposta de erro
        exit;
    }
    $sql = 'SELECT `id`,'.
                '`nome`,'.
                '`especie_id`,'.
                '`usuario_id`,'.
                '`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Adocao`.`animal_id` AS `adocao_id`,'.
                '`ultimaLocalizacao`,`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Perdido`.`observacao` AS `observacao_perdido`,'.
                '`Adocao`.`observacao` AS `observacao_adocao`'.
            'FROM `Animal` '.
                'LEFT JOIN `Perdido` ON `Perdido`.`animal_id` = `Animal`.`id` '.
                'LEFT JOIN `Adocao` ON `Adocao`.`animal_id` = `Animal`.`id` '.
            'WHERE `Animal`.`usuario_id` = :id';
    $stmt = $bancoDados->prepare($sql);
    $stmt->bindValue(':id', $input->consultarDono, SQLITE3_INTEGER);
    $resultado = $stmt->execute();
    
    $jsonReturn['consultarDono'] = [];
    while ($linha = $resultado->fetchArray(SQLITE3_ASSOC)) {
        if($linha['adocao_id'] != null){
            $linha['adocao'] = ['observacao' => $linha['observacao_adocao']];
        }else{
            $linha['adocao'] = null;
        }
        unset($linha['adocao_id']);
        unset($linha['observacao_adocao']);
        
        if($linha['perdido_id'] != null){
            list($latitude, $longitude) = sscanf($linha['ultimaLocalizacao'], "%f,%f");
            $linha['perdido'] = ['observacao' => $linha['observacao_perdido'], 'localizacao' => ['latitude' => $latitude, 'longitude' => $longitude]];
        }else{
            $linha['perdido'] = null;
        }
        unset($linha['perdido_id']);
        unset($linha['observacao_perdido']);
        unset($linha['ultimaLocalizacao']);
        $jsonReturn['consultarDono'][] = $linha;
    }
}

if(isset($input->pesquisaNome)) {
    if(gettype($input->pesquisaNome) != 'string') {
        echo json_encode(['resposta' => 'erro', 'mensagem' => 'Dados Invalidos para consulta']); //envia resposta de erro
        exit;
    }
    $sql = 'SELECT `id`,'.
                '`nome`,'.
                '`especie_id`,'.
                '`usuario_id`,'.
                '`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Adocao`.`animal_id` AS `adocao_id`,'.
                '`ultimaLocalizacao`,`Perdido`.`animal_id` AS `perdido_id`,'.
                '`Perdido`.`observacao` AS `observacao_perdido`,'.
                '`Adocao`.`observacao` AS `observacao_adocao`'.
            'FROM `Animal` '.
                'LEFT JOIN `Perdido` ON `Perdido`.`animal_id` = `Animal`.`id` '.
                'LEFT JOIN `Adocao` ON `Adocao`.`animal_id` = `Animal`.`id` '.
            'WHERE `Animal`.`nome` = :nome';
    $stmt = $bancoDados->prepare($sql);
    $stmt->bindValue(':nome', $input->pesquisaNome, SQLITE3_TEXT);
    $resultado = $stmt->execute();
    
    $jsonReturn['pesquisaNome'] = [];
    while ($linha = $resultado->fetchArray(SQLITE3_ASSOC)) {
        if($linha['adocao_id'] != null){
            $linha['adocao'] = ['observacao' => $linha['observacao_adocao']];
        }else{
            $linha['adocao'] = null;
        }
        unset($linha['adocao_id']);
        unset($linha['observacao_adocao']);
        
        if($linha['perdido_id'] != null){
            list($latitude, $longitude) = sscanf($linha['ultimaLocalizacao'], "%f,%f");
            $linha['perdido'] = ['observacao' => $linha['observacao_perdido'], 'localizacao' => ['latitude' => $latitude, 'longitude' => $longitude]];
        }else{
            $linha['perdido'] = null;
        }
        unset($linha['perdido_id']);
        unset($linha['observacao_perdido']);
        unset($linha['ultimaLocalizacao']);
        $jsonReturn['pesquisaNome'][] = $linha;
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