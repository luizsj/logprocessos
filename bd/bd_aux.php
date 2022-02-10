<?php

function bd_aux_machine_get_hashed_password($id) {
    $instr = "select hashed_password from machines where id = ?";
    $params[0] = $id;
    $hashed = bd_query_select_unique_value($instr, $params);
    return $hashed;
}

function bd_aux_process_get_id($texto) {
    $instr = "select id from process where lower(name) = lower(?)";
    $params[0] = $texto;
    $exist_id = bd_query_select_unique_value($instr, $params);
    if ($exist_id == '') {
        $instr = "insert into process (name) values lower(?)";
        $exist_id = bd_query_execute_return_id($instr, $params);
    }
    return $exist_id;
}

?>