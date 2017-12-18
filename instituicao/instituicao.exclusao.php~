<?php

$qtd_abas = 0;
require_once("../functions/functions.database.php");//temporario 
require_once("../functions/functions.forms.php");
require_once("../sessions.php");

if($_SESSION['USERNIVEL'] != 'a'){
	echo "Somente o administrador pode fazer a exclusão de registros do sistema.";
	exit(); 
}			

$id = $_GET['id'];	
$cor = 0;	
$qryStr = "SELECT razao_social FROM instituicoes_ensino WHERE id = {$id}";
$qry = sql_executa($qryStr);

if(sql_num_rows($qry)>0){
	$row = sql_fetch_array($qry);	
}

$q_delete = "DELETE FROM instituicoes_ensino WHERE id = {$id};";
$r_delete = sql_executa($q_delete);

if($r_delete){
	$msg = "A instituição {$row['nome']} foi excluída com sucesso!";
}else{
	$msg = "Não foi possível excluir a instituição {$row['nome']}. Por favor, tente novamente.";
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
