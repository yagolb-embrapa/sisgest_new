<?php
	include_once("../classes/DB.php");
	include_once("../classes/Register.php");
	include("../functions/functions.forms.php");
	DB::connect();
			
	echo "<span>";
	$inicio = $_GET["ini"];
	$fim = $_GET["fim"];
	$sem_termino = $_GET["sem"];
	$ano_termino = $_GET["ano"];		

	//se as duas datas tiverem sido digitadas
	if ($inicio && $fim){
		//confere formato das datas
		if(!valida($inicio,'data')) {echo "Data inicial inválida";return;}
		if(!valida($fim,'data')) {echo "Data final inválida";return;}

		//separando valores da data  			
		$diai = substr($inicio,0,2);
		$mesi = substr($inicio,3,2);
		$anoi = substr($inicio,-4);
		$diaf = substr($fim,0,2);
		$mesf = substr($fim,3,2);
		$anof = substr($fim,-4);
		
		//timestamp das datas
		$datai = mktime(0,0,0,$mesi,$diai,$anoi);
		$dataf = mktime(0,0,0,$mesf,$diaf,$anof);
				
		if($dataf < $datai)
			echo "Data final menor que a data inicial";
		else{
			$time = $dataf - $datai;
			//valor em dias ou meses								
			if($time > 2592000){
				$months = floor($time/2592000);
				$rest = floor(($time % 2592000)/86400);				
				if($rest>15) $months++;				
				echo "Duração aproximada: ".$months;
				echo ($months > 1)?" meses":" mês";				
			}else{
				$days = floor($time/86400);
				echo "Duração aproximada: ".$days." dias";											
			}														
		}		 
	}
	if($sem_termino && $ano_termino)
				if (($anof > $ano_termino) || ($anof == $ano_termino && $sem_termino == 1 && $mesf > 7) )
					echo "<br><span style='color:red;'>Término do estágio posterior ao término do curso</span>";
	echo "</span>";	
?>

<script language="javascript">
	ajax.showElement('divMunic','inline');
</script>