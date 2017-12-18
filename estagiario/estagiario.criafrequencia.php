<?php
require_once("../classes/DB.php");

extract($_POST);

list($mes, $ano) = explode('/',$periodo);
$periodo = $ano . '-' . $mes . '-' . '01';

$query = "SELECT id
          FROM   frequencias
          WHERE  id_estagiario = {$id_estagiario}
                 AND periodo = '{$periodo}';";
$result = DB::fetch_all($query);


if(sizeof($result)!=0) {
    $JSON['status'] = 'erro';
    $JSON['mensagem'] = "Já existe frequência no período: {$mes}/{$ano} para este estagiário, faça a alteração clicando no horário da tabela\n";
}
else {
    if($horas_mes == '') {
        $query = "INSERT INTO frequencias(id_estagiario, periodo, horas_mes, horas_trabalhadas)
                  VALUES({$id_estagiario}, '{$periodo}', NULL, '{$horas_trabalhadas}');";
        $horas_mes = '-';
    }
    else if($horas_trabalhadas == '') {
        $query = "INSERT INTO frequencias(id_estagiario, periodo, horas_mes, horas_trabalhadas)
                  VALUES({$id_estagiario}, '{$periodo}', '{$horas_mes}', NULL);";
        $horas_trabalhadas = '-';
    }
    else {
        $query = "INSERT INTO frequencias(id_estagiario, periodo, horas_mes, horas_trabalhadas)
                  VALUES({$id_estagiario}, '{$periodo}', '{$horas_mes}', '{$horas_trabalhadas}');";
    }

    $JSON['mensagem'] = $query;

    if(!DB::execute($query)) {
        $JSON['status'] = 'erro';
        $JSON['mensagem'] = "Nao foi possivel adicionar a frequencia.\n" . $query;
    }
    else {
        $JSON['status'] = 'sucesso';
        $JSON['mensagem'] = "Frequencia Adicionada!";
    }
}

if($JSON['status'] == 'sucesso') {
    $query = "SELECT id
              FROM   frequencias
              WHERE  id_estagiario = {$id_estagiario}
                     AND periodo = '{$periodo}';";
    $result = DB::fetch_all($query);

    $id = $result[0]['id'];

    $meses = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    $JSON['html'] = "                           <td align='left' id='{$id}' name='{$mes}'>{$meses[(int)$mes]}</td><td>{$horas_mes}</td><td>{$horas_trabalhadas}</td><td></td>";
}


echo json_encode($JSON);

?>

