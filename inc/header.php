<?php

/*Includes basicos*/
require_once("../sessions.php");
require_once("../classes/DB.php");
require_once("../classes/LDAP.php");
require_once("../classes/Register.php");

DB::connect();

if(!$_SESSION["USERID"]){
	echo "<script language='javascript'> window.location.href='../login.php'; </script>";	
}

// Carrega arquivos .js que estejam no diretorio corrente
$arquivos_js = glob('*.js');

if(is_array($arquivos_js)) {
    $JS = array();

    foreach($arquivos_js as $js)
        $JS[] = '<script type="text/javascript" language="javascript" src="./' . $js . '"></script>';
}

// Carrega arquivos .css que estejam no diretorio corrente
$arquivos_css = glob('*.css');

if(is_array($arquivos_css)) {
    $CSS = array();

    foreach($arquivos_css as $css)
        $CSS[] = '<link type="text/css" rel="stylesheet" href="./' . $css . '" />';
}

?>

<html>
<head>
	<title>SisGest - Embrapa Informática Agropecuária</title>

	<meta http-equiv="content-type" content="text/html charset=UTF-8" >

	<link href="../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/style.form.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css" />
	<link href="../css/abas.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript" src="../js/masks.js"></script>	
    <script language="javascript" src="../js/TAjax.js"></script>
    <script language="javascript" src="../js/jquery.js"></script>
    <script language="javascript" src="../js/jquery.maskedinput.js"></script>
    <script language="Javascript" type="text/javascript">
        var ajax = new TAjax();

        <?php if(!isset($qtd_abas) || $qtd_abas <= 0) $qtd_abas = 1; ?>

        function mostrarAba(contAba,aba) {
            for(i=1;i<=<?= $qtd_abas ?>;i++){
                document.getElementById('a'+i).className = '';
                document.getElementById('aba'+i).style.display = 'none';
            }

            divAba = document.getElementById(contAba);
            divAba.style.display = 'block';    
            document.getElementById(aba).className = 'active';                           

            $('input#novo_periodo').focus();
        }   
        
    	function mostraErros(strErros){
	  	 	var i=0;  	   	 
	 		//transforma esta string em um array próprio do Javascript
	 		arrayErros = strErros.split("|");
			
		 	//varre o array e torna visivel o item de erro
	 		for (i=0;i<arrayErros.length;i++){	 		 		 	
                if(arrayErros[0] != '')
                    document.getElementById('s'+arrayErros[i]).style.visibility='visible';	 	
		 	} 
  		}
  		        
    </script>
    
    <!-- INCLUSAO DE JS DO MODULO -->
    <?= implode("\n", $JS) ?>

    <!-- INCLUSAO DE CSS DO MODULO -->
    <?= implode("\n", $CSS) ?>

</head>
<body>
    <div align="center">

    <table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
    <!-- TR de TOPO (banner do sistema) -->
    <tr><td height="120"><?php require_once("../inc/topo.php"); ?></td></tr>
