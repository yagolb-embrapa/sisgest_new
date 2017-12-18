<?php 

$qtd_abas = 0;
//require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<span class='titulo'>Estagiários que não participaram do PIEC</span><br>
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
	$query_piec = "SELECT * FROM estagiarios WHERE participou_piec = 'N' AND status = 1 ORDER BY nome";
	$result_piec = sql_executa($query_piec);	
	
	
	if(sql_num_rows($result_piec)>0){
	echo "<table  width='100%' class='formulario'>						
			<tr>
				<th style='text-align:center;' width='303'>
					Nome
			</th>
			<th style='text-align:center;' width='350'>
					E-mail
			</th>	
			<th	 style='text-align:center;' width='40'>
				Ramal	
			</th>			
			<th style='text-align:center;' width='40'>
				Supervisor
			</th>
			<th style='text-align:center;' width='40'>
				Início
			</th>
		</tr>";
		$classe = "spec";
		while ( $campo = sql_fetch_array($result_piec) ){
			//pega o nome do supervisor, pois temos o id 
			$query_superv = "SELECT * FROM supervisores WHERE id = {$campo['id_supervisor']} ";
			$res_superv = sql_executa($query_superv);
			$superv = sql_fetch_array($res_superv);				
			$classe = ($classe == "specalt")?"spec":"specalt";						
			echo "<tr class='{$classe}'>
					<td align='left' width='30%'>
					<span >{$campo['nome']}</span>
				</td>
				<td align='left' width='20%'>						
					<span >";
					//coloca o email embrapa, mas se nao tiver, coloca o email pessoal
					if(empty($campo['email_embrapa'])) 
						echo $campo['email'];
					else echo $campo['email_embrapa'];
					echo "</span>
				</td>
			<td	 align='center' width='10%'>
						<span >";
					echo (empty($campo['ramal']))?"<i>Indefinido</i>":$campo['ramal']; 
				echo "</span>
				</td>						
				<td align='left' width='30%'>
					<span >{$superv['nome']}</span>
				</td>
			<td align='left' width='30%'>
					<span >".formata($campo['vigencia_inicio'],'redata')."</span>
				</td>
			</tr>";				
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Todos os estagiários participaram do PIEC.</span></td>
			</tr>
		</table>";	
  } 
}
 
?>	
	           
  </table>
  
    
<?php

echo "
<table width='100%' style='border:0px;'cellspacing='0' cellpadding='0'>
<tr><td>&nbsp;</td></tr>
<tr align='center'><td>
<a onclick=\"window.open('relatorio.piec.print.php','relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');\" style=\"color: rgb(0, 0, 255); font-size: 14px;\" href=\"javascript://\">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>

</table>";


?>

