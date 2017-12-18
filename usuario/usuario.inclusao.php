<?php 

$qtd_abas = 1;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../sessions.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>

<script language="Javascript" type="text/javascript">
	var ajax = new TAjax();
           
  function mostrarAba(contAba,aba) {
  	 for(i=1;i<5;i++){  	 	
      document.getElementById('a'+i).className = '';
		document.getElementById('aba'+i).style.display = 'none';
    }
    divAba = document.getElementById(contAba);
    divAba.style.display = 'block';    
    document.getElementById(aba).className = 'active';                           
  }   
  
  function mostraErros(strErros){
  	 var i=0;  	   	 
	 //transforma esta string em um array próprio do Javascript
	 arrayErros = strErros.split("|");

	 //varre o array e torna visivel o item de erro
	 for (i=0;i<arrayErros.length;i++){	 		 		 	
	 	document.getElementById('s'+arrayErros[i]).style.visibility='visible';	 	
	 } 
  }     

</script>

<!-- TR de CONTEUDO -->  
<tr>
  <td width='752' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo' align='left'>
		<span class='titulo'>.: Inclusão de Usuário</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
		<?php
if($_SESSION['USERNIVEL'] != 'a'){
	echo "Você não tem permissão para acessar essa área do sistema.";
	exit(); 
}
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
				
	// 3 - Mostra mensagem de erro ou cria query de insercao 
	if(count($erros)>0){
		//essa string é usada pelo javascript no final da pagina para marcar os campos com o asterisco vermelho 
		$string_erros = implode("|",$erros);			
		//mostra mensagem de erro
		if(count($erros)==1)
			$msg_erro = "Um campo não foi preenchido corretamente e foi marcado com um asterisco vermelho. Por favor, verifique-o e tente novamente.";		
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
				
		$query = "INSERT INTO users (nome, login, nivel) VALUES('$nome', '$login', '$nivel');";						
		$result = sql_executa($query);
		//mensagem de sucesso		
		if($result){
			echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#EFFFF4'>
					<td align='center'><span align='center' style='color:#296F3E;'>Usuário incluído com sucesso!</span></td>
				</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
			
			unset($_POST, $nome, $login, $nivel);
		}
	}
}
?>
   <!-- Abas -->	
	<ul class='listaAbas'>
       <li><a id='a1' class='active'>Dados</a></li>       
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
        <td width="75%"><input name="nome" id="nome" type="text" size='50' maxlength='50' value="<?php echo $nome; ?>"><span id='snome' class="sErro">&nbsp;*</span></td>        
      </tr>           
      <tr class='specalt'>     
        <td ><span>Login (*)</span></td>       
        <td><input name="login" type="text" id="login" size='15' maxlength="20" value="<?php echo $login; ?>"><span id='slogin' class="sErro">&nbsp;*</span></td>
       </tr>      
       <tr class='specalt'>
        <td><span>Nível (*)</span></td>
     		<td><select name='nivel' id='nivel'>  
        		<option value='' <?php if($nivel != 'a' && $nivel != 'u') echo selected; ?> >Selecione</option>
        		<option value='a' <?php if($nivel == 'a') echo selected; ?> >Administrador</option>
        		<option value='u' <?php if($nivel == 'u') echo selected; ?> >Usuário</option>
        </select>
        <span id='snivel' class="sErro">&nbsp;*</span>
        </td>	
      </tr>        
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
     </table></div>
    </table> 
  <table width="800px" bgcolor="#FFFFFF"><tr align='center'><td>
  <table width="600px" bgcolor="#F5FAFA">
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
