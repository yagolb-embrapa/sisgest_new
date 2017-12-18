<?php
require_once("../classes/DB.php");

extract($_POST);

//$JSON['mensagem'] = $id_estagiario . ' ' . $id_frequencia . ' ' . $mes . '/' . $ano;
$JSON['status'] = 'sucesso';
$JSON['codigo'] = 'alteracao';


$meses = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

$omes = array_search($mes, $meses);
if($omes != false)
    $periodo = $ano . '-' . $omes . '-' . '01';
else
    $periodo = $ano . '-' . $mes . '-' . '01';

//Se nao tiver id_frequencia, adiciona no bd
if($id_frequencia == ''){
    $query = "INSERT INTO frequencias(id_estagiario, periodo, {$campo})
              VALUES({$id_estagiario}, '{$periodo}','{$valor}');";

    $JSON['mensagem'] = $query;

    if(!DB::execute($query)){
        $JSON['status'] = 'erro';
        $JSON['mensagem'] = "Erro ao inserir frequencia\n" . $query;
    }
    else{
        $query = "SELECT id
                  FROM   frequencias
                  WHERE  id_estagiario = {$id_estagiario}
                         AND periodo = '{$periodo}'";
        $id_frequencia = DB::fetch_all($query);
        $JSON['status'] = 'sucesso';
        //Retorna o id para inserir o id novo na tabela
        $JSON['codigo'] = $id_frequencia[0]['id'];
    }
}
//Se tiver, faz atualizacao
else {
    $query = "UPDATE      frequencias
              SET         {$campo} = '{$valor}'
              WHERE       id_estagiario = {$id_estagiario}
                          AND periodo = '{$periodo}';";
    $JSON['mensagem'] = $query;

    if(!DB::execute($query)){
        $JSON['status'] = 'erro';
        $JSON['mensagem'] = "Erro ao alterar frequencia\n" . $query;
    }
    else {
        $JSON['status'] = 'sucesso';
        $JSON['codigo'] = 'alteracao';
    }

}

echo json_encode($JSON);

?>
