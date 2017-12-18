<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");

	$cor = true;
			
	if (!$_GET["i"] || !$_GET["f"]){
		//msg de erro
		return;			
	}		 	?>
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}
</style>
<span class='titulo'>Estágios Finalizados no Período de </span><br>
		<span class='subtitulo'><?php echo $_GET["i"]." a ".$_GET["f"]; ?></span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
<?php	

$datai = explode('/',$_GET["i"]);
$dataf = explode('/',$_GET["f"]);	
$timei = mktime(0,0,0,$datai[1],$datai[0],$datai[2]);
$timef = mktime(0,0,0,$dataf[1],$dataf[0],$dataf[2]);

/* Por enquanto pega na tabela de pedidos de finalizacao, mas posteriormente pegar de uma tabela
 que marca a finalizacao propriamente dita, vinda de uma pagina de finalizacao ou mudanca de status do estag*/
$query = "SELECT es.id as idestag, es.tipo_vinculo, su.nome as superv, es.nome as nome, es.vigencia_inicio, es.vigencia_fim, pc.data, es.id_instituicao_ensino, ins.razao_social as inst 
FROM pedidos_contas pc 
INNER JOIN estagiarios es ON es.id = pc.id_estagiario 
INNER JOIN supervisores su ON es.id_supervisor = su.id
INNER JOIN instituicoes_ensino ins ON es.id_instituicao_ensino = ins.id 
WHERE pc.tipo = 'F' AND pc.data > {$timei} AND pc.data < {$timef} ORDER BY tipo_vinculo, nome";

$result = sql_executa($query);	

//Se houver algum estagio finalizado no periodo..
if(sql_num_rows($result)>0){ 		
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
			<th style='text-align:center;' width='303'>
					Finalizado em
			</th>
			<th style='text-align:center;' width='303'>
					Vinculo
			</th>
		</tr>";
	$classe = "spec";	  	
  	while ( $campo = sql_fetch_array($result) ){
		$vigi = explode("-",$campo['vigencia_inicio']);
		$vigf = explode("-",$campo['vigencia_fim']);
  		$classe = ($classe == "specalt")?"spec":"specalt";	
  		//imprime resultados
  		  echo "<tr class='{$classe}'>
			<td align='center' width='25%'>						
				<span >{$campo['nome']}</span> <a href='javascript://' onclick=\"document.location.href='../estagiario/estagiario.visualizacao.php?id=".$campo['idestag']."';\">
				<img src='../img/icone_lupa.png' width='16' height='16' border='0' />
			</td>			
			<td align='left' width='25%'>
				<span >{$campo['superv']}</span>
			</td>
			<td align='left' width='25%'>
				<span >{$campo['inst']}</span>
			</td>
			<td align='left' width='10%'>
				<span > {$vigi[2]}/{$vigi[1]}/{$vigi[0]} a  {$vigf[2]}/{$vigf[1]}/{$vigf[0]}</span>
			</td>
			<td align='left' width='10%'>
				<span >".date('d/m/Y',$campo['data'])."</span>
			</td>
			<td align='left' width='5%'>
				<span >";
  		  echo ($campo['tipo_vinculo']=='e')?"Estágio":"Bolsa";
  		  echo "</span>
			</td>
		</tr>";
   }	
}else{	
	//Nenhum estagio finalizado no periodo		
	echo "<table width='752px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#F5FAFA'>
				<td align='center'><span align='center' style='color:black;'>
				Não foram encontrados estágios finalizados no período selecionado.</span></td>";
	echo "</tr>
		</table>";
}
		
?>

<table>
</tr><tr><td>&nbsp;</td></tr>
<tr align="center"><td>
<a id="linkImprimir" onclick="imprimeFinalizados('vigenciai', 'vigenciaf');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
<tr>
</table>