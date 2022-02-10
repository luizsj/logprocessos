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
        $instr = "insert into executions (tipo, machine_id, process_id_princ, process_id_sub, dhini, dhfim)
                        values (? , ? , ? , ? , ?, ?)";
        $params[0] = $dados['tipo'];
        $params[1] = $dados['machine_id'];
        $params[2] = $process_id_princ;
        $params[3] = $process_id_filho;
        $params[4] = $dados['dhinicio'];
        $params[5] = $dados['dhfim'];
        $sql = bd_query_execute($instr, $params);
        if (!$sql) {
            exit;
        }
    }
}

function savelog_valid_machine($id, $pass) {
    $hashed_password = bd_aux_machine_get_hashed_password($id);
    $result = password_verify($pass, $hashed_password);
    return $result;
}
?>