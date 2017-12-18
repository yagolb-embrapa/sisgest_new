<?php 

$qtd_abas = 4;
require_once("../inc/header.php");
require_once("../classes/DB.php");

include("../functions/functions.database.php");
include("../functions/functions.forms.php");


$query_setores = "  SELECT  distinct st.setor,
                            em.id_setor
                    FROM    emails as em, setores as st
                    WHERE   em.id_setor = st.id
                    ORDER BY st.setor ASC;";

$setores = DB::fetch_all($query_setores);
?>


<!-- TR de CONTEUDO -->  
<tr>
  <td width='750px' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Abertura de Conta</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		<script language='javascript'>		
		
		function preencheCampos(idEstag,inputSuperv,inputEmail){			
			ajax.loadValue(inputSuperv,'../functions/ajax.busca.supervisor.php?id='+idEstag);
			ajax.loadValue(inputEmail,'../functions/ajax.sugere.email.php?id='+idEstag);		
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
	
    $setor_escolhido = false;
    foreach($setores as $setor) {
        //colocar aqui os campos que podem ser vazios no formulario
        $excecoes_vazio[] = $setor['setor'];
        if(isset($_POST[$setor['setor']]))
            $setor_escolhido = true;
    }

	//Verificar campos vazios
	while($vaz = each($_POST)){
		//coloca os campos obrigatorios que estao vazios no vetor		
		if(empty($vaz['value']) && !in_array($vaz['key'],$excecoes_vazio)){						
			$erros[] = $vaz['key'];			
		}
	}


	if(!valida($email,'email')) $erros[] = 'email';

    /* VERIFICAR SE O EMAIL JA NAO EXISTE NO LDAP */
    $q_e = "SELECT * FROM estagiarios WHERE email_embrapa = '{$email}'";	
    $r_e = sql_executa($q_e);	
    if(sql_num_rows($r_e)>0) 
        $erros[] = 'email';
	
    if(!$setor_escolhido)
        $erros[] = 'setor';

	if(!empty($estagiario)){		 			 
	
        $q_est = "  SELECT  es.id, nome, es.id_supervisor, es.tipo_vinculo, es.nome_projeto, es.data_nascimento, es.id_instituicao_ensino, es.curso, es.ramal, es.vigencia_inicio, ni.nivel
                    FROM    estagiarios es LEFT JOIN niveis ni ON es.id_nivel = ni.id
                    WHERE   es.id = {$estagiario}";
		$r_est = sql_executa($q_est);
		if(sql_num_rows($r_est) > 0) $c_est = sql_fetch_array($r_est);

        $estag_bolsista = ($c_est['tipo_vinculo'] == 'b') ? 'bolsista' : 'estagiário';

		$q_superv = "SELECT nome FROM supervisores WHERE id = {$c_est['id_supervisor']}";		
		$r_superv = sql_executa($q_superv);
		if(sql_num_rows($r_superv) > 0) $c_superv = sql_fetch_array($r_superv);

        $q_inst = " SELECT  razao_social
                    FROM    instituicoes_ensino
                    WHERE   id = {$c_est['id_instituicao_ensino']};";
        $r_inst = DB::fetch_all($q_inst);
        $c_inst = $r_inst[0]['razao_social'];
	}	
		
	// 3 - Mostra mensagem de erro ou cria query de insercao 
	if(count($erros)>0){
		//essa string é usada pelo javascript no final da pagina para marcar os campos com o asterisco vermelho 
		$string_erros = implode("|",$erros);			
		//mostra mensagem de erro		
		if(count($erros)==1 && $erros[0] == 'email'){
			$msg_aviso = "O campo 'E-mail Sugerido' não foi preenchido corretamente e foi marcado com um asterisco vermelho. Por favor, verifique a disponibilidade dele e se ele se encontra no formato correto.";
        }elseif(in_array('setor',$erros)){
            $msg_aviso = "Selecione pelo menos um setor para enviar o email para abertura de conta.";
        }elseif(count($erros)==1){			
			$msg_aviso = "Um campo não foi preenchido corretamente e foi marcado com um asterisco vermelho. Por favor, verifique-o e tente novamente.";
		}else{
			$msg_aviso = "Alguns campos não foram preenchidos corretamente e foram marcados com um asterisco vermelho. Por favor, verifique-os e tente novamente.";		
		}
		$bgcolor = "#FFEFEF";
		$fontcolor = "red"; 				
	}else{
		$tempo = time();
		//Insere o pedido de abertura de contas no bd
		$query = "INSERT INTO pedidos_contas (data, id_estagiario, email_sugerido, tipo)  
		VALUES ({$tempo},{$estagiario},'{$email}', 'A');";		
		$result = sql_executa($query);
		
		if(!$result){			
			$msg_aviso = "Não foi possível concluir o pedido. Por favor, tente novamente.";
			$bgcolor = "#FFEFEF";
			$fontcolor = "red";						
        }else{
            $msg_email_suporte = utf8_decode("Prezados,<br><br>Por gentileza, abrir conta de e-mail para o seguinte " . $estag_bolsista . ":<br><br>
                Nome: " . $c_est['nome'] . "<br>
                Supervisor: " . $c_superv['nome'] . "<br>
                E-mail: {$email}<br><br>		
                Grato.<br>
                RH.");
            $msg_email =  utf8_decode("Prezados,<br><br>Hoje começa a trabalhar o seguinte " . $estag_bolsista . ":<br><br>
                Nome: " . strtoupper($c_est['nome']) . "<br>
                Supervisor: {$c_superv['nome']}<br>
                Ramal: {$c_est['ramal']}<br>
                Projeto: {$c_est['nome_projeto']}<br>
                Data de nascimento: " . formata($c_est['data_nascimento'], 'redata') . "<br>
                Instituição de Ensino: {$c_inst}<br>
                Curso: {$c_est['curso']}<br>
                Nível: {$c_est['nivel']}<br>
                <br>
                Vigência Início: " . formata($c_est['vigencia_inicio'], 'redata') . "<br>
                <br><br>
                RH<br>
                <br>
                Embrapa Informática Agropecuária");

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Sisgest - RH <cnptia.sgp@embrapa.br>'."\r\n";
            $headers .= "Reply-To: cnptia.sgp@embrapa.br\r\n";
            $headers .= "Return-Path: cnptia.sgp@embrapa.br\r\n";


            $emails_enviados = array();
            $msg_aviso = "Emails enviados:<br>";

            foreach($setores as $setor) {
                if(isset($_POST[$setor['setor']])) {
                    $query = "  SELECT  email
                                FROM    emails
                                WHERE   id_setor IN (SELECT  id
                                                     FROM    setores
                                                     WHERE   setor = '{$setor['setor']}');";
                    $emails = DB::fetch_all($query);

                    $emails_enviados = array();

                    //Enviando email para o setor
                    foreach($emails as $email) {
                        if($setor['setor'] == 'SUPORTE') {
                            if(mail($email['email'],'Solicita&ccedil;&atilde;o de Abertura de Conta de Email', $msg_email_suporte, $headers)){				
                                $bgcolor = "#EFFFF4";
                                $fontcolor = "#296F3E";
                                $emails_enviados[] = $email['email'];
                            }
                        }
                        else {
                            if(mail($email['email'],"Início Novo {$estag_bolsista}", $msg_email, $headers)){				
                                $bgcolor = "#EFFFF4";
                                $fontcolor = "#296F3E";
                                $emails_enviados[] = $email['email'];
                            }
                        }
                    }

                    if(sizeof($emails_enviados) == 0)
                        $msg_aviso .= "Nenhum email enviado para {$setor['setor']}<br>";
                    else {
                        $msg_aviso .= $setor['setor'] . ':';
                        foreach($emails_enviados as $email)
                            $msg_aviso .= " {$email}";
                        if($setor['setor'] == 'SUPORTE') {
                            $update_email = "UPDATE estagiarios
                                             SET    email_embrapa = '{$_POST['email']}'
                                             WHERE  id = {$c_est['id']};";
                            DB::execute($update_email);
                        }

                    }
                    $msg_aviso .= '<br>';
                }
            }

            unset($_POST);
            unset($estagiario, $email, $supervisor, $c_superv);		
        }
    }
}else{
    $bgcolor = "#F0F0F0";
    $fontcolor = "black";
    $msg_aviso  = "Um e-mail será enviado aos setores escolhidos solicitando a abertura de conta do estagiário.";			
}

echo "<table width='100%' style='border:1px solid black;' cellspacing='0' cellpadding='5' height='50px'>						
    <tr bgcolor='{$bgcolor}'>
    <td align='center'><span align='center' style='color:{$fontcolor};'>{$msg_aviso}</span></td>
    </tr>
    </table>		
    <div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";


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
        				echo "<select id='estagiario' name='estagiario' class='select'  onChange=\"preencheCampos(this.value,'supervisor','email');\">";    			        			
        				echo "<option value=''>
	        						-- Selecione --
        						</option>";        				         			
        				$q_estag = "SELECT * FROM estagiarios WHERE email_embrapa = '' AND status = 1 ORDER BY nome";
        				$r_estag = sql_executa($q_estag);        				       				
        				if(sql_num_rows($r_estag) < 1){
							echo "<span><i>Não foram encontrados estagiários sem conta.</i></span>";        				
        				}else{        				       				        					
        					while($c_estag = sql_fetch_array($r_estag)){        					
								echo "<option value=\"{$c_estag['id']}\"";
								if($c_estag['id'] == $c_est['id']) echo " selected='selected' ";
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
        <td ><span>E-mail Sugerido</span></td>
        <td><input name="email" id="email" type="text" size='30' value="<?php echo $email; ?>"><span id='semail' class="sErro">&nbsp;*</span>
				<input type='button' value='Verificar Disponibilidade' onClick="disponibEmail('divDisponib','email');"><div id='divDisponib'></div>        
        </td>
       </tr>

       <tr class='specalt'>
        <td ><span>Setores Destinatários</span></td>
        <td>

            <?php
            foreach($setores as $s) 
                echo '<input type="checkbox" name="' . $s['setor'] . '" ' . 'value="' . $s['id_setor'] . '"' . ' checked >' . $s['setor'] . '&nbsp;&nbsp;';
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
    <input type="submit" name="submit" value="Enviar"/>    
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

        //Selecao de funcionarios no filtro
        $("a#marcar_todos").click(function() {
            $("select#setores option").attr('selected', 'selected');

            return false;
            }); 

        $("a#inverter_selecao").click(function() {
            $("select#setores option").each(function() {
                $(this).attr('selected', !$(this).attr('selected'));
            }); 

            return false;
        }); 

    });

</script>
<?php 
include_once('../inc/copyright.php');
?>
</div>
