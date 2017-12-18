<?php 

$qtd_abas = 0;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>

<script language="javascript" src="TAjax.js"></script>
<script language="javascript">
var ajax = new TAjax();
</script>
<!-- TR de CONTEUDO -->

<?php 
$nome_pesq = trim (strip_tags ($_GET ['nome']));
$codigo = trim (strip_tags ($_GET ['cod']));
$flag_busca = $_GET['fb'];

//Definindo condicoes para a query de busca
if (( empty($nome_pesq) && $codigo == "") && empty($flag_busca) ){
	$condicao = "";	
	$flag_inicio_pg = true;//flag_inicio_pg serve para nao deixar buscar logo que inicia a pagina [tipica gambi]
}elseif (!empty($nome_pesq) && $codigo != ""){
	$condicao = " WHERE (nome ILIKE '%{$nome_pesq}%'				  
					  AND id = '{$codigo}') ";
}elseif(empty($nome_pesq) && $codigo != ""){
	$condicao = " WHERE id = '{$codigo}' ";
}elseif(!empty($nome_pesq) && $codigo == ""){
	$condicao = " WHERE nome ILIKE '%{$nome_pesq}%'";
}
?>

<tr>
  <td width='752' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>		
	<div class='divTitulo' align='left'>
		<span class='titulo'>.: Gerenciamento de Supervisores</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		<a href="supervisor.inclusao.php"  alt="Clique aqui para incluir um novo supervisor.">
			<img src="../img/icon_note_include.gif" style="border:none;">&nbsp;<b>Incluir Supervisor</b></a> 
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>	
  	 <form method='get' name='form' id='form' action="<?php echo $_SERVER['PHP_SELF'];?>">  	  	
    <table width="100%" class='formulario'>
     	<tr class='specalt'>
     	  <th colspan='2'>Busca</th>
      </tr>           
      <tr class='specalt'>
        <td width="14%"><span>Nome</span></td>
        <td width="36%"><input name="nome" type="text" id="nome" size='40' maxlength='50' value="<?php echo $nome_pesq; ?>"></td>        
      </tr>       
      <td ><span>Código</span></td>
        <td><input name="cod" type="text" id="cod" size='6' maxlength='6' value="<?php echo $codigo; ?>" onKeyPress='mascara(this, mnum);'>
			</td>
    </table>
    <input type='hidden' id='fb' name='fb' value='<?php echo $flag_busca; ?>'>
   </form>
  <br>
		
  <table width="600px" bgcolor="#FFFFFF"><tr align='left'><td>
  <table width="600px" bgcolor="#F5FAFA">
   <tr align='center'><td colspan='2' >
    <input type="button" name="buscar" value="Buscar"  onclick='document.form.submit()'>    
   </td></tr>
  </table> 
  </td></tr>
  </table>
<?php 
//Não deixa fazer a busca logo que começa
if($flag_inicio_pg != true){
	$query_busca = "SELECT * FROM funcionarios {$condicao} ORDER BY nome";	
	$resultado_busca = sql_executa($query_busca);
	//$campo_busca = sql_fetch_array($resultado_busca);
	$qtde_busca = sql_num_rows($resultado_busca);
		
	if($qtde_busca > 0){	
		//Imprimindo cabecalho
			echo "<br><br>
			<table  width='100%' class='formulario'>						
				<tr>
					<th style='text-align:center;' width='303'>
						Nome
					</th>
					<th style='text-align:center;' width='350'>
						Código
					</th>
					<th style='text-align:center;' width='40'>
						Visualizar
					</th>			
					<th style='text-align:center;' width='40'>
						Editar
					</th>
					<th style='text-align:center;' width='40'>
						Excluir
					</th>
				</tr>		 			 
			";			
			//Imprime resultados			
			while ( $campo_busca = sql_fetch_array($resultado_busca) ){								
								
				echo "<tr class='specalt'>
						<td align='center'>
							<span >{$campo_busca['nome']}</span>
						</td>
						<td align='center'>						
							<span >{$campo_busca['id']}</span>
						</td>
						<td align='center'>
							<a href='' alt='Clique aqui para visualizar os dados deste estagiário.' ><img src='../img/icon_note_new.gif' border='0'></a>
						</td>						
						<td align='center'>
							<a href='' alt='Clique aqui para editar os dados deste estagiário.'><img src='../img/icon_note_edit.gif' border='0'></a>
						</td>
						<td align='center'>
							<a href='' alt='Clique aqui para excluir este estagiário do sistema.'><img src='../img/icon_note_delete.gif' border='0'></a>
						</td>
					</tr>		 
				";			
			}
			
			//----------- Paginação	-------------
			$nome_tratado = str_replace(" ", "+", $nome_pesq);
			$cond_url = "&nome={$nome_tratado}&tipo={$codigo}";							
			
			
		}elseif($flag_inicio_pg == false){
			echo "<br><br>
			<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' align='left' height='50px'>						
				<tr bgcolor='#FAF5F5'>
					<td align='center'><span align='center'>Não foram encontrados registros com os parâmetros especificados.</span></td>
				</tr>
			</table>";
		}	
}

?>
	</div>
	</form>
  
  </div><!--<div align="center" id="divListUsr">-->
  </td>
</tr>
</table>
<script language='javascript'>document.getElementById('fb').value = 1;</script>
<?php 
include_once('../inc/copyright.php');
?>
</div>

<div class="divBottomFix" id="divMsg" style="display:none;background-color:#FF2828; color:#FEFEFE; border:#000033 1px solid; padding: 5px 15px 5px 15px; margin: 0 0 2px 2px;">Registro inserido com sucesso</div>
<script language="javascript">
ajax.loadDiv('divManip','lista_usr.php');
</script>
