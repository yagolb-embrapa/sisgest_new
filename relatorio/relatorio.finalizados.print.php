<?php 

$qtd_abas = 0;
require_once("../sessions.php");
if(!$_SESSION["USERID"]){
	echo "<script language='javascript'> window.location.href='../login.php'; </script>";


}

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>
<html>
<head>
	<title>SisGest - Embrapa Informática Agropecuária</title>
<meta http-equiv="content-type" content="="text/ht; charset=UTF-8" >

	<link href="../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/style.form.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css" />
	<link href="../css/abas.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript" src="../js/masks.js"></script>	
    <script language="javascript" src="../js/TAjax.js"></script>	
</head>
<body>
    <div align="center">
    <table width="800" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >

<!-- TR de CONTEUDO -->  
<tr>
  <td width='800' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo' align='center'>
		<span class='tituloMaior'>SisGest - Sistema Gerenciador de Estágios</span><br><br>
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
$query = "SELECT su.nome as superv, es.nome as nome, es.vigencia_inicio, es.vigencia_fim, pc.data, es.id_instituicao_ensino, ins.razao_social as inst 
FROM pedidos_contas pc 
INNER JOIN estagiarios es ON es.id = pc.id_estagiario 
INNER JOIN supervisores su ON es.id_supervisor = su.id
INNER JOIN instituicoes_ensino ins ON es.id_instituicao_ensino = ins.id 
WHERE pc.tipo = 'F' AND pc.data > {$timei} AND pc.data < {$timef} ORDER BY nome";

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
		</tr>";
	$classe = "spec";	  	
  	while ( $campo = sql_fetch_array($result) ){
		$vigi = explode("-",$campo['vigencia_inicio']);
		$vigf = explode("-",$campo['vigencia_fim']);  		
  		
		$classe = ($classe == "specalt")?"spec":"specalt";
  		//imprime resultados
  		  echo "<tr class='{$classe}'>
			<td align='center' width='25%'>						
				<span >{$campo['nome']}</span>
			</td>			
			<td align='left' width='25%'>
				<span >{$campo['superv']}</span>
			</td>
			<td align='left' width='25%'>
				<span >{$campo['inst']}</span>
			</td>
			<td align='left' width='15%'>
				<span > {$vigi[2]}/{$vigi[1]}/{$vigi[0]} a  {$vigf[2]}/{$vigf[1]}/{$vigf[0]}</span>
			</td>
			<td align='left' width='10%'>
				<span >".date('d/m/Y',$campo['data'])."</span>
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
	           
  </table>
  
    
  </div></div>
 </div> 
</div>
<?php

echo "
  </td>
</tr>
</table>
";

 
include_once('../inc/copyright.php');

echo "<script language='javascript'>window.print();</script>";
?>
</div>
