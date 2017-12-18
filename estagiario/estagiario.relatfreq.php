<?php
require_once("../classes/DB.php");

extract($_POST);

$JSON['status'] = 'sucesso';
//Header da tabela
$JSON['tabela'] =  "\n                    <thead>
                        <tr>\n";
$JSON['tabela'] .= "                           <th style='text-align:center;'><span>Mês</span></th>";
$JSON['tabela'] .= "<th style='text-align:center;'><span>Entregou Relatório</span></th>\n";
$JSON['tabela'] .= "                        </tr>
                    </thead>
                    <tbody>\n";

//Busca as frequencias do estagiario no ano
$query = "  SELECT id,
                   periodo,
                   entregou_relatorio
            FROM   frequencias
            WHERE  id_estagiario={$id}  and  (extract(year from periodo)) = {$ano}";
$frequencias = DB::fetch_all($query);

//Deixa o mês como indice do vetor
foreach($frequencias as $freq) {
   list($ano,$mes,$dia) = explode('-', $freq['periodo']); 
   $dados[(int)$mes] = $freq;
}

$meses = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

//Preenche a tabela dos meses com checkbox dos relatórios que foram entregues
for($i=1;$i<=12;$i++) {
    $classe = ($classe == "alt")?"noalt":"alt";	
    $mes = ($i < 10) ? '0'.$i : $i;
    $JSON['tabela'] .= "                        <tr align='center' class='{$classe}'>\n";
    $JSON['tabela'] .= "                            <td align='left' id='id{$dados[$i]['id']}' name='{$mes}'>{$meses[$i]}</td>"; //Adiciona o id da frequencia que corresponde a linha

    $entregou = ($dados[$i]['entregou_relatorio'] == 't') ? 'checked' : '';

    $JSON['tabela'] .= "<td><input id='id{$dados[$i]['id']}' name='$meses[$i]' type='checkbox' {$entregou}></td>\n";
    $JSON['tabela'] .= "                        </tr>\n";
}
$JSON['tabela'] .= "                    </tbody>\n";

echo json_encode($JSON);
?>

