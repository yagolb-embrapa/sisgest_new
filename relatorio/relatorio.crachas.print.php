<?php 

$qtd_abas = 0;
require_once("../sessions.php");
if(!$_SESSION["USERID"]){
	echo "<script language='javascript'> window.location.href='../login.php'; </script>";	
}

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>
<html>
<head>
	<title>SisGest - Embrapa Informática Agropecuária</title>
<meta http-equiv="content-type" content="="text/ht; charset=UTF-8" >

	<link href="../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/style.form.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css" />
	<link href="../css/abas.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript" src="../js/masks.js"></script>	
    <script language="javascript" src="../js/TAjax.js"></script>	
</head>
<body>
    <div align="center">
    <table width="800" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >

<!-- TR de CONTEUDO -->  
<tr>
  <td width='800' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo' align='center'>
		<span class='tituloMaior'>SisGest - Sistema Gerenciador de Estágios</span><br><br>
		<span class='titulo'>Relação de Crachás de Estagiários</span><br>
		<span class='subtitulo'><?php echo date("d/m/Y"); ?></span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
<?php

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>";	
	
}else{
	$query_cracha = "SELECT * FROM estagiarios  WHERE status = 1 ORDER BY cracha";
	$result_cracha = sql_executa($query_cracha);	
	
	
	if(sql_num_rows($result_cracha)>0){
		echo "<table  width='100%' class='formPrint'>						
			<tr>
			<th style='text-align:center;' width='350'>
					Número Crachá
			</th>
			<th style='text-align:center;' width='303'>
					Nome
			</th>
		</tr>";
	
		$classe = "spec";
		while ( $campo = sql_fetch_array($result_cracha) ){
			$classe = ($classe == "specalt")?"spec":"specalt";	
			echo "<tr class='{$classe}'>
				<td align='center' width='30%'>						
					<span >{$campo['cracha']}</span>
				</td>
				
					<td align='left' width='70%'>
					<span >{$campo['nome']}</span>
				</td>
			</tr>";				
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Não foram encontrados estagiários cadastrados no sistema.</span></td>
			</tr>
		</table>";	
  } 
}
 
?>	
	           
  </table>
  
    
  </div></div>
 </div> 
</div>
<?php

echo "
  </td>
</tr>
</table>";
 
include_once('../inc/copyright.php');
echo "<script language='javascript'>window.print();</script>";

?>
</div>
