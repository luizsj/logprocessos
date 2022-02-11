<?php
//endpoint para chamadas de API
//recebe um json e salva os dados no banco
//checando antes o usuário e senha da máquina
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('bd.php');


    $function_to_call = 'savelog';
    echo('chamar função '.$function_to_call.$lf);
    if (function_exists($function_to_call)) {
        $result = $function_to_call();
    } else {
        echo("<p>Chamada inválida");
    }


function savelog(){
    global $lf;
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    print_r($dados);
    
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
        $process_id_filho = bd_aux_process_get_id($dados['process_filho']);
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