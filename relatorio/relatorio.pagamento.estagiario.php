<?php
/*var_dump($_POST);*/

require_once("../classes/DB.php");
include("../functions/functions.database.php");//temporario 

function calcDescontos($remun,$dias,$tipo){
	
	switch ($tipo){		
		case 0:
			$ret = ($remun/30)*$dias;
			break;
		case 1:
			$ret = (132/22)*$dias;
			break;
		case 2:
			$ret = ($remun/30)*($dias*2.5);
			break;
		default:
			$ret = 0;
	}
	
	return $ret;	
}

?>
<html>
<head><title>Folha de pagamento estagiarios</title></head>
<body>
<center>
<table cellpadding="0" cellspacing="0" style="font-size: 8pt; font-family: arial; top: 0; width:283mm; margin:0;">
<tr>
<td colspan="5"><center><img border="0" src="../img/embrapa_hd.jpg" style="height: 2cm; width: 70%"/></center></td>
<td colspan="7" style="text-align: center; padding-bottom:1mm; vertical-align: bottom;">FOLHA DE PAGAMENTO ESTAGIÁRIOS</td>
<td colspan="3"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td style="width: 1.8%; border-bottom: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
<td style="width: 18.4%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">NOME</td>  
<td style="width: 8.2%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">CPF</td>  
<td style="width: 3.5%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center; font-size: 6pt;">HORAS</td>  
<td style="width: 9.9%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">SUP.</td>  
<td style="width: 9.9%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">SUBPROJETO</td>  
<td style="width: 5.3%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">BOLSA</td>
<td style="width: 4.6%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">FERIAS</td>
<td style="width: 4.6%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">VT</td>
<td style="width: 5.3%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">LÍQUIDO</td>
<td style="width: 4.6%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">BANCO</td>
<td style="width: 5.3%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">AGÊNCIA</td>
<td style="width: 6.7%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">CTA.COR.</td>
<td style="width: 6%; border: 1px solid black; border-left: 0; background-color: #CCCCCC; font-weight: bold; text-align: center;">OBS.</td>
</tr>
<?php 

$ct = 1;
$total = 0;
for ($i=0; $i<sizeof($_POST["idEstagiario"]); $i++){
	
	if (($_POST['forigem']==-1 || $_POST['forigem']==$_POST["origem"][$i])&&(in_array($_POST["idEstagiario"][$i], $_POST["finalList"]))){
	$id = $_POST["idEstagiario"][$i];
	$ido = $_POST["origem"][$i];
	$sql = "SELECT e.id, 
	e.nome, 
	e.cpf, 
	e.carga_horaria horas, 
	s.nome supervisor, 
	case when e.numero_projeto='' then e.nome_projeto else e.numero_projeto end projeto, 
	e.remuneracao bolsa,
	b.codigo_banco banco,
	e.agencia, 
	e.conta_corrente cc,
	e.vale_transporte vt,
	o.origem  
	FROM estagiarios e inner join supervisores s on (s.id=e.id_supervisor) 
	LEFT join bancos b on (e.id_banco=b.id) 
	inner join origens_recursos o on (o.id={$ido}) 
	where e.status = 1 and e.id={$id} ORDER BY e.nome";
	DB::execute($sql);
	$estagiario = DB::fetch();
	
	
	echo "<tr style=\"page-break-after: auto;\">\n";
	echo "<td style=\"border-right: 1px solid black; font-size:6pt;\">{$estagiario["origem"]}</td>\n";		
	echo "<td style=\"font-size: 8pt; text-align:center; border: 1px solid black; border-left:0; border-top:0;\">".$ct."</td>\n";
	echo "<td style=\"font-size: 8pt; border: 1px solid black; border-left:0; border-top:0;\">".$estagiario['nome']."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".$estagiario['cpf']."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".$estagiario['horas']."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".$estagiario['supervisor']."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">";
	if ($estagiario['projeto']=='')
		echo '&nbsp;';
	else{
		if (strlen($estagiario['projeto'])>35)
			$estagiario['projeto'] = substr($estagiario['projeto'], 0, 35)."...";
		echo $estagiario['projeto'];
	} 
	echo "</td>\n";
	$descontoBolsa = $estagiario['bolsa']-calcDescontos($estagiario['bolsa'], $_POST['diasDescontar'][$i], 0);
	//$valorRecesso = calcDescontos($estagiario['bolsa'], $_POST['diasRecesso'][$i], 2);
	$valorRecesso = str_replace(",",".",$_POST['diasRecesso'][$i]);
	$valorVT = 132-calcDescontos($estagiario['vt'], $_POST['diasDescontarVT'][$i], 1);
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".number_format($descontoBolsa,2,',','.')."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".number_format($valorRecesso,2,',','.')."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".number_format($valorVT,2,',','.')."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">".number_format(($descontoBolsa+$valorRecesso+$valorVT),2,',','.')."</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">";
	if ($estagiario['banco']!='' && $estagiario['banco']<10)	echo '00';
	else if ($estagiario['banco']!='' && $estagiario['banco']<100)	echo '0';
	echo ($estagiario['banco']!='')?$estagiario['banco']:'&nbsp';
	echo "</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">";
	echo ($estagiario['agencia']!='')?$estagiario['agencia']:'&nbsp;';
	echo "</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">";
	echo ($estagiario['cc']=='')?'&nbsp;':$estagiario['cc'];
	echo "</td>\n";
	echo "<td style=\"border: 1px solid black; border-left:0; border-top:0; text-align:center;\">&nbsp;</td>";
	echo "</tr>\n";
	$ct++;
	$total += ($descontoBolsa+$valorRecesso+$valorVT);
	
	}
}
?>
<tr>
<td>&nbsp;</td>
<td colspan=14 style="border-bottom: 1px solid black; text-align: center; font-weight: bold; font-style: italic; font-size: 10pt;">&nbsp;</td>
</tr>
<tr>
<td style="border-right: 1px solid black;">&nbsp;</td>
<td colspan=9 style="border-bottom: 1px solid black; text-align: right; padding-right: .3em; font-weight: bold;">Total</td>
<td style="border-bottom: 1px solid black; text-align: center;"><?php echo number_format($total,2,",","."); ?></td>
<td colspan=4 style="border-right: 1px solid black; border-bottom: 1px solid black;">&nbsp;</td>
</tr>
</table>
<br/>
<a href="javascript://" onclick="print();" style="text-decoration: none;">Imprimir <img src="../img/icone_impressora.gif" border=0 /></a>
</center>
</body>
</html>
