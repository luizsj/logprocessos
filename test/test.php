<?php
include('bd.php');
//primeiro precisa ter uma máquina com um hashed_password salvo
/*

$instr = "insert into machines (name, hashed_password) values (?, ?)";
$params[0] = 'TestSoares';
$params[1] = password_hash('testing123', PASSWORD_DEFAULT);
$sql = bd_query_execute($instr, $params);
*/


$log['machine_id'] = 1;
$log['machine_pass'] = 'testing123';
$log['process_pai'] = 'teste de processo pai';
$log['process_filho'] = '';
$log['dhinicio'] = '2021-02-10 14:53:02.0256';
$log['dhfim']    = '2021-02-10 14:53:17.3587';
$log['tipo']    = 'web';
$dados_json = json_encode($log);
$url = 'http://localhost/logprocessos/savelog.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $dados_json );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
$response = curl_exec($ch);
curl_close($ch);
//echo $response;

?>