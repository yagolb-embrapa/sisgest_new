<?php

/*Includes basicos*/
include("../sessions.php");
allow();//verifica se o usuario esta logado
include_once("../conexao.php");
include_once("../inc/header.php");
include_once("../classes/DB.php");
include_once("../classes/Register.php");
//include_once("../classes/BaseCatracas.php");
//include_once("../classes/DIMEP.php");
//include_once("../classes/MADIS.php");
DB::connect();

?>

<!--<script language="javascript" src="../js/TAjax.js"></script>-->
<!--<script language="Javascript" type="text/javascript">
	var ajax = new TAjax();

  var i = 1;         
  function mostrarAba(contAba,aba) {
  	 for(i=1;i<1;i++){  	 
      document.getElementById('a'+i).className = '';
		document.getElementById('aba'+i).style.display = 'none';
    }
    divAba = document.getElementById(contAba);
    divAba.style.display = 'block';    
    document.getElementById(aba).className = 'active';                           
  }   
  
</script>-->
<body>
<div align="center">

<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<!-- TR de TOPO (banner do sistema) -->
<tr><td height="120"><?php include_once("../inc/topo.php"); ?></td></tr>
