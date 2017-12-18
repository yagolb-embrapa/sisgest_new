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
		<span class='titulo'>Estagiários que não participaram do PIEC</span><br>
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
	$query_piec = "SELECT * FROM estagiarios WHERE participou_piec = 'N' AND status = 1 ORDER BY nome";
	$result_piec = sql_executa($query_piec);	
	
	
	if(sql_num_rows($result_piec)>0){
	echo "<table  width='100%' class='formulario'>						
			<tr>
				<th style='text-align:center;' width='303'>
					Nome
			</th>
			<th style='text-align:center;' width='350'>
					E-mail
			</th>	
			<th	 style='text-align:center;' width='40'>
				Ramal	
			</th>			
			<th style='text-align:center;' width='40'>
				Supervisor
			</th>
			<th style='text-align:center;' width='40'>
				Início
			</th>
		</tr>";
		$classe = "spec";
		while ( $campo = sql_fetch_array($result_piec) ){
			//pega o nome do supervisor, pois temos o id 
			$query_superv = "SELECT * FROM supervisores WHERE id = {$campo['id_supervisor']} ";
			$res_superv = sql_executa($query_superv);
			$superv = sql_fetch_array($res_superv);				
			$classe = ($classe == "specalt")?"spec":"specalt";							
			echo "<tr class='{$classe}'>
					<td align='left' width='30%'>
					<span >{$campo['nome']}</span>
				</td>
				<td align='left' width='20%'>						
					<span >";
					//coloca o email embrapa, mas se nao tiver, coloca o email pessoal
					if(empty($campo['email_embrapa'])) 
						echo $campo['email'];
					else echo $campo['email_embrapa'];
					echo "</span>
				</td>
			<td	 align='center' width='10%'>
						<span >";
					echo (empty($campo['ramal']))?"<i>Indefinido</i>":$campo['ramal']; 
				echo "</span>
				</td>						
				<td align='left' width='30%'>
					<span >{$superv['nome']}</span>
				</td>
			<td align='left' width='30%'>
					<span >".formata($campo['vigencia_inicio'],'redata')."</span>
				</td>
			</tr>";				
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Todos os estagiários participaram do PIEC.</span></td>
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
</table>
";

 
include_once('../inc/copyright.php');

echo "<script language='javascript'>window.print();</script>";
?>
</div>
