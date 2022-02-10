<?php

/*banco Maria DB rodando em docker 

https://www.leobreda.net/artigos/instalando-o-mysql-no-docker-43.html

--Create Machine
sudo docker run --detach --name mariadb_logproc -p3306:3306 -v /var/www/html/logprocessos/bd/data:/var/lib/mysql  --env MARIADB_USER=luizsoares --env MARIADB_PASSWORD=golf_card --env MARIADB_ROOT_PASSWORD=my_golf_field  mariadb:latest
//alterar a senha do root para executar o comando acima


--Start/Sttop Machine
sudo docker start mariadb_logproc
sudo docker stop mariadb_logproc


--enter container console
sudo docker exec -it mariadb_logproc bash

mariadb -u root -p
my_golf_field (senha do root)

Create database logprocessos;


CREATE USER 'luizsoares'@'172.17.0.1' IDENTIFIED BY 'golf_card';
GRANT ALL PRIVILEGES ON logprocessos.* TO 'luizsoares'@'172.17.0.1';
FLUSH PRIVILEGES;

*/
if (!file_exists('bd_params.php')) {
    echo('Necessário criar o bd_params.php com os parâmetros do banco de dados!'.chr(10).chr(13));
    exit;
}
include('bd_params.php'); //define o array bd_params com os parâmetros de conexão

global $conn;
$conn = new mysqli($bd_params['host'], $bd_params['user'], $bd_params['password'], $bd_params['database']);

function bd_query_select_unique_value($instr, $params){
    global $conn;
    $instr = bd_query_instr_params($instr, $params);
    $result = mysqli_query($conn, $instr);
    $row = mysqli_fetch_row($result);
    return $row[0];
}

function bd_query_execute_return_id($instr, $params) {
    global $conn;
    $query = bd_query_execute($instr, $params);
    $id = mysqli_insert_id($conn);
    return $id;
}

function bd_query_execute($instr, $params){
    global $conn;
    $instr = bd_query_instr_params($instr, $params);
    $query = mysqli_query($conn, $instr);
    if ($query == '')
        {   var_dump(mysqli_error($conn)); }
    return $query;
}

function bd_query_instr_params($instr, $params) {
    global $conn;
    for ($i=0; $i < count($params); $i++){
        $pos = strpos($instr, '?');
        if ($pos !== false) {
            $replace = "'".mysqli_real_escape_string($conn, $params[$i])."'";
            $instr = substr_replace($instr, $replace, $pos, 1);
        }
    }
    return $instr;
}

function bd_query_select_unique_row($instr, $params) {
    global $conn;
    $instr = bd_query_instr_params($instr, $params);
    $result = mysqli_query($conn, $instr);
    $row = mysqli_fetch_assoc($result);
    return $row;
}
?>