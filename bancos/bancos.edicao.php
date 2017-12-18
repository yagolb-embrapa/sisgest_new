<?php 

$qtd_abas = 1;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");


?>
<tr>
  <td width='750px' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
<?php
$id=$_GET['id'];
if ($_POST["submit"]){
	
	$nome = $_POST['nome'];
	$codigo = $_POST['codigo'];
	
	$sql = "update bancos 
			set banco = '{$nome}',
			codigo_banco = '{$codigo}' 
			where id={$id}";
	$qry = sql_executa($sql);
	
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>";						
				
	
	if ($qry){
		echo  "<tr bgcolor='#EFFFF4'>
					<td align='center'><span align='center' style='color:#296F3E;'>Banco alterado com sucesso!</span></td>
				</tr>";
	}
	else{
		echo "<tr bgcolor='#FFEFEF'>
					<td align='center'><span align='center' style='color:red;'>Erro ao inserir banco.<br/>";
		$sqlCodigos = "SELECT banco FROM bancos where codigo_banco = '{$codigo}'";
		$qryCodigos = sql_executa($sqlCodigos);
		$rowCod = sql_fetch_array($qryCodigos);
		if ($rowCod['banco']){
			echo "O código {$codigo} pertence a/ao {$rowCod['banco']}";
		}
		echo "</span></td>
				</tr>";
	}
	
	echo "</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
	
	
}
else{
	if (!$id){
		echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>";						
		echo "<tr bgcolor='#FFEFEF'>
					<td align='center'><span align='center' style='color:red;'>Erro ao inserir banco.<br/>
					Certifique-se que o banco já não está inserido no sistema</span></td>
				</tr>";
		echo "</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
	}
	else{		
		$sqlSel = "select * from bancos where id={$id}";
		$qrySel = sql_executa($sqlSel);
		$row = sql_fetch_array($qrySel);
		$nome = $row['banco'];
		$codigo = $row['codigo_banco'];
	}
	
}


?>
		
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Edição de Banco</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		<!-- Abas -->	
	<ul class='listaAbas'>
       <li><a href="" id='a1' class='active'>Dados</a></li>              
   </ul>
   </div>
   
	<form id="frmUsr" name="frmUsr" method="post">
	
	<!-- ============ Conteudo da Primeira ABA ============ --> 	
	<div id="aba1" class='conteudoAba' style='display:block;'>
		<div id="erro"></div>  	 	
  	  	<table width="100%" class='formulario'>
  	  	<tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>  	  		  
      <tr class='specalt'>
        <td width="25%"><span>Nome (*)</span></td>
        <td width="75%"><input name="nome" id="nome" type="text" size='40' maxlength='60' value="<?php echo $nome; ?>"><span id='snome' class="sErro">&nbsp;*</span></td>        
      </tr>
      <tr class='specalt'>
        <td width="25%"><span>Código (*)</span></td>
        <td width="75%"><input name="codigo" id="codigo" type="text" size='7' maxlength='10' value="<?php echo $codigo; ?>"><span id='snome' class="sErro">&nbsp;*</span></td>        
      </tr>
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       </table>
       </div>  
            
    </table> 
  <tabela width="800px" bgcolor="#FFFFFF"><tr align='center'><td>
  <table class="tabelaBotao">
   <tr align='center'><td colspan='2' >
    <input type="submit" name="submit" value="Salvar"/>    
   </td></tr>
  </table> 
  </td></tr>
  </table>
  </div> 
 </div>

	
 </div>	
</form>  
</div>
  </td>
</tr>
</table>
<?php 
include_once('../inc/copyright.php');
?>
</div>
		