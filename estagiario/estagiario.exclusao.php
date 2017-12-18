<?php

$qtd_abas = 0;
require_once("../functions/functions.database.php");//temporario 
require_once("../functions/functions.forms.php");
			
$id = $_GET['id'];	
$cor = 0;	
$qryStr = "SELECT nome FROM estagiarios WHERE id = {$id}";
$qry = sql_executa($qryStr);

if(sql_num_rows($qry)>0){
	$row = sql_fetch_array($qry);	
}

$q_delete = "DELETE FROM estagiarios WHERE id = {$id};";
$r_delete = sql_executa($q_delete);

if($r_delete){
	$msg = "O estagiário {$row['nome']} foi excluído com sucesso!";
}else{
	$msg = "Não foi possível excluir o estagiário {$row['nome']}. Por favor, tente novamente.";
}
echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
		<tr bgcolor='#F5FAFA'>
			<td align='center'><span align='center' style='color:black;'>{$msg}</span></td>
		</tr>
		</table>";
 			

?>
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}

</style>
