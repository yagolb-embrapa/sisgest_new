<?php 	
$qtd_abas = 0;
require_once("../inc/header.php");
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../sessions.php");


?>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
  <div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
  <div align='left' class='divTitulo'>
		<span class='titulo'>.: Gerenciamento de Usuários</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
	</div><?php
if($_SESSION['USERNIVEL'] != 'a'){
	echo "Você não tem permissão para acessar essa área do sistema.";
	exit(); 
}			
 echo "<div align='center' style='padding: 0 0 10px 0;'>
	<input type='button' value='Incluir Novo Usuário' style='padding: 2px 15px 2px 15px; height:24px;' onClick=\"document.location.href='usuario.inclusao.php';\"></div>";

echo "<div id='divManip'></div>";		
		
$query = "SELECT * FROM users";
$result = sql_executa($query);
if(sql_num_rows($result) < 1){
	echo "<table width='582px' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
		<tr bgcolor='#FFEFEF'>				<td align='center'><span align='center' style='color:red;'>Nenhum usuário cadastrado</span></td>
		</tr>
	</table>";
}else{	
	
	echo "<table  width='582px' class='formulario'>						
	<tr>
		<th style='text-align:center;'>
			Nome
	</th>
	<th style='text-align:center;'>
			Login
	</th>
	<th style='text-align:center;'>
			Nível
	</th>
	<th style='text-align:center;'>
			Editar Dados
	</th>
	<th style='text-align:center;'>
			Excluir Usuário
	</th>
	</tr>";
	$classe = "spec";
	while ( $campo = sql_fetch_array($result) ){
		$classe = ($classe == "specalt")?"spec":"specalt";						
		echo "<tr class='{$classe}' >
				<td align='center' width='40%'>
				<span >{$campo['nome']}</span>
		</td>
		<td align='center' width='20%'>						
			<span >
			{$campo['login']}												
			</span>
		</td>
		<td align='center' width='20%'>						
			<span >";
			switch($campo['nivel']){
				case 'a': echo "Administrador";
				 	break;
				case 'u': echo "Usuário";
					break;			
			}																		
			echo "</span>
		</td>
		<td width='10%' align='center' valign='middle'>
         <a href='javascript://' onclick=\"document.location.href='usuario.edicao.php?id=".$campo['id']."';\">         	
         <img src='../img/icon_edit.gif' width='16' height='16' border='0'></a>
      </td>          
      <td width='10%' align='center' valign='middle'>
         <a href='javascript://' onclick=\"if (confirm('Deseja realmente excluir o usuário? Esta operação não poderá ser desfeita.')){ajax.loadDiv('divManip','usuario.exclusao.php?id=".$campo['id']."');}\">
        <img src='../img/icon_delete.gif' width='16' height='16' border='0'></a> 
    	 </td>						
		</tr>";				
	}
	echo "</table>";		
}
	 
?>	
	  
  </td>
</tr>
<tr><td>
<?php include("../inc/copyright.php"); ?>
</td></tr>
</table>

</div>
<script language="javascript">
	var ajax = new TAjax();	
</script>
</body>
</html>

