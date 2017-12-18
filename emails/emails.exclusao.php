<?php

$qtd_abas = 0;
require_once("../functions/functions.database.php");//temporario 
require_once("../functions/functions.forms.php");
require_once("../sessions.php");
require_once("../classes/DB.php");

if($_SESSION['USERNIVEL'] != 'a'){
	echo "Somente o administrador pode fazer a exclusão de registros do sistema.";
	exit(); 
}
			
$id_setor = $_GET['id_setor'];
$cor = 0;	
$qryStr = "SELECT * FROM emails WHERE id_setor = {$id_setor}";
$qry = sql_executa($qryStr);

if(sql_num_rows($qry)>0){
	$row = sql_fetch_array($qry);	
}

$q_delete = "DELETE FROM emails WHERE id_setor = {$id_setor};";
$r_delete = sql_executa($q_delete);

$q_setor = "SELECT setor FROM setores WHERE id = {$id_setor};";
$r_setor = DB::fetch_all($q_setor);

if($r_delete){
	$msg = "Os emails do setor {$r_setor[0]['setor']} foram excluídos com sucesso!";
}else{
	$msg = "Não foi possível excluir os emails do setor {$r_setor[0]['setor']}. Por favor, tente novamente.";
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
