/*
    Table Executions
        - ID sequencial
        - Tipo (se é script cron ou web)
        - Maquina (precisa ter um código referente a máquina autorizada
                    para identificar a origem)
        - ProcessoPai (nome do script ou do processo web principal)
        - Processo    (nome do subprocesso, quando estiver analisando
                        partes internas de um processo principal
                        nulo se estiver analisando só o principal)
        - DHInicio (vir já em timestamp na hora local do servidor que executou)
        - DHFim     (vir já em timestamp na hora local do servidor que executou)
        Os timestamps precisam vir prontos, porque se usar
            o current_timestamp do banco de dados vai ter que fazer duas chamadas
            uma para abertura e outra para finalização da execução
            e isso irá consumir tempo do próprio processo 
            para processar a chamada inicial
    Table Machines
        id ,         name,         password
    Table tipo desnecessário, porque será apenas cron ou web
    Table Process
        id ,         name
        */
Create table process (
    id integer auto_increment,
    name varchar(250),
    constraint primary key (id)
);

Create table machines(
    id integer auto_increment,
    name varchar(30),
    hashed_password varchar(255),
    constraint primary key (id)
);

Create table executions (
     id integer auto_increment,
     tipo varchar(10),
     machine_id integer not null,
     process_id_princ integer not null,
     process_id_sub integer not null,
     dhini timestamp,
     dhfim timestamp,
     constraint primary key (id),
     constraint foreign key (machine_id) references machines (id),
     constraint foreign key (process_id_princ) references process (id),
     constraint foreign key (process_id_sub) references process (id)
);

