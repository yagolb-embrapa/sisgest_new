<?php 

$qtd_abas = 4;
require_once("../inc/header.php");

require_once("../classes/DB.php");
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

$query_setores = "  SELECT  distinct st.setor,
                            em.id_setor
                    FROM    emails as em, setores as st
                    WHERE   em.id_setor = st.id and st.setor not like 'SUPORTE'
                    ORDER BY st.setor ASC;";

$setores = DB::fetch_all($query_setores);


?>


<!-- TR de CONTEUDO -->  
<tr>
  <td width='750px' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Finalização de Conta</span>
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
	$excecoes_vazio = array();	

	//Verificar campos vazios
	while($vaz = each($_POST)){
		//coloca os campos obrigatorios que estao vazios no vetor		
		if(empty($vaz['value']) && !in_array($vaz['key'],$excecoes_vazio)){						
			$erros[] = $vaz['key'];			
		}														
	}
	
	/*Seleciona o supervisor do estagiario e o nome dele (juntar numa query)*/ 			 
	if(!empty($estagiario)){
		$q_est = "SELECT nome, id_supervisor, tipo_vinculo, email_embrapa FROM estagiarios WHERE id = {$estagiario}";	
		$r_est = sql_executa($q_est);
		if(sql_num_rows($r_est) > 0) $c_est = sql_fetch_array($r_est);
	
		$tipo_vinculo=($c_est["tipo_vinculo"]=='b')?"Bolsista":"Estagiário";
		
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
				
	}else{
		$query_update = "UPDATE estagiarios SET status = 0 WHERE id = {$estagiario}";		
		$result_update = sql_executa($query_update);			
	
		//Insere o pedido de abertura de contas no bd
		$query = "INSERT INTO pedidos_contas (data, id_estagiario, tipo)  
		VALUES (".time().",'{$estagiario}','F');";						
		$result = sql_executa($query);				
				
		$msg_email = utf8_decode("Bom dia,<br><br>Por gentileza, finalizar a conta de e-mail para o seguinte ESTAGIÁRIO:<br><br>
		Nome: {$c_est['nome']}<br>
		Supervisor: {$c_superv['nome']}<br>
		E-mail: {$c_est['email_embrapa']}<br><br>
		
		Grato.<br>
		RH.");		
		
		$msg_email_all = utf8_decode("Prezados,<br/><br/>Está sendo desligado o seguinte {$tipo_vinculo}:<br/>
		Nome: {$c_est['nome']}<br>
		Supervisor: {$c_superv['nome']}<br>
		E-mail: {$c_est['email_embrapa']}<br><br>
		
		Grato.<br>
		RH.");
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Sisgest - RH <cnptia.sgp@embrapa.br>'."\r\n";
		$headers .= "Reply-To: cnptia.sgp@embrapa.br\r\n";
    	$headers .= "Return-Path: cnptia.sgp@embrapa.br\r\n";

        $query = "  SELECT  email
                    FROM    emails
                    WHERE   id_setor IN  (  SELECT  id
                                            FROM    setores
                                            WHERE   setor = 'SUPORTE');";
        $emails = DB::fetch_all($query);

        foreach($emails as $email) {
            if(mail($email['email'],'Solicita&ccedil;&atilde;o de Finaliza&ccedil;&atilde;o de Conta', $msg_email, $headers)){		
                echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
                    <tr bgcolor='#EFFFF4'>
                    <td align='center'><span align='center' style='color:#296F3E;'>Um e-mail foi enviado ao suporte, solicitando a finalização da conta do estagiário. <br>
                    Estagiário: {$c_est['nome']}<br>
                    Supervisor: {$c_superv['nome']}<br>
                    E-mail: {$c_est['email_embrapa']}</span></td>
                    </tr>
                    </table>		
                    <div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
            }
        }
        
        //mandando emails pro resto dos setores selecionados
        if (count($setoresEmail)>0){
	        for ($i=0;$i<count($setoresEmail);$i++){
	        	$setoresEmail[$i]='id_setor='.$setoresEmail[$i];
	        }
	        
	        unset($emails);
	        $query = "  SELECT  email
	                    FROM    emails WHERE ".implode(" OR ", $setoresEmail);
	        
	        $emails = DB::fetch_all($query);
	        
	        foreach($emails as $email){
	        	if 	(!mail($email['email'],'Finaliza&ccedil;&atilde;o de est&aacute;gio', $msg_email_all, $headers)){
	        		echo "Erro ao enviar email para {$email['email']}<br/>";
	        	}
	        }
        }
			
		unset($_POST);
		unset($estagiario, $supervisor, $c_superv);		
	}
}else{
    extract($_GET);
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#F0F0F0'>
				<td align='center'><span align='center' style='color:black;'>- Um e-mail será enviado ao suporte, solicitando a finalização da conta do estagiário.<br>- O estagiário será desabilitado neste sistema.</span></td>
			</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
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
        <td width="25%"><span>Estagiário</span></td>
        <td width="75%">        			
        			<?php    
        				echo "<select id='estagiario' name='estagiario' class='select'   onChange=\"preencheSupervisor(this.value,'supervisor');\">";    			        			
        				echo "<option value=''>
	        						-- Selecione --
        						</option>";        				         			
        				$q_estag = "SELECT * FROM estagiarios WHERE email_embrapa <> '' AND status = 1 ORDER BY nome";
        				$r_estag = sql_executa($q_estag);        				       				
        				if(sql_num_rows($r_estag) < 1){
							echo "<span><i>Não foram encontrados estagiários com conta.</i></span>";        				
        				}else{        				       				        					
        					while($c_estag = sql_fetch_array($r_estag)){        					
								echo "<option value=\"{$c_estag['id']}\"";
                                if($c_estag['id'] == $c_est['id'])
                                    echo " selected='selected' ";
                                else if($c_estag['id'] == $id)
                                    echo " selected='selected' ";
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
        <?php
            if(isset($id_superv)) {
                $query = "SELECT    nome
                          FROM      supervisores
                          WHERE     id = {$id_superv};";
                $superv = DB::fetch_all($query);

                $c_superv['nome'] = $superv[0]['nome'];
            }
        ?>
        		<input name="supervisor" id="supervisor" type="text" size='40' value="<?php echo $c_superv['nome']; ?>" disabled>
				<span id='ssupervisor' class="sErro">&nbsp;*</span>
			</td>
       </tr>    
       <tr>
       	<td><span>Setores destinatários</span></td>
       	<td>
       		<?php
            foreach($setores as $s) 
                echo '<input type="checkbox" name="setoresEmail[]" value="' . $s['id_setor'] . '"' . ' checked >' . $s['setor'] . '&nbsp;&nbsp;';
            ?>
       	</td>
       </tr> 
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       </table>
       </div>
            
    </table> 
  <table width="800px" bgcolor="#FFFFFF"><tr align='center'><td>
  <table width="750px" bgcolor="#F5FAFA">
   <tr align='center'><td colspan='2' >
    <input type="submit" name="submit" value="Enviar">    
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
