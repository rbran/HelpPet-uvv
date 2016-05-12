<?php

//TODO: criar o banco de dados caso o mesmo não exista

$bancoDados = new SQLite3('../etc/banco-dados.sqlite3');

if(!$bancoDados){
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Erro no banco de dados: ' . $bancoDados->lastErrorMsg()]); //envia resposta de erro
    exit;
}