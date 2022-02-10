<?php
//primeiro precisa ter uma máquina com um hashed_password salvo
/*
include('bd.php');
$instr = "insert into machines (name, hashed_password) values (?, ?)";
$params[0] = 'TestSoares';
$params[1] = password_hash('testing123', PASSWORD_DEFAULT);
$sql = bd_query_execute($instr, $params);
*/

//a chamada para o serviço é uma requisição http
//passando url http://localhost/logprocessos/savelog.php&fn=savelog 
//definindo método post
//e passando os dados via json



?>