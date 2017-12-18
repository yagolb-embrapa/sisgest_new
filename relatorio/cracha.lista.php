<?php 

$qtd_abas = 0;
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<span class='titulo'>Relação de Crachás de Estagiários</span><br>
		<span class='subtitulo'><?php echo date("d/m/Y"); ?></span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
<?php

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>";	
	
}else{
	$query_cracha = "SELECT * FROM estagiarios  WHERE status = 1 ORDER BY cracha";
	$result_cracha = sql_executa($query_cracha);	
	
	
	if(sql_num_rows($result_cracha)>0){
		echo "<table  width='100%' class='formulario'>						
			<tr>
			<th style='text-align:center;' width='350'>
					Número Crachá
			</th>
			<th style='text-align:center;' width='303'>
					Nome
			</th>
		</tr>";
		$classe = "spec";
		while ( $campo = sql_fetch_array($result_cracha) ){
			$classe = ($classe == "specalt")?"spec":"specalt";	
			echo "<tr class='{$classe}'>
				<td align='center' width='30%'>						
					<span >{$campo['cracha']}</span>
				</td>
				
					<td align='left' width='70%'>
					<span >{$campo['nome']}</span>
				</td>
			</tr>";				
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Não foram encontrados estagiários cadastrados no sistema.</span></td>
			</tr>
		</table>";	
  } 
}
 
?>	
	           
  </table>

<table width='100%' style='border:0px;' cellspacing='0' cellpadding='0'>  
<tr><td>&nbsp;</td></tr>
<tr align='center'><td>
<a onclick="window.open('relatorio.crachas.print.php','relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
</table>
