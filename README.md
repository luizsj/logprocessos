# logprocessos
Objetivo: Salvar log de processos executados para gerar estatísticas
Meio: API que recebe os dados e faz a gravação em banco local


# 1) Cenário
    Dois servidores que executam diversos scripts via cron
    Um servidor web que atende uma aplicação empresarial (SIG)
        e que alguns processos registram lentidão ocasional
    Descartada a possibilidade de conflito causado pela execuções simultâneas
    Considerado mais provável excesso de carga em determinados momentos

# 2) Proposta de solução
    Desenvolver em um dos servidores uma aplicação que forneça uma API
    na qual todos os 3 servers acima possam fazer uma chamada simples
    ao final de cada script executado ou ao final de cada chamada web crítica
    para gravar o processo executado, o horário de início e o horário de término
    Isso permitirá depois gerar estatísticas,
        para cada processo no log,
        qual a média de tempo de execução e quais os casos de desvio da média
        e nesses casos de desvio verificar quais outros processos
        estavam executando simultaneamente
        e assim focar em quais os processos mais problemáticos primeiro
        ao invés de ficar caçando a esmo a origem das lentidões

# 3) Desenvolvimento
# 3.1) Ambiente
    Banco Mysql rodando em docker
        - como vai precisar de estatísticas depois, é melhor ser um banco relacional
            porque vai precisar analisar dados agrupados
    Ambiente PHP aceitando chamadas de POST
        com json de dados para gravação

# 3.2) Modelagem BD
    Precisa de uma tabela principal que vai gravar as execuções
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

# 3.3 ) Execução
    O endpoint será o savelog.php
    chamada será http://qualquercoisa/savelog.php?fn=savelog
                e os dados virão por post
            dados será um array simples
                ['machine_id'] = integer
                ['machine_pass'] = 'texto'
                ['process_pai'] = 'texto'
                ['process_filho] = 'texto'
                ['dhinicio'] = 'YYYY-MM-DD HH:II:SS.msms'
                ['dhfim] = 'YYYY-MM-DD HH:II:SS.msms'                
    primeiro valida o machine id/pass
    depois verifica a existência ou cadastra os dois processos
    depois salva os dados na tabela de executions
    
    
        
