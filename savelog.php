<?php
//endpoint para chamadas de API
//recebe um json e salva os dados no banco
//checando antes o usuário e senha da máquina

include('bd.php');

if (!isset($_GET['fn'])) {
    echo("<p>Chamada inválida");
} else {
    $function_to_call = $_GET['fn'];
    if (function_exists($function_to_call)) {
        $result = $function_to_call();
    } else {
        echo("<p>Chamada inválida");
    }
}

function savelog(){
    $dados = $_POST['dados_json'];
    $dados = json_decode($dados, true);
/*  $dados é uma array
        ['machine_id'] = integer
        ['machine_pass'] = 'texto'
        ['process_pai'] = 'texto'
        ['process_filho] = 'texto'
        ['dhinicio'] = 'YYYY-MM-DD HH:II:SS.msms'
        ['dhfim] = 'YYYY-MM-DD HH:II:SS.msms'
        */
    if (savelog_valid_machine($dados['machine_id'], $dados['machine_pass'])) {
        $process_id_princ = bd_aux_process_get_id($dados['process_pai']);
        $process_id_filho = bd_aux_process_get_id($dados['$process_filho']);
        
    }
}

function savelog_valid_machine($id, $pass) {
    $hashed_password = bd_aux_machine_get_hashed_password($id);
    $result = password_verify($pass, $hashed_password);
    return $result;
}
?>