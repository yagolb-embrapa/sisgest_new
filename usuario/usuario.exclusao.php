<?php

$qtd_abas = 0;
require_once("../functions/functions.database.php");//temporario 
require_once("../functions/functions.forms.php");
require_once("../sessions.php");
if($_SESSION['USERNIVEL'] != 'a'){
	echo "Você não tem permissão para acessar essa área do sistema.";
	exit(); 
}
$id = $_GET['id'];	
$cor = 0;	
$qryStr = "SELECT nome FROM users WHERE id = {$id}";
$qry = sql_executa($qryStr);

if(sql_num_rows($qry)>0){
	$row = sql_fetch_array($qry);	
}

$q_delete = "DELETE FROM users WHERE id = {$id};";
$r_delete = sql_executa($q_delete);

if($r_delete){
	echo "<script language='javascript'>document.location.reload();</script>";
}else{
	$msg = "<font color='red'>Não foi possível excluir o usuário {$row['nome']}. Por favor, tente novamente.</font>";
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
