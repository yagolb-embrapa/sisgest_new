<?php
	include_once("../classes/DB.php");
	include_once("../classes/Register.php");
	include("../functions/functions.forms.php");
	include("../functions/functions.database.php");
	DB::connect();
	
	$id = $_GET["id"];	

	if ($id){
		/* Pega o id do supervisor daquele estagiario */
		$q_estag = "SELECT id_supervisor FROM estagiarios WHERE id = {$id};";
		$r_estag = sql_executa($q_estag);
		
		if(sql_num_rows($r_estag)>0){	
			$c_estag = sql_fetch_array($r_estag);
			
			/* Pega o nome do supervisor */			
			$q_superv = "SELECT nome FROM supervisores WHERE id = {$c_estag['id_supervisor']};";
			$r_superv = sql_executa($q_superv);
			
			if(sql_num_rows($r_superv)>0){	
				$c_superv = sql_fetch_array($r_superv);
				if($c_superv['nome'])
					echo $c_superv['nome'];
				else
					echo "Não encontrado"; 	
			}	 
		}else{
			echo "Não encontrado";		
		}		 
	}else{
		echo "Não encontrado";		
	}		
?>
