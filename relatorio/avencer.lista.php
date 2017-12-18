<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");

	$cor = true;
			
	if (!$_GET["i"] || !$_GET["f"]){
		//msg de erro
		return;			
	}
			 	?>
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}
</style>
<span class='titulo'>Contratos a vencer no Período de </span><br>
		<span class='subtitulo'><?php echo $_GET["i"]." a ".$_GET["f"]; ?></span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
<?php	

$datai = explode('/',$_GET["i"]);
$dataf = explode('/',$_GET["f"]);	
$timei = mktime(0,0,0,$datai[1],$datai[0],$datai[2]);
$timef = mktime(0,0,0,$dataf[1],$dataf[0],$dataf[2]);

/*Selecionando dados do estagiario (codigo antigo)
$query = "SELECT est.id,est.nome,est.vigencia_inicio,est.vigencia_fim,sup.nome as superv,inst.razao_social as instit 
			FROM estagiarios est  
			INNER JOIN supervisores sup ON sup.id = est.id_supervisor
			INNER JOIN instituicoes_ensino inst ON inst.id = est.id_instituicao_ensino 
			WHERE status = 1";*/
$query = "SELECT est.id,
est.nome,
est.vigencia_inicio,
CASE WHEN (EXISTS(select * from termos_aditivos AS ta where ta.id_estagiario=est.id)) THEN (select data_fim from termos_aditivos as ta where ta.id_estagiario=est.id order by data_fim desc limit 1)
ELSE est.vigencia_fim
END as vigencia_fim,
case when (EXISTS(select * from termos_aditivos AS ta where ta.id_estagiario=est.id)) THEN 1
else 0 end as aditivo,
sup.nome as superv,
inst.razao_social as instit

FROM estagiarios est  
			INNER JOIN supervisores sup ON sup.id = est.id_supervisor
			INNER JOIN instituicoes_ensino inst ON inst.id = est.id_instituicao_ensino 
			WHERE status = 1";
$result = sql_executa($query);	
	
/*Recuperando dados e tratando-os*/	
if(sql_num_rows($result)>0){	

  while ( $campo = sql_fetch_array($result) ){
  		$anoi = substr($campo['vigencia_inicio'],0,4);
  		$mesi = substr($campo['vigencia_inicio'],5,2);
  		$diai = substr($campo['vigencia_inicio'],-2);
  		  			  		
  		$anof = substr($campo['vigencia_fim'],0,4);
  		$mesf = substr($campo['vigencia_fim'],5,2);
  		$diaf = substr($campo['vigencia_fim'],-2);
  		
  		$ids[] = $campo['id'];  			
		$nomes[] = $campo['nome'];
		$inicios[] = mktime(0,0,0,$mesi,$diai,$anoi);
		$fins[] = mktime(0,0,0,$mesf,$diaf,$anof);
		$insts[] = $campo['instit'];
		$supervs[] = $campo['superv'];
		$tas[] = $campo['aditivo'];
  		
  }	  
  /*Ordena um vetor e rearranja o chaveamento dos outros em função desse*/	  
  array_multisort($ids, $nomes, $inicios, $fins, $insts, $supervs,$tas);		
    $count_ok = 0;
  
  //Imprime uma linha com cada estagiario dentro do periodo desejado 
  for($i=0;$i<count($ids);$i++){  		
  	 if($count_ok == 0 && $fins[$i] >= $timei && $fins[$i] <= $timef){  	     	 	  
    	echo "<table  width='100%' class='formulario'>						
			<tr>
			<th style='text-align:center;' width='350'>
					Estagiário
			</th>
			<th style='text-align:center;' width='303'>
					Supervisor
			</th>
			<th style='text-align:center;' width='303'>
					Instituição
			</th>
			<th style='text-align:center;' width='303'>
					Vigência
			</th>
		</tr>";
		$classe = "spec";
		$classe = ($classe == "specalt")?"spec":"specalt";
		echo "<tr class='{$classe}'>
			<td align='center' width='25%'>						
				<span >{$nomes[$i]}</span>
			</td>			
			<td align='left' width='25%'>
				<span >{$supervs[$i]}</span>
			</td>
			<td align='left' width='25%'>
				<span >{$insts[$i]}</span>
			</td>
			<td align='left' width='25%'>
				<span >";
		if ($tas[$i]==1) echo "<i>";
		echo date("d/m/Y",$inicios[$i])." a ".date("d/m/Y",$fins[$i]);
		if ($tas[$i]==1) echo "*</i>";
		echo "</span>
			</td>
		</tr>";
		$count_ok = 1;
	 }elseif($count_ok > 0 && $fins[$i] >= $timei && $fins[$i] <= $timef){	 	
		$classe = ($classe == "specalt")?"spec":"specalt";
	 	echo "<tr class='{$classe}'>
			<td align='center' width='25%'>						
				<span >{$nomes[$i]}</span>
			</td>			
			<td align='left' width='25%'>
				<span >{$supervs[$i]}</span>
			</td>
			<td align='left' width='25%'>
				<span >{$insts[$i]}</span>
			</td>
			<td align='left' width='25%'><span >";
	 	if ($tas[$i]==1) echo "<i>";
	 	echo date("d/m/Y",$inicios[$i])." a ".date("d/m/Y",$fins[$i]);
	 	if ($tas[$i]==1) echo "*</i>";
	 	echo "</span></td>
		</tr>";
	 }	
  }
}else{
	
	//Nenhum estagio a vencer no periodo		
	echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#F5FAFA'>
				<td align='center'><span align='center' style='color:black;'>
				Não foram encontrados estágios finalizados no período selecionado.</span></td>";
	echo "</tr>
		</table>";
}
?>
<table>
<div style="width:700px; text-align: right;"><i>*Termo aditivo</i></div>
</tr><tr><td>&nbsp;</td></tr>
<tr align="center"><td>
<a id="linkImprimir" onclick="imprimeAVencer('vigenciai', 'vigenciaf');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
<tr>
</table>

