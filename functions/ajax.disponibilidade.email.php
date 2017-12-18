<?php
	include_once("../classes/DB.php");
	include_once("../classes/Register.php");
	include("../functions/functions.forms.php");
	include("../functions/functions.database.php");
	DB::connect();	
	
	$email = $_GET["email"];	
	/*POSTERIORMENTE, VERIFICAR NO LDAP, E NAO NO BD*/
		
	if ($email){
		if(!valida($email,'email')){ 
			echo "<span style='color:red;'><i>E-mail com formato incorreto</i></span>";			
		}else{	
			$q_estag = "SELECT * FROM estagiarios WHERE email_embrapa = '{$email}';";		
			$r_estag = sql_executa($q_estag);

			if(sql_num_rows($r_estag)>0){	
				echo "<span style='color:red;'><i>Não disponível</i></span>";
			}else{
				echo "<span style='color:green;'><i>Disponível</i></span>";	
			}
		}				 
	}else{
		echo "";		
	}		
?>
