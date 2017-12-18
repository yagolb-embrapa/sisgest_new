<?php 

$qtd_abas = 0;
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../classes/DB.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<div align='center'>
    <span class='titulo'>Relação de Estagiários e Horários</span><br>
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
    $query = "select e.id, e.nome, s.nome sup_nome, h.dia, h.entrada, h.saida, e.tipo_vinculo tipo
				from horarios h 
				inner join estagiarios e on (e.id=h.id_estagiario) 
				left join supervisores s on (s.id=e.id_supervisor)
				where h.tipo = 'e' and e.status = 1
				order by tipo, e.nome, h.dia, h.entrada;";
	$result = sql_executa($query);	
	
	if(sql_num_rows($result)>0){
		echo "E: estagiário / B: bolsista<table width='100%' class='formulario'>						
			<tr>
				<th colspan='3'>&nbsp;</th>
				<th colspan='5' style='text-align:center; border-left: 1px solid #444;'>Horários</th>
			</tr>			
			<tr>
			<th style='text-align:center;' >
					&nbsp;
			</th>			
			<th style='text-align:center;' >
					Estagiário
			</th>
			<th style='text-align:center;' >
					Supervisor
			</th>
			<th style='text-align:center; border-left: 1px solid #444;'  width='14%'>
					Segunda-feira
			</th>
			<th style='text-align:center; width:14%;'>
					Terça-feira
			</th>
			<th style='text-align:center;'  width='14%'>
					Quarta-feira
			</th>
			<th style='text-align:center;'  width='14%'>
					Quinta-feira
			</th>
			<th style='text-align:center;' width='14%'>
					Sexta-feira
			</th>
		</tr>";
		$classe = "specalt";
        $qtde = 1;
        $ct = 0;
        $resultset = array();
		while ( $campo = sql_fetch_array($result) ){

			if (!isset($row)){
				$row = array();
			}
			else if ($row[0]!=$campo["id"]){
				$resultset[]=$row;
				unset($row);
				$row = array();
			}
			
			$row[0] = $campo["id"];
			$row[1] = ($campo["tipo"]=="e")?"E":"B";
			$row[2] = $campo["nome"];
			$row[3] = $campo["sup_nome"];
			$row[$campo["dia"]+2][] = $campo["entrada"]." - ".$campo["saida"];
		}
		
		
            
        foreach ($resultset as $row){
        	$classe = ($classe == "specalt")?"spec":"specalt";
        	echo "<tr class='{$classe}'>
	        		<td align='center' style='border-right:1px solid #444;'>
	        			<span >{$row[1]}</span>
	        		</td>
	        		<td align='center' >
	        			<span >{$row[2]}</span>
	        		</td>
	        		<td align='center' >
	        			<span >{$row[3]}</span>
	        		</td>
	        		<td align='center'  style='border-left: 1px solid #444;'>
	        			<span >".((isset($row[4]))?implode("<br/>",$row[4]):"&nbsp;")."</span>
	        		</td>
	        		<td align='center' >
	        			<span >".((isset($row[5]))?implode("<br/>",$row[5]):"&nbsp;")."</span>
	        		</td>
	        		<td align='center' >
	        			<span >".((isset($row[6]))?implode("<br/>",$row[6]):"&nbsp;")."</span>
	        		</td>
	        		<td align='center' >
	        			<span >".((isset($row[7]))?implode("<br/>",$row[7]):"&nbsp;")."</span>
	        		</td>
	        		<td align='center'>
	        			<span >".((isset($row[8]))?implode("<br/>",$row[8]):"&nbsp;")."</span>
	        		</td>	        		
        		</tr>";
        	$qtde++;
        }
		
		
        echo "</table>";		
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Não foram encontrados estagiários cadastrados no sistema.</span></td>
			</tr>
		</table>";	
    } 
    echo "</table><br>
            <div align='right'> Total = <strong>" . ($qtde-1) . " estagiários e bolsistas. </strong></div>";
}
 
?>
	           
</table>

<table width='100%' style='border:0px;' cellspacing='0' cellpadding='0'>  
<tr><td>&nbsp;</td></tr>
<tr align='center'><td>
<a onclick="window.open('horarios.estagiarios.print.php','relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
</table>
