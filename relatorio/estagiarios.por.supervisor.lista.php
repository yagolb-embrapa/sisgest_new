<?php 

$qtd_abas = 0;
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../classes/DB.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<div align='center'>
    <span class='titulo'>Relação de Estagiários por Supervisor</span><br>
    <span class='subtitulo'><?php echo date("d/m/Y"); ?></span>
</div>
<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
<?php

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>";	
	
}else{
    $query = "SELECT su.nome AS sup_nome,
                     es.nome AS est_nome
                     FROM   estagiarios AS es
                     INNER JOIN supervisores su ON es.id_supervisor = su.id
                     WHERE status = 1 AND es.tipo_vinculo = 'e'
                     ORDER BY su.nome, es.nome;";
	$result = sql_executa($query);	
	
	if(sql_num_rows($result)>0){
		echo "<table  width='100%' class='formulario'>						
			<tr>
			<th style='text-align:center;' width='350'>
					Supervisor
			</th>
			<th style='text-align:center;' width='300'>
					Estagiário
			</th>
			<th style='text-align:center;' width='30'>
					Quantidade
			</th>
		</tr>";
		$classe = "spec";
        $qtde = 1;
		while ( $campo = sql_fetch_array($result) ){
            if($ultimo_supervisor != $campo['sup_nome']){
                $classe = ($classe == "specalt")?"spec":"specalt";	
                $qtde = 1;
            }
			echo "<tr class='{$classe}'>
				<td align='left' width='45%'>
					<span >{$campo['sup_nome']}</span>
				</td>
			    <td align='left' width='45%'>
					<span >{$campo['est_nome']}</span>
				</td>
			    <td align='center' width='5%'>
					<span >{$qtde}</span>
				</td>
			</tr>";				
            $ultimo_supervisor = $campo['sup_nome'];
            $qtde++;
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Não foram encontrados estagiários cadastrados no sistema.</span></td>
			</tr>
		</table>";	
    } 
    echo "</table><br>
            <div align='right'> Total = <strong>" . sql_num_rows($result) . " estagiários. </strong></div>";
}
 
?>
	           
</table>

<table width='100%' style='border:0px;' cellspacing='0' cellpadding='0'>  
<tr><td>&nbsp;</td></tr>
<tr align='center'><td>
<a onclick="window.open('relatorio.estagiarios.por.supervisor.print.php','relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
</table>
