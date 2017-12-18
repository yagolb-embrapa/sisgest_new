<?php

// ARQUIVO PARA MANIPULAR UPLOAD DE ARQUIVOS...

function getExtension($arquivo){
	$ext = explode(".",$arquivo["name"]);
	return $ext[sizeof($ext)-1];
}

//retorna TRUE se a extensao FOR IGUAL A EXTENSAO FORNECIDA;
function validExtension($extension,$arquivo){
	$extension = str_replace(".","",$extension);
	$extension = explode(";",$extension);
	foreach ($extension as $ext){
		if (getExtension($arquivo)==$ext) return true;
	}
	return false;	
}

//UPLODAR ARQUIVO PRO SERVIDOR
function uploadFile($arquivo, $name, $dest="./arquivos/"){
	$tipo = getExtension($arquivo);
	$name = $name.".".$tipo;
 	if(move_uploaded_file($arquivo["tmp_name"], $dest.$name)) {
 		chmod($dest.$name, 0777);
 		return $tipo;
 	} else {
 		return false;
 	}
}
?>
