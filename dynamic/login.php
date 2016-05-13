<?php
/*
Irá receber o login e password e irá retornar o token. Tudo vai functionar usando JWT

Tutoriais:
http://code.tutsplus.com/pt/tutorials/token-based-authentication-with-angularjs-nodejs--cms-22543

Biblioteca auxiliar:
https://github.com/firebase/php-jwt
*/

use \Firebase\JWT\JWT;
require_once("vendor/autoload.php");
require_once("connect_sqlite.php");
require_once("config.php");

header('Content-type: application/json');

$horaAtual = time();
$token = [
    'iat'  => $horaAtual,                            // Issued at: time when the token was generated
    'jti'  => base64_encode(mcrypt_create_iv(32)),   // Json Token Id: an unique identifier for the token
    'iss'  => $nomeServidor,                         // Issuer
    'nbf'  => $horaAtual,                            // Not before
    'exp'  => $horaAtual + (60*60),                  // Expire
    'data' => null                                   // Data to be signed
];

$input = @json_decode(utf8_encode(file_get_contents("php://input"))); // converto o input em json; o "@" remove a mensagem de erro (caso existir)

if($input == null or !isset($input->login) or !isset($input->senha)){
    echo json_encode(['resposta' => 'erro', 'mensagem' => 'Requisição invalida']); //envia resposta de erro
    exit;
}

//JWT::$leeway = 60; // $leeway in seconds // precisa??

$usuario = null;

$sql = 'SELECT * FROM `Usuario` WHERE `email` = :login and `senha` = :senha';
$stmt = $bancoDados->prepare($sql);
$stmt->bindValue(':login', $input->login, SQLITE3_TEXT);
$stmt->bindValue(':senha', $input->senha, SQLITE3_TEXT);

$resultado = $stmt->execute();

if($resultado != null and $linha = $resultado->fetchArray(SQLITE3_ASSOC)){
    unset($linha['senha']);
    $usuario = $linha;
}

if($usuario != null){
    $token['data'] = $usuario; //adiciona o login aos dados que seram assinados pelo jwt
    $jwt = JWT::encode($token, $JWTkey, 'HS256'); //assina os dados do usuario
    echo json_encode(['resposta' => 'sucesso', 'jwt' => $jwt]); //envia a resposta json
    exit;
}

echo json_encode(['resposta' => 'erro', 'mensagem' => 'Login ou Senha Invalido']); //envia resposta de erro
exit;

?>