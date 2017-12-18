<?php

	include_once("../classes/DB.php");
	include_once("../classes/Register.php");
	DB::connect();
	
	echo "<select name='municipio' id='municipio' class='select'>";
	$uf = $_GET["uf"];
	$munGet = $_GET["mun"];		
		
	if ($uf){		
        $query = "SELECT id,
                         nome
                  FROM   municipios
                  WHERE  uf='{$uf}'";
        $mun = DB::fetch_all($query);
		foreach($mun as $municipios){			
			echo "<option value='{$municipios['id']}' ";						
			if ($municipios['id'] == $munGet) echo " selected='selected' "; 
			echo">".$municipios['nome']."</option>";			
		}		
	}else
		echo "<option value='0'>-- Selecione uma UF --</option>";
	echo "</select>";	
?>

<script language="javascript">
	ajax.showElement('divMunic','inline');
</script>
