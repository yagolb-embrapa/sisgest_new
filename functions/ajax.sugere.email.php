<?php
	include_once("../classes/DB.php");
	include_once("../classes/Register.php");
	include("../functions/functions.forms.php");
	include("../functions/functions.database.php");
	DB::connect();
	
	$id = $_GET["id"];	

	if ($id){
		/* Pega o id do supervisor daquele estagiario */
		$q_estag = "SELECT nome FROM estagiarios WHERE id = {$id};";
		$r_estag = sql_executa($q_estag);
		
		if(sql_num_rows($r_estag)>0){	
			$c_estag = sql_fetch_array($r_estag);			
			$nome = explode(" ",$c_estag['nome']);
			$email = $nome[0];
			for($i=1;$i<count($nome);$i++){
				if(strlen($nome[$i]) > 3)
					$email .= substr($nome[$i],0,1);
			}
			echo strtolower($email."@colaborador.embrapa.br");	 
		}else{
			echo "Nehuma sugestão";		
		}		 
	}else{
		echo "Nehuma sugestão";		
	}		
?>
