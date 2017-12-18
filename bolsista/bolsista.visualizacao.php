<?php 

$qtd_abas = 0;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<!-- TR de CONTEUDO -->  
<tr>
  <td width='752' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo' align='left'>
		<span class='titulo'>.: Visualização da Modalidade de Bolsista</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
<?php
$id = $_GET['id'];

if(empty($id))
	$msg_erro = "A modalidade não foi encontrado.";		
else{		
	$query_superv = "SELECT * FROM modalidades_bolsista WHERE id = {$id}";
	$result_superv = sql_executa($query_superv);	
	if(sql_num_rows($result_superv)==0)
		$msg_erro = "A modalidade não foi encontrada.";			
	else
		$campo = sql_fetch_array($result_superv);
}

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>		
	<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
}else{
    echo "<p><a href='javascript://' onclick=\"document.location.href='bolsista.edicao.php?id=".$id."';\">
        <img src='../img/icon_edit.gif' width='16' height='16' border='0'>Editar Dados</a></p>";
?>
   <!-- Abas -->	
	<ul class='listaAbas'>
       <li><a id='a1' class='active'>Dados</a></li>      
   </ul>
   </div>
	<div id="aba1" class='conteudoAba' style='display:block;'>		  	 	
  	  	<table width="100%" class='visualizacao'>
  	  	<tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>		  
      <tr class='specalt'>
        <td width="33%"><span>Nome</span></td>
        <td width="67%"><span><?php echo (empty($campo['nome']))?" <i>Não preenchido</i> ":$campo['nome']; ?></span></td>        
      </tr>
        
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       </table>
       </div>
            
    </table>  
  </div></div>
 </div> 
</div>
<?php
}
echo "
  </td>
</tr>
</table>";
 
include_once('../inc/copyright.php');
?>
</div>
