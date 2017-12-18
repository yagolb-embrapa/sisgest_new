<?php 

$qtd_abas = 1;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");


?>

<!-- TR de CONTEUDO -->  
<tr>
  <td width='750px' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Inclusão de Origem de Recursos</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
		<?php
		
//Se clicou
$submit = $_POST['submit'];
unset($string_erros);
if($submit){
	extract($_POST);// 1 - Pega tb todos os valores do formulario
	
	//colocar aqui os campos que podem ser vazios no formulario
	$excecoes_vazio = array();	
		
	//Verificar campos vazios
	while($vaz = each($_POST)){
		//coloca os campos obrigatorios que estao vazios no vetor		
		if(empty($vaz['value']) && !in_array($vaz['key'],$excecoes_vazio)){						
			$erros[] = $vaz['key'];			
		}														
	}

	$q_repetido = "SELECT * FROM origens_recursos WHERE origem = '{$origem}';";
	$r_repetido = sql_executa($q_repetido);
	if(sql_num_rows($r_repetido)>0) $erros[] = "origem";	
				
	// 3 - Mostra mensagem de erro ou cria query de insercao 
	if(count($erros)>0){
		//essa string é usada pelo javascript no final da pagina para marcar os campos com o asterisco vermelho 
		$string_erros = implode("|",$erros);			
		//mostra mensagem de erro
		if(count($erros)==1)
			if($erros[0] == "origem" && !empty($origem))
				$msg_erro = "A origem já está cadastrada no sistema.";
			else				
				$msg_erro = "Um campo não foi preenchido corretamente e foi marcado com um asterisco vermelho. Por favor, verifique-o e tente novamente.";
		else
			if(in_array("codigo",$erros) && !empty($codigo))
				$msg_erro = "A origem já está cadastrada no sistema.<br>Alguns campos não foram preenchidos corretamente e foram marcados com um asterisco vermelho. Por favor, verifique-os e tente novamente.";
			else	
				$msg_erro = "Alguns campos não foram preenchidos corretamente e foram marcados com um asterisco vermelho. Por favor, verifique-os e tente novamente.";
								
		echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>
					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
				</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
		
		
		//Coloca um sinal vermelho ao lado dos campos não-preenchidos ou preenchidos de forma incorreta
	}else{	
		/*$reg = Register::create('estagiarios');
		$reg->nome = $nome;		
		$reg->id = $codigo;
		$reg->cargo = $cargo;
		$reg->formacao = $formacao;		
		$reg->save();*/
				
		$query = "INSERT INTO origens_recursos (origem) VALUES('{$origem}');";		
		$result = sql_executa($query);
		//mensagem de sucesso		
		if($result){
			echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#EFFFF4'>
					<td align='center'><span align='center' style='color:#296F3E;'>Origem de Recursos incluída com sucesso!</span></td>
				</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
			
			unset($_POST);
		}
	}
}
?>
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
        <td width="75%"><input name="origem" id="origem" type="text" size='40' maxlength='80' value="<?php echo $origem; ?>"><span id='sorigem' class="sErro">&nbsp;*</span></td>        
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
<script language="javascript">
	mostraErros('<?php echo $string_erros; ?>');	
</script>
<?php 
include_once('../inc/copyright.php');
?>
</div>
