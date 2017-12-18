<?php 

$qtd_abas = 0;
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../classes/DB.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<div align='center'>
    <span class='titulo'>Relação de Bolsistas por Modalidade</span><br>
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
                     es.nome AS est_nome,
                     mb.nome AS mb_nome
                     FROM   estagiarios AS es
                     INNER JOIN supervisores su ON es.id_supervisor = su.id
                     INNER JOIN modalidades_bolsista mb ON es.id_bolsista = mb.id
                     WHERE status = 1 AND tipo_vinculo='b'
                     ORDER BY mb.nome, su.nome, es.nome;";
	$result = sql_executa($query);	
	
	if(sql_num_rows($result)>0){
		echo "<table  width='100%' class='formulario'>						
			<tr>
			<th style='text-align:center;' width='30'>
                    Modalidade do Bolsista
            </th>
			<th style='text-align:center;' width='300'>
					Bolsista
			</th>
			<th style='text-align:center;' width='350'>
					Supervisor
			</th>
			<th style='text-align:center;' width='30'>
					Quantidade
			</th>
		</tr>";
		$classe = "spec";
        $qtde = 1;
		while ( $campo = sql_fetch_array($result) ){
            if($ultima_modalidade != $campo['mb_nome']){
                $classe = ($classe == "specalt")?"spec":"specalt";	
                $qtde = 1;
            }
			echo "<tr class='{$classe}'>
			    <td align='left'>
					<span >{$campo['mb_nome']}</span>
				</td>
			    <td align='left'>
					<span >{$campo['est_nome']}</span>
				</td>
				<td align='left'>
					<span >{$campo['sup_nome']}</span>
				</td>
			    <td align='center'>
					<span >{$qtde}</span>
				</td>
			</tr>";				
            $ultima_modalidade = $campo['mb_nome'];
            $qtde++;
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Não foram encontrados bolsistas cadastrados no sistema.</span></td>
			</tr>
		</table>";
    } 
    $bolsista = (sizeof($result) == 1) ? "bolsista" : "bolsistas";
    echo "</table><br>
            <div align='right'> Total = <strong>" . sql_num_rows($result) . " " . $bolsista . " </strong></div>";
}
 
?>
	           

<table width='100%' style='border:0px;' cellspacing='0' cellpadding='0'>  
<tr><td>&nbsp;</td></tr>
<tr align='center'><td>
<a onclick="window.open('relatorio.bolsistas.por.modalidade.print.php','relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
</table>
