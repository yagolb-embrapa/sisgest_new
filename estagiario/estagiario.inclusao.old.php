<?php 

$qtd_abas = 4;
require_once("../inc/header.php");
require_once("../classes/DB.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>


<!-- TR de CONTEUDO -->  
<tr>
  <td width='750px' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Inclusão de Estagiário</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		<script language='javascript'>
		function calculaVigencia(ini, fim, sem, ano, result){
			//pega o inicio e termino da vigencia
			var i = document.getElementById(ini).value;
			var f = document.getElementById(fim).value;			
			
			//verificando qual semestre de termino do curso
			var s1 = document.getElementById("tcurso1").checked;			
			var s2 = document.getElementById("tcurso2").checked;
			if(s1 == true) s = 1;
			else s = 2;
			
			//pega o ano de termino do curso			
			var a = document.getElementById(ano).value;						
			ajax.loadDiv(result,'../functions/ajax.calcula.vigencia.php?ini='+i+'&fim='+f+'&sem='+s+'&ano='+a);
		}					
	</script>
		<?php
		

$submit = $_POST['submit'];
unset($string_erros);
if($submit){
	extract($_POST);// 1 - Pega tb todos os valores do formulario
	$municipio = $_POST['municipio'];//pega manualmente pq como esta em outra pagina, nao ta pegando com a linha acima
	
	//colocar aqui os campos que podem ser vazios no formulario
	$excecoes_vazio = array("telres","telcel","emaile","complemento","ra","ramal","numero_projeto","nome_projeto","observacao","cargaoutra","agencia","conta","banco","cracha","beneficiario0","beneficiario1","beneficiario2","beneficiario3","beneficiario4","parentesco0","parentesco1","parentesco2","parentesco3","parentesco4", "remuneracao","fumante");

    if($tipo_vinculo != 'b'){
        $excecoes_vazio[] = "id_bolsista";
        $id_bolsista = 'null';
        
    }
    $excecoes_vazio[] = "termo_aceite";
    $cracha = ($cracha == '') ? 'null' : $cracha;
	
	//Colocando os horarios como excecao	
	for($j=0;$j<5;$j++){
		for($i=0;$i<4;$i++){
			$excecoes_vazio[] = "he".$j.$i."h";
			$excecoes_vazio[] = "he".$j.$i."m";
			$excecoes_vazio[] = "ha".$j.$i."h";
			$excecoes_vazio[] = "ha".$j.$i."m"; 		 		
		}
	}
	
	
	//substitui virgula por ponto 
	$remuneracao = str_replace(",",".",$remuneracao);		
	
	//Verificar campos vazios
	while($vaz = each($_POST)){
		//coloca os campos obrigatorios que estao vazios no vetor		
		if(empty($vaz['value']) && !in_array($vaz['key'],$excecoes_vazio)){						
			$erros[] = $vaz['key'];			
		}														
	}
	//vigenciai e vigenciaf compartilham o mesmo aviso de campo nao preenchido 
	if(empty($vigenciai) && !empty($vigenciaf)) $erros[] = 'vigenciaf';

	//tratando carga horaria
	if($carga == -1){		
		$ocarga = $_POST['cargaoutra'];
		if(empty($ocarga)) $erros[] = 'cargaoutra';
		$cargahoraria = $ocarga;		
	}else{
		$cargahoraria = $carga;	
	}	

    //verificando se o sexo foi definido
    if($sexo != 'f' && $sexo != 'm')
        $erros[] = 'sexo';

	//validando formato das datas  
	if(!valida($datanasc,'data')) $erros[] = 'datanasc';
	if(!valida($dataexpedicao,'data')) $erros[] = 'dataexpedicao';
	if(!valida($vigenciai,'data')) $erros[] = 'vigenciai';
	if(!valida($vigenciaf,'data')) $erros[] = 'vigenciaf';
	//validando outros formatos
	//if(!valida($cpf,'cpf')) $erros[] = 'cpf';
	if(!valida($cep,'cep')) $erros[] = 'cep';
	if(!valida($email,'email')) $erros[] = 'email';
	if(!valida($emaile,'email') && !empty($emaile)) $erros[] = 'emaile';
							
	//Inicio e termino do curso no formato AAAA-MM (ano e semestre, na verdade)
	$inicio_curso = $anoicurso."-".$icurso;	
	$termino_curso = $anotcurso."-".$tcurso;
	
	$q_cpf = "SELECT * FROM estagiarios WHERE cpf = '{$cpf}'";	
	$r_cpf = sql_executa($q_cpf);
	if(sql_num_rows($r_cpf)>0) $erros[] = 'cpf'; 
		
    //Salva beneficiarios e parentescos
    $beneficiario[0] = $beneficiario0;
    $beneficiario[1] = $beneficiario1;
    $beneficiario[2] = $beneficiario2;
    $beneficiario[3] = $beneficiario3;
    $beneficiario[4] = $beneficiario4;
    $parentesco[0] = $parentesco0;
    $parentesco[1] = $parentesco1;
    $parentesco[2] = $parentesco2;
    $parentesco[3] = $parentesco3;
    $parentesco[4] = $parentesco4;

	// 3 - Mostra mensagem de erro ou cria query de insercao 
	if(count($erros)>0){
		//essa string é usada pelo javascript no final da pagina para marcar os campos com o asterisco vermelho 
		$string_erros = implode("|",$erros);			
		//mostra mensagem de erro		
		if(count($erros)==1 && $erros[0] != 'cpf'){
			$msg_erro = "Um campo não foi preenchido corretamente e foi marcado com um asterisco vermelho. Por favor, verifique-o e tente novamente.";
		}elseif(count($erros)==1 && $erros[0] == 'cpf'){
			$msg_erro = "O usuário já está cadastrado no sistema.";					
		}elseif(in_array('cpf',$erros)){			
			$msg_erro = "O usuário já está cadastrado no sistema; Alguns campos não foram preenchidos corretamente e foram marcados com um asterisco vermelho. Por favor, verifique-os e tente novamente.";				
		}else{
			$msg_erro = "Alguns campos não foram preenchidos corretamente e foram marcados com um asterisco vermelho. Por favor, verifique-os e tente novamente.";		
		}
        if(count($erros) < 4)
            $msg_erro .= "\nErro em : " .  $string_erros;
		echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>
					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
				</tr>
			</table>		
		<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
	}else{
        $query = "INSERT INTO estagiarios (nome, data_nascimento, nacionalidade, id_estado_civil, cpf, rg, data_expedicao, orgao_expedidor, endereco, complemento, bairro, cep, id_municipio, uf, tel_residencial, tel_celular, email, email_embrapa, agencia, conta_corrente, id_banco, id_instituicao_ensino, curso, inicio_curso, termino_curso, id_nivel, ra, estagio_obrigatorio, vigencia_inicio, vigencia_fim, remuneracao, cracha, participou_piec, id_origem_recursos, carga_horaria, id_supervisor, area_atuacao, numero_projeto, ramal, nome_projeto, status, sexo, tipo_vinculo, id_bolsista, termo_aceite, fumante) 
                  VALUES('$nome', '".formata($datanasc,'data')."', '$nacionalidade', $estadocivil, '$cpf', '$rg', '".formata($dataexpedicao,'data')."', '$orgaoexpedidor', '$endereco', '$complemento', '$bairro', '$cep', $municipio, '$uf', '$telres', '$telcel', '$email', '$emaile', '$agencia', '$conta', $banco, $instituicao, '$curso','$inicio_curso', '$termino_curso', $nivel, '$ra', '$obrig', '".formata($vigenciai,'data')."', '".formata($vigenciaf,'data')."', $remuneracao, $cracha, '$piec', $origem, $cargahoraria, $supervisor, '$area', '$numero_projeto', '$ramal', '$nome_projeto', $status, '$sexo', '$tipo_vinculo', $id_bolsista, '$termo_aceite', '$fumante');";

		$result = sql_executa($query);

		//$query_horarios = "INSERT INTO horarios () VALUES ()";
		//mensagem de sucesso		
		if($result){
			//pegando id do estagiario inserido
			$query_estag = "SELECT id FROM estagiarios ORDER BY id DESC LIMIT 1";
			$result_estag = sql_executa($query_estag);
			$campo_estag = sql_fetch_array($result_estag);			
			//salvando horarios
			if(!salva_horarios ($campo_estag['id'], $_POST)){
				$msg_hor = "No entanto, não foi possível inserir os horários do estagiário. Por favor, tente novamente.";
			}

            for($i = 0; $i < 5; $i++) {
                if($beneficiario[$i] != ''){
                    if($parentesco[$i] == '')
                        $erros[] = 'parentesco'.$i;  
                    else {
                        $query = "INSERT INTO beneficiarios(id_estagiario, nome, parentesco)
                                  VALUES({$campo_estag['id']},'{$beneficiario[$i]}', '{$parentesco[$i]}');";
                        $j = $i+1;
                        if(!DB::execute($query))
                            $msg_hor = "No entanto, não foi possível inserir o beneficiário {$j} do estagiário. Por favor, tente novamente.";
                    }
                }
            }
			
            //Recuperando beneficiarios para exibicao em ordem alfabetica e permitir alteracao
            $query = "SELECT id, nome as beneficiario_nome, parentesco
                      FROM   beneficiarios
                      WHERE  id_estagiario = {$id}
                      ORDER BY nome DESC;";
            $benef = DB::fetch_all($query);
            $i=0;
            foreach($benef as $b) {
                $beneficiario[$i] = $b['beneficiario_nome'];
                $parentesco[$i] = $b['parentesco'];
                $id_benef[$i] = $b['id'];
                $i++;
            }

			echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#EFFFF4'>
					<td align='center'><span align='center' style='color:#296F3E;'>Estagiário incluído com sucesso!{$msg_hor}</span></td>
				</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
			
			unset($_POST);
			unset($nome,$datanasc,$nacionalidade,$estadocivil,$cpf,$rg,$dataexpedicao,$orgaoexpedidor,$endereco,$complemento,$bairro,$cep, $municipio, $uf, $telres, $telcel, $email, $emaile, $agencia, $conta, $banco, $instituicao, $curso,$inicio_curso,$termino_curso, $nivel, $ra, $vigenciai,$vigenciaf,$remuneracao, $cracha, $piec, $origem, $cargahoraria, $supervisor, $area, $numero_projeto, $ramal, $nome_projeto,$anoicurso,$icurso,$termino_curso,$anotcurso,$tcurso);
		}
        else{
            echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
                <tr bgcolor='#FFEFEF'>
                <td align='center'><span align='center' style='color:red;'>Não foi possível adicionar o estagiário, verifique se os campos com (*) foram preenchidos corretamente</span></td>
                </tr>
                </table>		
                <div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
        }
	}
}
?>
   <!-- Abas -->	
	<ul class='listaAbas'>
       <li><a href="javascript: mostrarAba('aba1','a1');" id='a1' class='active'>Identificação</a></li>
       <li><a href="javascript: mostrarAba('aba2','a2');" id='a2'>Curso</a></li>
       <li><a href="javascript: mostrarAba('aba3','a3');" id='a3'>Banco</a></li>
       <li><a href="javascript: mostrarAba('aba4','a4');" id='a4'>Estágio</a></li>       
   </ul>
   </div>
   
	<form id="frmUsr" name="frmUsr" method="post">
	
	<!-- ============ Conteudo da Primeira ABA ============ --> 	
	<div id="aba1" class='conteudoAba' style='display:block;'>
		<div id="erro"></div>  	 	
  	  	<table width="100%" class='formulario'>
  	  	<tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>		  
      <tr class='specalt'>
        <td width="25%"><span>Nome(*)</span></td>
        <td width="75%"><input name="nome" id="nome" type="text" size='40' maxlength='50' value="<?php echo $nome; ?>"><span id='snome' class="sErro">&nbsp;*</span></td>        
      </tr>           
      <tr class='specalt'>
        <td><span>Sexo(*)</span></td>
        <td><input name="sexo" type="radio" id="masculino" value="m" <?php if($sexo=="m") echo "checked";?> >        		
        			<label for="masculino"><span>M</span></label>
            <input name="sexo" type="radio" id="feminino" value="f" <?php if($sexo=="f") echo "checked";?> >
        			<label for="feminino"><span>F</span></label>
        	<span id='ssexo' class="sErro">&nbsp;*</span>        			
      </tr>
      <tr class='specalt'>     
        <td ><span>Data de nascimento(*)</span></td>       
        <td><input name="datanasc" type="text" id="datanasc" size='10' maxlength="10" value="<?php echo $datanasc; ?>"><span id='sdatanasc' class="sErro">&nbsp;*</span></td>
       </tr>
		<tr class='specalt'>
        <td ><span>Nacionalidade(*)</span></td>
        <td><input name="nacionalidade" id="nacionalidade" type="text" size="10" maxlength="20" value="<?php echo $nacionalidade; ?>"><span id='snacionalidade' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Telefone Residencial</span></td>
        <td><input name="telres" id="telres" type="text" size='15' maxlength='12' onKeyPress="mascara(this, mtelefone);" value="<?php echo $telres; ?>"><span id='stelres' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Telefone Celular</span></td>
        <td><input name="telcel" id="telcel" type="text" size='15' maxlength='12' onKeyPress="mascara(this, mtelefone);" value="<?php echo $telcel; ?>"><span id='stelcel' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>E-mail Pessoal(*)</span></td>
        <td><input name="email" id="email" type="text" size='30' value="<?php echo $email; ?>"><span id='semail' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td><span>Fumante(*)</span></td>
        <td><input name="fumante" type="radio" id="fumante_sim" value='t' <?php if($fumante=='t') echo "checked";?> >        		
        			<label for="fumante_sim"><span>Sim</span></label>
            <input name="fumante" type="radio" id="fumante_nao" value='f' <?php if($fumante!='t') echo "checked";?> >
        			<label for="fumante_nao"><span>Não</span></label>
       </tr>
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>      
       <tr class='specalt'>
        <td ><span>RG(*)</span></td>
        <td><input name="rg" id="rg" type="text" size='15' maxlength='15' value="<?php echo $rg; ?>"><span id='srg' class="sErro">&nbsp;*</span></td>
       </tr>               
		 <tr class='specalt'>
        <td ><span>Data de expedição(*)</span></td>
        <td><input name="dataexpedicao" id="dataexpedicao" type="text"  size='10' maxlength="10" value="<?php echo $dataexpedicao; ?>"><span id='sdataexpedicao' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Órgão expedidor(*)</span></td>
        <td><input name="orgaoexpedidor" id="orgaoexpedidor" type="text" value="<?php echo $orgaoexpedidor; ?>"><span id='sorgaoexpedidor' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>CPF(*)</span></td>
        <td><input name="cpf" id="cpf" type="text" size='15' maxlength="14" onKeyPress="mascara(this, mcpf);" value="<?php echo $cpf; ?>"><span id='scpf' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Estado Civil(*)</span></td>
        <td><select id="estadocivil" name="estadocivil" class="select">
        			<option value="">-- Estado Civil --</option>
        			<?php
        				$ecs = Register::filter('estado_civil');       				        					
        				foreach($ecs as $ecivil){
							echo "<option value='{$ecivil->id}'"; 
							if($ecivil->id==$estadocivil) echo " selected='selected' ";						
							echo ">".($ecivil->estado_civil)."</option>";
						}
        			?>					
				</select>
				<span id='sestadocivil' class="sErro">&nbsp;*</span>
			</td>
       </tr>
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>
       <tr class='specalt'>
        <td><span>Endereço(*)</span></td>
        <td><input name="endereco" id="endereco" type="text" size="40" maxlength="50"  value="<?php echo $endereco; ?>"><span id='sendereco' class="sErro">&nbsp;*</span></td>
      </tr>
        <tr class='specalt'>
        <td ><span>Complemento</span></td>
        <td><input name="complemento" id="complemento" type="text" size="40" maxlength="50" value="<?php echo $complemento; ?>"><span id='scomplemento' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>CEP(*)</span></td>
        <td><input name="cep" id="cep" type="text" size="15" maxlength="9" onKeyPress="mascara(this, mcep);"  value="<?php echo $cep; ?>"><span id='scep' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Bairro(*)</span></td>
        <td><input name="bairro" id="bairro" type="text"  value="<?php echo $bairro; ?>"><span id='sbairro' class="sErro">&nbsp;*</span></td>
       </tr>       
       <tr  class='specalt'>
        <td><span>UF(*)</span></td>
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
        <td><span>Município(*)</span></td>
        <td><div style="display:inline;" id="divMunic"></div>
          <script language="JavaScript" type="text/javascript">          	
		  		ajax.loadDiv('divMunic','../functions/ajax.municLoad.php?uf=<?php echo $uf."&mun=".$municipio;?>');		  		
		  	 </script><span id='smunicipio' class="sErro">&nbsp;*</span></td>	    
       </tr>

       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>

       <tr>
        <td colspan='2'>
            <table width='100%' class='formulario' align='center' style='border:none;'>       
                <tbody>
                <tr align='center'><td colspan='2'><div align='center' style='margin: 0 0 0px 0; padding: 2px 2px 2px 2px;'></div></td></tr>	
                <tr class='specalt' align='center'><td colspan='5' align='center'><span><b>Beneficiários do Seguro de Vida</b></span></td></tr>
                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

                <tr align="center">
                    <td width="50%"><span><b>Nome</b></span></td>
                    <td width="50%"><span><b>Parentesco</b></span></td>
                </tr>
                <?php
                    for($i = 0; $i < 5; $i++) {
                        echo "                <tr align='center'>\n";
                        echo "                  <td><input id='beneficiario{$i}' name='beneficiario{$i}' value='{$beneficiario[$i]}' size='40' maxlength='40' /></td>\n";
                        echo "                  <td><input id='parentesco{$i}' name='parentesco{$i}' value='{$parentesco[$i]}' size='40' maxlength='40' /></td>\n";
                        echo "                </tr>\n";
                    }
                ?>
                </tbody>
            </table>
        </td>
       </tr>

       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
     </table></div>    
       
        
       <!-- ============ Conteudo da Segunda ABA  ============ -->
       <div id="aba2" class='conteudoAba'>
       <table width="100%" class='formulario'>       	
  	  	<tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>
       <tr class='specalt'>
        <td width="25%"><span>Nível(*)</span></td>
        <td><select id="nivel" name="nivel" class="select">
        			<option value="">-- Nível --</option>
        			<?php
        				$niv = Register::filter('niveis', array('order' => array('id' => 'ASC')));       				        					
        				foreach($niv as $niveis){
							echo "<option value='{$niveis->id}'";
							if($niveis->id == $nivel) echo " selected='selected' ";
							echo ">".($niveis->nivel)."</option>";
						}
        			?>					
				</select><span id='snivel' class="sErro">&nbsp;*</span>
			</td>
       </tr>
        <tr class='specalt'>
        <td ><span>Curso(*)</span></td>
        <td><input name="curso" id="curso" type="text"  size="30" maxlength="50" value="<?php echo $curso; ?>">
        <span id='scurso' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Instituição de ensino(*)</span></td>
        <td><select id="instituicao" name="instituicao" class="select">
        			<option value="">-- Instituição --</option>
        			<?php
        				$query_inst = "SELECT * FROM instituicoes_ensino ORDER BY razao_social";
        				//$inst = Register::filter('instituicoes_ensino', array('depth' => 0));
        				$result_inst = sql_executa($query_inst);
        				while($campo_inst = sql_fetch_array($result_inst)){
							echo "<option value='{$campo_inst['id']}'";
							/*if($instituicoes->id == $instituicao) echo " selected='selected' ";
							echo ">".($instituicoes->razao_social)."</option>";*/
							if($campo_inst['id'] == $instituicao) echo " selected='selected' ";
							echo ">".$campo_inst['razao_social']."</option>";							
						}																		
        			?>					
				</select><span id='sinstituicao' class="sErro">&nbsp;*</span>
			</td>
       </tr>
        <tr>
			<td width="25%"><span>Início do Curso(*)</span></td>
         <td width="75%">
         	<input name="icurso" id="icurso1" type="radio" value="01" <?php if($icurso != "02" ) echo " checked ";?>><label for="icurso1">1º</label> 
        		<input name="icurso"  id="icurso2" type="radio" value="02" <?php if($icurso == "02") echo " checked ";?>><label for="icurso2">2º</label>
        		<span>&nbsp;&nbsp;Semestre do ano de </span><input name="anoicurso" id="anoicurso" type="text" size="10" maxlength="4" value="<?php echo $anoicurso; ?>"   onKeyPress='mascara(this, mnum);'>        		
				<span id='sanoicurso' class="sErro">&nbsp;*</span><span id='sicurso' class="sErro"></span>        	
        	</td>      
      </tr>
        <tr>
			<td width="25%"><span>Término do Curso(*)</span></td>
         <td width="75%">
         	<input name="tcurso" id="tcurso1" type="radio" value="01" <?php if($tcurso != "02") echo " checked ";?>><label for="tcurso1">1º</label> 
        		<input name="tcurso"  id="tcurso2" type="radio" value="02" <?php if($tcurso == "02") echo " checked ";?>><label for="tcurso2">2º</label>
        		<span>&nbsp;&nbsp;Semestre do ano de </span><input name="anotcurso" id="anotcurso" type="text" size="10" maxlength="4" value="<?php echo $anotcurso; ?>"   onKeyPress='mascara(this, mnum);'  onBlur="calculaVigencia('vigenciai','vigenciaf','tcurso','anotcurso', 'divVigencia');" >        		
				<span id='sanotcurso' class="sErro">&nbsp;*</span><span id='stcurso' class="sErro"></span>        	
        	</td>      
      </tr>
        <tr class='specalt'>
        <td ><span>RA</span></td>
        <td><input name="ra" id="ra" type="text" size="10" maxlength="10" value="<?php echo $ra; ?>">
        <span id='sra' class="sErro">&nbsp;*</span></td>        
       </tr>        
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>
       <tr><td colspan='2'>
       <!-- Tabela de HORARIOS -->      
		<?php 
			echo tabela_horarios("das Aulas","ha");			
		?>
  			</td></tr> 
        <tr>
            <td><span>Observação: </span></td>
            <td><textarea name="observacao" id="observacao" cols='60' rows='4'><?php echo $observacao; ?></textarea>
            <span id='sobservacao' class="sErro">&nbsp;*</span></td>        
        </tr>

       </table>
       
       </div>
        
		<!-- ============ Conteudo da terceira ABA ============ -->       
       <div id="aba3" class='conteudoAba'> 
       <table width="100%" class='formulario'>
  	  	 <tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>	
        <tr class='specalt'>
        <td width="25%"><span>Agência</span></td>
        <td width="75%"><input name="agencia" id="agencia" type="text"  value="<?php echo $agencia; ?>">
        <span id='sagencia' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Conta Corrente</span></td>
        <td><input name="conta" id="conta" type="text"  value="<?php echo $conta; ?>">
        <span id='sconta' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Banco</span></td>
        <td><select id="banco" name="banco" class="select">
        			<option value='null'>-- Banco --</option>
        			<?php
        				$bnc = Register::filter('bancos');       				        					
        				foreach($bnc as $bancos){
							echo "<option value='{$bancos->id}'";
							if($bancos->id == $banco) echo " selected='selected' ";
							echo ">".($bancos->banco)." ({$bancos->codigo_banco})</option>";
						}
        			?>					
				</select>
				<span id='sbanco' class="sErro">&nbsp;*</span>
			</td>
       </tr>
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       </table>
       </div>

	     <!-- ============ Conteudo da quarta ABA  ============ -->  
       <div id="aba4" class='conteudoAba'>
       <table width="100%" class='formulario'>
       <tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>

        <tr class='specalt'>
        <td ><span>Ativo</span></td>
        <td><input name="status" type="radio" id="status1" value="1" <?php if($status!="0") echo "checked"; ?> >        		
        			<label for="status1"><span>Sim</span></label>
        		<input name="status" type="radio" id="status0" value="0" <?php if($status=="0") echo "checked";?> >
        			<label for="status0"><span>Não</span></label>
        	<span id='sstatus' class="sErro">&nbsp;*</span>        			
       </tr>
      <tr class='specalt'>
        <td><span>Tipo de Vínculo</span></td>
        <td><input name="tipo_vinculo" type="radio" id="vinc_estagiario" value="e" <?php if($tipo_vinculo!="b") echo "checked"; ?> >        		
        			<label for="estagiario"><span>Estagiário</span></label>
            <input name="tipo_vinculo" type="radio" id="vinc_bolsista" value="b" <?php if($tipo_vinculo=="b") echo "checked"; ?> >
        			<label for="bolsista"><span>Bolsista</span></label>
        	<span id='stipo_vinculo' class="sErro">&nbsp;*</span>        			
      </tr>
      <tr class='specalt'  id="tipo_bolsa">
        <td><span>Tipo de Modalidade</span></td>
        <td><select id="id_bolsista" name="id_bolsista" class="select">
                <option value="">-- Modalidade --</option>
        	    <?php
        	        $modalidades = Register::filter('modalidades_bolsista', array('order' => array('nome' => 'ASC')));       				        					
        	        foreach($modalidades as $mod){
				    echo "<option value='{$mod->id}'";
				    if($mod->id == $id_bolsista) echo " selected='selected' ";
				        echo ">".($mod->nome)."</option>";
			     	}
        	    ?>					
		    </select>
	        <span id='sid_bolsista' class="sErro">&nbsp;*</span>
        </td>
       </tr>
      <tr class='specalt'  id="trTermoAceite">
        <td><span>Termo de Aceite</span></td>
        <td> 
			<input type="text" name="termo_aceite" id="termo_aceite" value="<?php echo $termoAceite; ?>" />	        
        </td>  
      </tr>
       <tr class='specalt' id="tipo_estagio">
        <td ><span>Tipo do Estágio</span></td>
        <td><input name="obrig" type="radio" id="obrigs" value="S" <?php if($obrig=="S") echo "checked"; ?> >        		
        			<label for="obrigs"><span>Obrigatório</span></label>
        		<input name="obrig" type="radio" id="obrign" value="N" <?php if($obrig!="S") echo "checked";?> >
        			<label for="obrign"><span>Não Obrigatório</span></label>
        	<span id='sobrig' class="sErro">&nbsp;*</span>        			
        </td>        
       </tr>
		<tr class='specalt'>
        <td ><span>Vigência(TCE) (*)</span></td>
        <td><input name="vigenciai" id="vigenciai" type="text" size="10"  maxlength="10" onBlur="calculaVigencia('vigenciai','vigenciaf','tcurso','anotcurso', 'divVigencia');"  value="<?php echo $vigenciai; ?>">
        		<span>&nbsp;&nbsp;a&nbsp;&nbsp;</span>
        		<input name="vigenciaf" id="vigenciaf" type="text" size="10"  maxlength="10" onBlur="calculaVigencia('vigenciai','vigenciaf','tcurso','anotcurso', 'divVigencia');"  value="<?php echo $vigenciaf; ?>">
			<span id='svigenciaf' class="sErro">&nbsp;*</span><span id='svigenciai' class="sErro"></span>
			<div id='divVigencia'></div>			
        	</td>
       </tr>       
       <tr class='specalt'>
        <td ><span>Área de atuação(*)</span></td>
        <td><input name="area" id="area" type="text" size='25' maxlength='30' value="<?php echo $area; ?>">
        <span id='sarea' class="sErro">&nbsp;*</span></td>
       </tr>
		<tr>
			<td width="25%"><span>Carga Horária(*)</span></td>
         <td width="75%">
         	<input name="carga" id="carga20" type="radio" value="20" <?php if($carga == 20 || empty($carga)) echo " checked ";?> onClick="document.getElementById('outracarga').style.display = 'none';"><label for="carga20">20 horas/semana</label> 
        		<input name="carga" id="carga30" type="radio" value="30" <?php if($carga == 30) echo " checked ";?> onClick="document.getElementById('outracarga').style.display = 'none';"><label for="carga30">30 horas/semana</label>
        		<input name="carga" id="outra" type="radio" value="-1" <?php if($carga == -1) echo " checked ";?> onClick="document.getElementById('outracarga').style.display = 'block';"><label for="outra">Outra</label>
        		<div align='right' id='outracarga' style='width:430px;<?php if($carga != -1) echo "display:none;"; ?>'><span>(<input name="cargaoutra" id="cargaoutra" type="text" size='1' maxlength='2' value="<?php echo $ocarga; ?>"  onKeyPress='mascara(this, mnum);'> horas/semana)</span>       		
        		<span id='scargaoutra' class="sErro">&nbsp;*</span></div>        		
        	</td>      
      </tr>
      <tr><td width="25%"></td><td width="75%" align="center"><div id="outra" style="display:none;">Carga <input type="text"></input></div></td></tr>
       <tr class='specalt'>
        <td ><span>Remuneração(*sem decimal)</span></td>
        <td><input name="remuneracao" id="remuneracao" type="text" size="6" maxlength="8"  value="<?php echo $remuneracao; ?>" onKeyPress='mascara(this, mnum);'>
        <span id='sremuneracao' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Origem dos Recursos(*)</span></td>
        <td><select id="origem" name="origem" class="select">
        			<option value="">-- Origem --</option>
        			<?php
        				$orig = Register::filter('origens_recursos');       				        					
        				foreach($orig as $origens){
							echo "<option value='{$origens->id}'";
							if($origens->id == $origem) echo " selected='selected' ";
							echo ">".($origens->origem)."</option>";
						}
        			?>
				</select><span id='sorigem' class="sErro">&nbsp;*</span>
			</td>
       </tr>  
       <tr class='specalt'>
        <td ><span>Crachá</span></td>
        <td><input name="cracha" id="cracha" type="text" size="6" maxlength="3" onKeyPress='mascara(this, mnum);' value="<?php if($cracha != 'null') echo $cracha; ?>">
        <span id='scracha' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Participou do PIEC?</span></td>
        <td><input name="piec" type="radio" id="piecn" value="N" <?php if($piec!="S") echo "checked";?> >
        			<label for="piecn"><span>Não</span></label>        			 
        		<input name="piec" type="radio" id="piecs" value="S" <?php if($piec=='S') echo "checked"; ?> >        		
        			<label for="piecs"><span>Sim</span></label>
        	<span id='spiec' class="sErro">&nbsp;*</span>        			
        </td>        
       </tr>
       <tr class='specalt'>
        <td ><span>Supervisor(*)</span></td>
        <td><select id="supervisor" name="supervisor" class="select">
        			<option value="">-- Supervisor --</option>
        			<?php
        				$superv = Register::filter('supervisores', array('order' => array('nome' => 'ASC')));       				        					
        				foreach($superv as $supervisores){
							echo "<option value='{$supervisores->id}'";
							if($supervisores->id == $supervisor) echo " selected='selected' ";
							echo ">".($supervisores->nome)."</option>";
						}
        			?>					
				</select>
				<span id='ssupervisor' class="sErro">&nbsp;*</span>
			</td>
       </tr>    
       <tr class='specalt'>
        <td ><span>Ramal</span></td>
        <td><input name="ramal" id="ramal" type="text" size='6' maxlength='4' value="<?php echo $ramal; ?>"   onKeyPress='mascara(this, mnum);'>
        <span id='sramal' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Número do Projeto</span></td>
        <td><input name="numero_projeto"  id="numero_projeto" type="text" size='55' maxlength='60' value="<?php echo $numero_projeto; ?>">
       </tr> 
       
       <tr class='specalt'>
        <td valign='top'><span>Nome do Projeto</span></td>
        <td>
        <textarea name="nome_projeto" id="nome_projeto" cols='50' rows='4'><?php echo $nome_projeto; ?></textarea>
       </tr>
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>
       <tr><td colspan='2'>
       <?php 
			echo tabela_horarios("do Estágio","he");       
       ?>
       </td></tr>

       </table>
       </div> 
            
                  
    </table> 
  <table width="800px" bgcolor="#FFFFFF"><tr align='center'><td>
  <table width="750px" bgcolor="#F5FAFA">
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

    $(document).ready(function() {
        $('input#vigenciai').mask('39/19/2999');
        $('input#vigenciaf').mask('39/19/2999');
        $('input#datanasc').mask('39/19/2999');
        $('input#dataexpedicao').mask('39/19/2999');

        $('input#vinc_estagiario').click(function() {
            $('#tipo_estagio').show();
        });
        $('input#vinc_bolsista').click(function() {
            $('#tipo_bolsa').show();
            $('#tipo_estagio').hide();
        });
        $('#id_bolsista').change(function(){
           	if ($('#id_bolsista').val()==6) //hard code feio... mas nao foi previsto
           		$('#trTermoAceite').show();
           	else{
                $('#trTermoAceite').hide();
                $('input#termo_aceite').val('');
           	}           	
        });

        if('<?= $tipo_vinculo?>' != 'b'){
            $('#tipo_bolsa').hide();
            $('#trTermoAceite').hide();
        }
        else{            
            $('#tipo_estagio').hide();
            if ('<?php echo $id_bolsista; ?>'!='6'){
                alert('<?php echo $termoAceite; ?>.');
                $('#trTermoAceite').hide();
            }
            
            
        }
        
    });


	mostraErros('<?php echo $string_erros; ?>');	
</script>
<?php 
include_once('../inc/copyright.php');
?>
</div>
