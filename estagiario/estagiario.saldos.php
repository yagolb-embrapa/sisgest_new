<?php
require_once("../classes/DB.php");

extract($_POST);

$query = "  SELECT   ano,
                     saldo
            FROM     saldos
            WHERE    id_estagiario = {$id_estagiario} and ano = {$ano};";
$novo_saldo = DB::fetch_all($query);

if(sizeof($novo_saldo) == 0){
    $query = " SELECT to_char(SUM(horas_trabalhadas),'HH24:MI') AS horas_trab
               FROM   frequencias
               WHERE  to_char(periodo, 'YYYY-MM-DD') LIKE '{$ano}%' and id_estagiario={$id_estagiario};";
    $horas_trab = DB::fetch_all($query);

    $JSON['horas_trab'] = $horas_trab[0]['horas_trab'];

    $query = "  INSERT INTO saldos(id_estagiario, ano, saldo, horas_trabalhadas)
                VALUES({$id_estagiario}, {$ano}, '{$saldo}', '{$horas_trab[0]['horas_trab']}');";
    
    $JSON['status'] = (DB::execute($query)) ? 'sucesso' : 'erro ao inserir query';
}
else if($saldo != $novo_saldo[0]['saldo']) {
    $query = " SELECT to_char(SUM(horas_trabalhadas),'HH24:MI') AS horas_trab
               FROM   frequencias
               WHERE  to_char(periodo, 'YYYY-MM-DD') LIKE '{$ano}%' and id_estagiario={$id_estagiario};";
    $horas_trab = DB::fetch_all($query);

    $JSON['horas_trab'] = $horas_trab[0]['horas_trab'];


    $query .= " UPDATE  saldos
                SET     saldo = '$saldo', horas_trabalhadas = '{$horas_trab[0]['horas_trab']}'
                WHERE   id_estagiario = {$id_estagiario} and ano = {$ano};";

    $JSON['status'] = (DB::execute($query)) ? 'sucesso' : 'erro no update';
}
else
    $JSON['status'] = 'sucesso';

echo json_encode($JSON);
?>
