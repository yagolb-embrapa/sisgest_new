<?php
require_once("../classes/DB.php");

extract($_POST);

$JSON['status'] = 'sucesso';
//Header da tabela
$JSON['frequencia'] =  "\n                    <thead>
                        <tr>\n";
$JSON['frequencia'] .= "                           <th style='text-align:center;'><span>Mês</span></th>";
$JSON['frequencia'] .= "<th style='text-align:center;'><span>Horas Planejadas (hh:mm)</span></th>";
$JSON['frequencia'] .= "<th style='text-align:center;'><span>Horas Trabalhadas (hh:mm)</span></th>\n";
$JSON['frequencia'] .= "<th style='text-align:center;'><span>Saldo no Ano (hh:mm)</span></th>\n";
$JSON['frequencia'] .= "                        </tr>
                    </thead>
                    <tbody>\n";

//Busca os anos que o estagiário trabalhou
$query = "  SELECT id,
                   periodo,
                   to_char(horas_mes, 'HH24:MI') AS horas_mes,
                   to_char(horas_trabalhadas, 'HH24:MI') AS horas_trabalhadas,
                   horas_mes-horas_trabalhadas AS saldo
            FROM   frequencias
            WHERE  id_estagiario={$_POST['id']}  and  (extract(year from periodo)) = {$ano}";
$frequencias = DB::fetch_all($query);

//Deixa o mês como indice do vetor
foreach($frequencias as $freq) {
   list($ano,$mes,$dia) = explode('-', $freq['periodo']); 
   $dados[(int)$mes] = $freq;
}

$meses = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

$saldo = "00:00";
//Preenche a tabela dos meses com as horas a cumprir e trabalhadas
for($i=1;$i<=12;$i++) {
    $classe = ($classe == "alt")?"noalt":"alt";	
    $mes = ($i < 10) ? '0'.$i : $i;
    $JSON['frequencia'] .= "                        <tr align='center' class='{$classe}'>\n";
    $JSON['frequencia'] .= "                            <td align='left' id='{$dados[$i]['id']}' name='{$mes}'>{$meses[$i]}</td>"; //Adiciona o id da frequencia que corresponde a linha

    $horas_mes = isset($dados[$i]['horas_mes']) ? $dados[$i]['horas_mes'] : '-';
    $horas_trabalhadas = isset($dados[$i]['horas_trabalhadas']) ? $dados[$i]['horas_trabalhadas'] : '-';

    $JSON['frequencia'] .= "<td>{$horas_mes}</td>";
    $JSON['frequencia'] .= "<td>{$horas_trabalhadas}</td>";

    $horas_mes = ($horas_mes == '-') ? '00:00' : $horas_mes;
    $horas_trabalhadas = ($horas_trabalhadas == '-') ? '00:00' : $horas_trabalhadas;

    $JSON['frequencia'] .= "<td></td>\n";
    $JSON['frequencia'] .= "                        </tr>\n";
}
$JSON['frequencia'] .= "                    </tbody>\n";

$anterior = $ano-1;

$query = "  SELECT to_char(SUM(saldo),'HH24:MI') AS saldo, to_char(sum(horas_trabalhadas),'HH24:MI') AS horas_trab
            FROM   saldos
            WHERE  id_estagiario={$_POST['id']} AND ano <= {$anterior};";
$saldos = DB::fetch_all($query);

if($saldos[0]['saldo'] == null)
    $JSON['saldo_anterior'] = '00:00';
else{
    //$saldo_anterior = explode(':', $saldos[0]['saldo']);
    $JSON['saldo_anterior'] = $saldos[0]['saldo'];
}

if($saldos[0]['horas_trab'] == null)
    $JSON['horas_trab_anterior'] = '00:00';
else{
    //$horas_trab = explode(':', $saldos[0]['horas_trab']);
    $JSON['horas_trab_anterior'] = $saldos[0]['horas_trab'];
}

echo json_encode($JSON);
?>

