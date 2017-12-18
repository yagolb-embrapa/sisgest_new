<?php 

$qtd_abas = 1;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

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
        if(document.getElementById('s'+arrayErros[i]) != null)
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
		<span class='titulo'>.: Inclusão de Instituição de Ensino</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
		<?php
		
//Se clicou
$submit = $_POST['submit'];
unset($string_erros);
if($submit){
	extract($_POST);// 1 - Pega tb todos os valores do formulario
	$municipio = $_POST['municipio'];//pega manualmente pq como esta em outra pagina, nao ta pegando com a linha acima
		
	//colocar aqui os campos que podem ser vazios no formulario
	$excecoes_vazio = array("endereco","complemento","cep","bairro");	
	
	//Verificar campos vazios
	while($vaz = each($_POST)){
		//coloca os campos obrigatorios que estao vazios no vetor		
		if(empty($vaz['value']) && !in_array($vaz['key'],$excecoes_vazio)){						
			$erros[] = $vaz['key'];			
		}														
	}
				
	//validando outros formatos
	if(!valida($cnpj,'cnpj')) $erros[] = 'cnpj';
	if(!valida($cep,'cep') && !(empty($cep))) $erros[] = 'cep';	
	if(!valida($data_convenio,'data')) $erros[] = 'data_convenio';
				
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
		/*$reg = Register::create('instituicoes');
		$reg->razao_social = $razao;				
		$reg->cnpj = $cnpj;			
		$reg->endereco = $endereco;
		$reg->complemento = $complemento;
		$reg->bairro = $bairro;
		$reg->cep = $cep;
		$reg->id_municipio = $municipio;
		$reg->uf = $uf;						
		$reg->save();*/
				
		$query = "INSERT INTO instituicoes_ensino (razao_social, cnpj, endereco, complemento, bairro, cep, id_municipio, uf, data_convenio, numero_saic)
                  VALUES('$razao', '$cnpj', '$endereco', '$complemento', '$bairro', '$cep', $municipio, '$uf', '".formata($data_convenio, 'data')."', '$numero_saic');";
		$result = sql_executa($query);
		//mensagem de sucesso		
		if($result){
			echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#EFFFF4'>
					<td align='center'><span align='center' style='color:#296F3E;'>Instituição incluída com sucesso!</span></td>
				</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
			
			unset($_POST, $razao, $cnpj, $endereco, $complemento, $bairro, $cep, $municipio, $uf, $data_convenio, $numero_saic);
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
        <td width="25%"><span>Razão Social (*)</span></td>
        <td width="75%"><input name="razao" id="razao" type="text" size='60' maxlength='80' value="<?php echo $razao; ?>"><span id='srazao' class="sErro">&nbsp;*</span></td>        
      </tr>           
      <tr class='specalt'>     
        <td ><span>CNPJ (*)</span></td>       
        <td><input name="cnpj" type="text" id="cnpj" size='15' maxlength="18" onKeyPress='FormataCNPJ(this, event);' value="<?php echo $cnpj; ?>"><span id='scnpj' class="sErro">&nbsp;*</span></td>
       </tr>      
       <tr class='specalt'>
        <td><span>Endereço</span></td>
        <td><input name="endereco" id="endereco" type="text" size="40" maxlength="50"  value="<?php echo $endereco; ?>"><span id='sendereco' class="sErro">&nbsp;*</span></td>
      </tr>
        <tr class='specalt'>
        <td ><span>Complemento</span></td>
        <td><input name="complemento" id="complemento" type="text"  value="<?php echo $complemento; ?>"><span id='scomplemento' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>CEP</span></td>
        <td><input name="cep" id="cep" type="text" size="10" maxlength="9" onKeyPress="mascara(this, mcep);"  value="<?php echo $cep; ?>"><span id='scep' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Bairro</span></td>
        <td><input name="bairro" id="bairro" type="text"  value="<?php echo $bairro; ?>"><span id='sbairro' class="sErro">&nbsp;*</span></td>
       </tr>       
       <tr  class='specalt'>
        <td><span>UF (*)</span></td>
        <td><select name="uf" onchange="ajax.loadDiv('divMunic','../functions/ajax.municLoad.php?uf='+this.value);">
          <option value="">-UF-</option>
          <?php
        		$ufs = Register::filter('uf');       				        					
        		foreach($ufs as $uf_reg){
					echo "<option value='{$uf_reg->uf}'";
					if($uf_reg->uf == $uf) echo " selected='selected' ";		
					echo ">{$uf_reg->uf}</option>";
				}
        		?>					
        </select><span id='suf' class="sErro">&nbsp;*</span></td></tr>
       <tr  class='specalt'>
        <td><span>Município (*)</span></td>
        <td><div style="display:inline;" id="divMunic"></div>
          <script language="JavaScript" type="text/javascript">          	
		  		ajax.loadDiv('divMunic','../functions/ajax.municLoad.php?uf=<?php echo $uf."&mun=".$municipio;?>');		  		
		  	 </script><span id='smunicipio' class="sErro">&nbsp;*</span></td>	    
       </tr>

        <tr class='specalt'>
        <td ><span>Data do Convênio</span></td>
        <td><input name="data_convenio" id="data_convenio" type="text" size='10' maxlength='10' value="<?php echo $data_convenio; ?>"><span id='sdata_convenio' class="sErro">&nbsp;*</span></td>
       </tr>       

        <tr class='specalt'>
        <td ><span>Número SAIC</span></td>
        <td><input name="numero_saic" id="numero_saic" type="text" size='25' maxlength='25' value="<?php echo $numero_saic; ?>"><span id='snumero_saic' class="sErro">&nbsp;*</span></td>
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
    $('input#data_convenio').mask('39/19/2999');
	mostraErros('<?php echo $string_erros; ?>');	
</script>
<?php 
include_once('../inc/copyright.php');
?>
</div>
