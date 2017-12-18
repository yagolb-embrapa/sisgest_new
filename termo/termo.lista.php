<?php 

$qtd_abas = 4;
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
		<span class='titulo'>.: Gerador de Termos</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		<script language='javascript'>		
		
		function preencheCampos(idEstag,inputSuperv,inputEmail){			
			ajax.loadValue(inputSuperv,'../functions/ajax.busca.supervisor.php?id='+idEstag);
			ajax.loadValue(inputEmail,'../functions/ajax.sugere.email.php?id='+idEstag);		
		}
		
		function preencheSupervisor(idEstag,inputSuperv){			
			ajax.loadValue(inputSuperv,'../functions/ajax.busca.supervisor.php?id='+idEstag);					
		}		
		
		function disponibEmail(divDisponib,campo){
			var email = document.getElementById(campo).value;
			ajax.loadDiv(divDisponib,'../functions/ajax.disponibilidade.email.php?email='+email);
		}		
							
	</script>
		<?php
		

$submit = $_POST['submit'];
unset($string_erros);
if($submit){
	extract($_POST);// 1 - Pega tb todos os valores do formulario
	
	//colocar aqui os campos que podem ser vazios no formulario
	$excecoes_vazio = array('num_cracha');	

	//Verificar campos vazios
	while($vaz = each($_POST)){
		//coloca os campos obrigatorios que estao vazios no vetor		
		if(empty($vaz['value']) && !in_array($vaz['key'],$excecoes_vazio)){						
			$erros[] = $vaz['key'];			
		}														
	}
	
	/*Seleciona o supervisor do estagiario e o nome dele (juntar numa query)*/ 			 
	if(!empty($estagiario)){
		$q_est = "SELECT nome, id_supervisor FROM estagiarios WHERE id = {$estagiario}";	
		$r_est = sql_executa($q_est);
		if(sql_num_rows($r_est) > 0) $c_est = sql_fetch_array($r_est);
	
		$q_superv = "SELECT nome FROM supervisores WHERE id = {$c_est['id_supervisor']}";		
		$r_superv = sql_executa($q_superv);
		if(sql_num_rows($r_superv) > 0) $c_superv = sql_fetch_array($r_superv);
	}
		
	// 3 - Mostra mensagem de erro ou cria query de insercao 
	if(count($erros)>0){
		//essa string é usada pelo javascript no final da pagina para marcar os campos com o asterisco vermelho 
		$string_erros = implode("|",$erros);			
		//mostra mensagem de erro		
		if(count($erros)==1){
			$msg_erro = "Selecione um Estagiário.";
		}
		echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>
					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
				</tr>
			</table>		
		<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
				
	}
}
?>
   <!-- Abas -->	
	<ul class='listaAbas'>
       <li><a id='a1' class='active'>Selecione</a></li>              
   </ul>
   </div>
   
	<form id="frmUsr" name="frmUsr" method="post">
	
	<!-- ============ Conteudo da Primeira ABA ============ --> 	
	<div id="aba1" class='conteudoAba' style='display:block;'>
		<div id="erro"></div>  	 	
  	  	<table width="100%" class='formulario'>
  	  	<tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>		  
      <tr class='specalt'>
        <td width="25%"><span>Estagiário</span></td>
        <td width="75%">        			
        			<?php    
        				echo "<select id='estagiario' name='estagiario' class='select'   onChange=\"preencheSupervisor(this.value,'supervisor');\">";    			        			
        				echo "<option value=''>
	        						-- Selecione --
        						</option>";        				         			
        				$q_estag = "SELECT * FROM estagiarios WHERE status = 1 ORDER BY nome";
        				$r_estag = sql_executa($q_estag);        				       				
        				if(sql_num_rows($r_estag) < 1){
							echo "<span><i>Não foram encontrados estagiários ativos.</i></span>";        				
        				}else{        				       				        					
        					while($c_estag = sql_fetch_array($r_estag)){        					
								echo "<option id='{$c_estag['cracha']}'  value=\"{$c_estag['id']}\"";
                                if($c_estag['id'] == $c_est['id']) {
                                    echo " selected='selected' ";
                                }
								echo ">".($c_estag['nome'])."</option>";
							}							
						}
        			?>					
				</select><span id='sestagiario' class="sErro">&nbsp;*</span>
			</td>        
      </tr>
      <tr class='specalt'>
        <td ><span>Supervisor</span></td>
        <td>
        		<input name="supervisor" id="supervisor" type="text" size='40' value="<?php echo $c_superv['nome']; ?>" disabled>
				<span id='ssupervisor' class="sErro">&nbsp;*</span>
			</td>
       </tr>        
      <tr class='specalt'>
        <td width="25%"><span>Termo</span></td>
        <td width="75%">
        		<select id='termo' name='termo' class='select'>
        			<option value='0'>-- Selecione --</option>
					<option value='1'>Aditivo</option>
					<option value='2'>Bolsista</option>
					<option value='3'>Cadastro Biblioteca</option>        										
					<option value='5'>Certificado</option>        								
					<option value='6'>Checklist Contratação de Bolsista</option>        								
					<option value='7'>Checklist Contratação de Estagiário</option>        								
					<option value='19'>Checklist Renova&ccedil;&atilde;o de Estagiário</option>        								
					<option value='8'>Checklist de Desligamento</option>        								
					<option value='9'>Código de Conduta</option>        								
					<option value='10'>Compromisso Não-Obrigatório</option>
        			<option value='11'>Compromisso Obrigatório</option>
        			<option value='12'>Confidencialidade</option>
        			<option value='18'>Compromisso PIBIC</option>
					<option value='13'>Crachá</option>        								
					<option value='14'>Distrato</option>        								
					<option value='15'>Índice da Pasta</option>
					<option value='17'>Índice da Pasta (Bolsista)</option>
					<option value='16'>Seguro de Vida</option>
				</select><span id='setermo' class="sErro">&nbsp;*</span>
			</td>        
      </tr>
      <tr class='specalt' id="cracha">
        <td ><span>Número do crachá: </span></td>
        <td>
            <input name="num_cracha" id="num_cracha" type="text" size='10' value=''>
            <span id='snum_cracha' class="sErro">&nbsp;*</span>
        </td>
       </tr>        
        
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       </table>
       </div>
            
    </table> 
  <table width="800px" bgcolor="#FFFFFF"><tr align='center'><td>
  <table width="750px" bgcolor="#F5FAFA">
   <tr align='center'><td colspan='2' >
    <input type="button" name="submit" value="Gerar" onClick="geraTermo('termo');">    
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

    $(document).ready(function() {
        $('#cracha').hide();

        // Ao mudar o termo, exibe/esconde o input do numero do cracha
        $('#termo').change(function() {
            if($(this).val() == 13)
                $('#cracha').show();
            else
                $('#cracha').hide();
        });
        // Ao mudar o estagiario, muda o numero do cracha no input
        $('#estagiario').change(function() {
            var num_cracha = $($(this).find("option:selected")).attr('id');
            $("input#num_cracha").val(num_cracha);
        });
    });

	function geraTermo(t){
	
		var id = document.getElementById('estagiario').value;
        // Se nenhum estagiario for selecionado
		if(id == ""){
			alert("Selecione um estagiário!");
			return;
		}
		var termo = document.getElementById(t).value;
        if(termo == 13) {
            var num_cracha = $('#num_cracha').val();
            if(num_cracha == '')
                alert('Número de crachá inválido.');
            // Atualiza o cracha que esta no id do select do estagiario
            else{
                $($("#estagiario").find("option:selected")).attr('id',num_cracha);
                window.open('termo.gerador.php?id='+id+'&t='+termo+'&num_cracha='+num_cracha);
            }
        }
        else
            window.open('termo.gerador.php?id='+id+'&t='+termo);
	}	
</script>
<?php 
include_once('../inc/copyright.php');
?>
</div>
