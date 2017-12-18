<?php
try{
session_start();

}
catch(Exception $e){
	//?
}

function allow($perm=""){
		if (!isset($_SESSION["USUARIO"]))
			echo "<script> window.location = '../login.php' </script>";		
		if (($perm!="")&&(!($_SESSION["PERMISSAO"] & $perm)))
			echo "<script> window.location = 'index.php?erro=grant' </script>";
}

function allow_root($perm=""){
		if (!isset($_SESSION["USUARIO"]))
			echo "<script> window.location = 'login.php' </script>";		
		if (($perm!="")&&(!($_SESSION["PERMISSAO"] & $perm)))
			echo "<script> window.location = 'index.php?erro=grant' </script>";
}


function hasPerm($level){
	if (!($_SESSION["PERMISSAO"] & $level)) return false;
	else return true;
}

?>
