<?php

//TODO: criar o banco de dados caso o mesmo não exista

if(!file_exists('../etc/banco-dados.sqlite3')){
    //TOPO: RESOLVER ISSO, evitar usar shell_exec ou equivalente
    shell_exec('sqlite3 ../etc/banco-dados.sqlite3  < ../etc/banco-de-dados.sql');
}

$bancoDados = new SQLite3('../etc/banco-dados.sqlite3');

if(!$bancoDados){
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Erro no banco de dados: ' . $bancoDados->lastErrorMsg()]); //envia resposta de erro
    exit;
}
$bancoDados->exec('PRAGMA foreign_keys = ON');