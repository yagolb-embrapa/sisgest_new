<?php 

$qtd_abas = 6;
require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../classes/DB.php");
?>

<!-- TR de CONTEUDO -->  
<tr>
  <td width='750px' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Edição de Estagiário</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
<?php

$id = $_GET['id'];
		
		
$submit = $_POST['submit'];
unset($string_erros);
if($submit){
	extract($_POST);// 1 - Pega tb todos os valores do formulario
	$municipio = $_POST['municipio'];//pega manualmente pq como esta em outra pagina, nao ta pegando com a linha acima
	
	//colocar aqui os campos que podem ser vazios no formulario
	$excecoes_vazio = array("telres","telcel","emaile","complemento","ra","ramal","numero_projeto","nome_projeto","observacao","cargaoutra","agencia","conta","banco","cracha","beneficiario0","beneficiario1","beneficiario2","beneficiario3","beneficiario4","parentesco0","parentesco1","parentesco2","parentesco3","parentesco4","select_ano","novo_periodo","novo_horas_mes","novo_horas_trabalhadas","id_beneficiario0","id_beneficiario1","id_beneficiario2","id_beneficiario3","id_beneficiario4","remuneracao","relat_ano","status","fumante","obrig");

    $nome_mes = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

    $excecoes_vazio = array_merge($excecoes_vazio, $nome_mes);

    if($tipo_vinculo != 'b'){
        $excecoes_vazio[] = "id_bolsista";
        $excecoes_vazio[] = "termo_aceite";
        $id_bolsista = 'null';		
        $termo_aceite = '';
    }
    else if ($id_bolsista!=6){
    	$excecoes_vazio[] = "termo_aceite";
        $termo_aceite = '';
    }
    $cracha = ($cracha == '') ? 'null' : $cracha;
    
    //Colocando os termos aditivos e de distrato como excecao
    $excecoes_vazio[] = 'ta';    
    $excecoes_vazio[] = 'tdistrato';

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
	if(!valida($cpf,'cpf')) $erros[] = 'cpf';
	if(!valida($cep,'cep')) $erros[] = 'cep';
	if(!valida($email,'email')) $erros[] = 'email';
	if(!valida($emaile,'email') && !empty($emaile)) $erros[] = 'emaile';
								
	//Inicio e termino do curso no formato AAAA-MM (ano e semestre, na verdade)
	$inicio_curso = $anoicurso."-".$icurso;	
	$termino_curso = $anotcurso."-".$tcurso;
	
	$q_cpf = "SELECT * FROM estagiarios WHERE cpf = '{$cpf}' AND id <> {$id}";	
	$r_cpf = sql_executa($q_cpf);
	if(sql_num_rows($r_cpf)>0) $erros[] = 'cpf'; 

    //Adicionando os valores dos beneficiarios/parentescos em vetores
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
    $id_benef[0] = $id_beneficiario0;
    $id_benef[1] = $id_beneficiario1;
    $id_benef[2] = $id_beneficiario2;
    $id_benef[3] = $id_beneficiario3;
    $id_benef[4] = $id_beneficiario4;

	// 3 - Mostra mensagem de erro ou cria query de insercao 
	if(count($erros)>0){
		//essa string é usada pelo javascript no final da pagina para marcar os campos com o asterisco vermelho 
		$string_erros = implode("|",$erros);			
		//mostra mensagem de erro		
		if(count($erros)==1 && $erros[0] != 'cpf'){
			$msg_erro = "Um campo não foi preenchido corretamente e foi marcado com um asterisco vermelho. Por favor, verifique-o e tente novamente.";
		}elseif(count($erros)==1 && $erros[0] == 'cpf'){
			$msg_erro = "Outro usuário com esse CPF já está cadastrado no sistema.";					
		}elseif(in_array('cpf',$erros)){			
			$msg_erro = "Outro usuário com esse CPF já está cadastrado no sistema; Alguns campos não foram preenchidos corretamente e foram marcados com um asterisco vermelho. Por favor, verifique-os e tente novamente.";				
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
        if($status == '')
            $status = 1;
		$query = "UPDATE estagiarios SET 
			nome = '$nome', data_nascimento = '".formata($datanasc,'data')."', nacionalidade = '$nacionalidade', 
			id_estado_civil = $estadocivil, cpf = '$cpf', rg = '$rg', 
			data_expedicao = '".formata($dataexpedicao,'data')."', orgao_expedidor='$orgaoexpedidor', 
			endereco = '$endereco', complemento = '$complemento', bairro = '$bairro', cep = '$cep', 
			id_municipio=$municipio, uf = '$uf', tel_residencial = '$telres', tel_celular = '$telcel', 
			email = '$email', email_embrapa = '$emaile', agencia = '$agencia', conta_corrente = '$conta', 
			id_banco = $banco, id_instituicao_ensino = $instituicao, curso = '$curso', inicio_curso = '$inicio_curso', observacao = '$observacao',
			termino_curso = '$termino_curso', id_nivel = $nivel, ra = '$ra', estagio_obrigatorio = '$obrig',
			vigencia_inicio = '".formata($vigenciai,'data')."', vigencia_fim = '".formata($vigenciaf,'data')."', 
			remuneracao = $remuneracao, cracha = $cracha, participou_piec ='$piec', id_origem_recursos =$origem, 
			carga_horaria = $cargahoraria, id_supervisor = $supervisor, area_atuacao = '$area', 
            numero_projeto = '$numero_projeto', ramal = '$ramal', nome_projeto = '$nome_projeto', status = '$status', sexo = '$sexo',
            tipo_vinculo = '$tipo_vinculo', id_bolsista = {$id_bolsista}, termo_aceite = '{$termo_aceite}', fumante = '{$fumante}'";		
        if (valida($tdistrato, "data"))
        	$query .= ", tdistrato='".formata($tdistrato, "data")."'";		
		$query .= " WHERE id = {$id} ;";
		
		$query_update = $query;

		$result = sql_executa($query);
			
		//$query_horarios = "INSERT INTO horarios () VALUES ()";
		//mensagem de sucesso		
		if($result){
			//TERMOS ADITIVOS
			$q_del_ta = "DELETE FROM termos_aditivos WHERE id_estagiario = {$id};";
			$r_del_ta = sql_executa($q_del_ta);			
			for ($i=0;$i<count($_POST['ta']['inicio']);$i++){
				if (valida($_POST['ta']['inicio'][$i],"data") && valida($_POST['ta']['fim'][$i],"data")){
					$q_ins_ta = "INSERT INTO termos_aditivos (id_estagiario,data_inicio,data_fim) VALUES ";
					$q_ins_ta .= "({$id},'".formata($_POST['ta']['inicio'][$i],"data")."','".formata($_POST['ta']['fim'][$i],"data")."');";
					$r_ins_ta = sql_executa($q_ins_ta);
				}
			}
			
			//HORARIOS
			$q_del_horarios = "DELETE FROM horarios WHERE id_estagiario = {$id};";
			$r_del_horarios = sql_executa($q_del_horarios);						
			//salvando horarios
			if(!$r_del_horarios || !salva_horarios ($id, $_POST)){
				$msg_hor = "No entanto, não foi possível editar os horários do estagiário. Por favor, tente novamente.";
			}

            for($i = 0; $i < 5; $i++) {
                if($beneficiario[$i] != '' && $parentesco[$i] == '')
                    $erros[] = 'parentesco'.$i;  
                else {
                    if($id_benef[$i]=='' && $beneficiario[$i] != '' && $parentesco[$i] != ''){
                        $query = "INSERT INTO beneficiarios(id_estagiario, nome, parentesco)
                            VALUES({$id},'{$beneficiario[$i]}', '{$parentesco[$i]}');";
                    }
                    else if($id_benef[$i] != '') {
                        $query = "UPDATE beneficiarios
                                  SET nome='{$beneficiario[$i]}', parentesco='{$parentesco[$i]}'
                                  WHERE id = {$id_benef[$i]};";
                    }
                    else
                        $query = '';
                    $j = $i+1;
                    if($query != '')
                        if(!DB::execute($query))
                            $msg_hor = "No entanto, não foi possível inserir o beneficiário {$j} do estagiário. Por favor, tente novamente.";
                }
            }

            //Recuperando beneficiarios para exibicao em ordem decrescente e permitir alteracao
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
					<td align='center'><span align='center' style='color:#296F3E;'>Estagiário editado com sucesso!{$msg_hor}</span></td>
				</tr>
			</table>		
			<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
		}
        else{
            //echo $query_update;
            echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
                <tr bgcolor='#FFEFEF'>
                <td align='center'><span align='center' style='color:red;'>Não foi possível editar o estagiário, verifique se os campos com (*) foram preenchidos corretamente</span></td>
                </tr>
                </table>		
                <div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
        }
	}
}else{
	//Recupera os dados do bd
	$q_estag = "SELECT * FROM estagiarios WHERE id = {$id}";
	$r_estag = sql_executa($q_estag);
	if(sql_num_rows($r_estag)>0){
		$c_estag = sql_fetch_array($r_estag);
		extract($c_estag);		
	}

    //Recuperando beneficiarios
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
    
    //Recuperando termos aditivos
    $q_ta = "SELECT * FROM termos_aditivos WHERE id_estagiario={$id} ORDER BY data_inicio;";
    $termosAceite = DB::fetch_all($q_ta);
    $ta['inicio'] = array();
    $ta['fim'] = array();
    foreach ($termosAceite as $termoAceite){
    	$ta['inicio'][] = formata($termoAceite['data_inicio'],"redata");
    	$ta['fim'][] = formata($termoAceite['data_fim'],"redata");
    }
	//Ajustando nomes das variaveis (no form é diferente do bd)
	$datanasc = formata($data_nascimento,'redata');
	$telres = $tel_residencial;
	$telcel = $tel_celular;
	$dataexpedicao = formata($data_expedicao,'redata');
	$orgaoexpedidor = $orgao_expedidor;
	$estadocivil = $id_estado_civil;
	$nivel = $id_nivel;
	$instituicao = $id_instituicao_ensino;
	$icurso = substr($inicio_curso,-2);
	$anoicurso = substr($inicio_curso,0,4); 
	$tcurso = substr($termino_curso,-2);
	$anotcurso = substr($termino_curso,0,4);
	$vigenciai = formata($vigencia_inicio,'redata');
	$vigenciaf = formata($vigencia_fim,'redata');
	$tai = formata($taditivo_inicio,'redata');
	$taf = formata($taditivo_fim,'redata');
	$tdistrato = formata($tdistrato,'redata');
	$area = $area_atuacao;
	$carga = $carga_horaria;
	if($carga!=20 && $carga!=30){
		$carga = -1;
		$ocarga = $carga_horaria;
	}
	$origem = $id_origem_recursos;
	$piec = $participou_piec;
	$supervisor = $id_supervisor;
	$numero_projeto = $numero_projeto;
	$conta = $conta_corrente;
	$banco = $id_banco;
	$municipio = $id_municipio;
	$emaile = $email_embrapa;
	$obrig = $estagio_obrigatorio;
    $ativo = $status;
	
	//Pegando horarios [ Danger - Não mexa! ] ox
	$q_horarios = "SELECT * FROM horarios WHERE id_estagiario = {$id} AND (tipo = 'a' OR tipo = 'e') ORDER BY tipo,dia,entrada";						  
	$r_horarios = sql_executa($q_horarios);
										
	/*Relembrando: o formato é hxnmy, onde 
		x indica o tipo, aula ou estagio {a,e}
		n indica o dia da semana {0,1,2,3,4} [segunda a sexta]
		m indica entrada ou saida {0,1,2,3} [entrada1, saida1, entrada2, saida2]
		y indica minuto ou hora {h,m} 
	*/  														
	if(sql_num_rows($r_horarios)>0){
        $count = 3;
		while($c_horarios = sql_fetch_array($r_horarios)){
            $tipo = $c_horarios['tipo'];
			$indice = 'h'.$tipo.($c_horarios['dia']-2);
            if($tipo == 'e') {
                if(substr($c_horarios['entrada'],0,2)<12) $count = 0;
                else $count = 2;// significa 'entrada do 2 periodo'
            }
            else {
                $count = $count > 2 ? 0 : 2;
            }
							
			$_POST[$indice.$count.'h'] = substr($c_horarios['entrada'],0,2);
			$_POST[$indice.$count.'m'] = substr($c_horarios['entrada'],3,2);				
			$count++;				
			$_POST[$indice.$count.'h'] = substr($c_horarios['saida'],0,2);
			$_POST[$indice.$count.'m'] = substr($c_horarios['saida'],3,2);					
		}
		extract($_POST);
	}

}
?>
   <!-- Abas -->	
	<ul class='listaAbas'>
       <li><a href="javascript: mostrarAba('aba1','a1');" id='a1' class='active'>Identificação</a></li>
       <li><a href="javascript: mostrarAba('aba2','a2');" id='a2'>Curso</a></li>
       <li><a href="javascript: mostrarAba('aba3','a3');" id='a3'>Banco</a></li>
       <li><a href="javascript: mostrarAba('aba4','a4');" id='a4'>Estágio</a></li>       
       <li><a href="javascript: mostrarAba('aba5','a5');" id='a5'>Frequência</a></li>       
       <li><a href="javascript: mostrarAba('aba6','a6');" id='a6'>Rel.Frequência</a></li>       
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
        <td><input name="sexo" type="radio" id="masculino" value="m" <?php if($sexo=="m") echo "checked"; ?> >        		
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
        <td ><span>E-mail Embrapa</span></td>
        <td><input name="emaile" id="emaile" type="text" size='30' value="<?php echo $emaile; ?>"><span id='semaile' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr class='specalt'>
        <td><span>Fumante(*)</span></td>
        <td><input name="fumante" type="radio" id="fumante_sim" value="t" <?php if($fumante=='t') echo "checked";?> >        		
        			<label for="fumante_sim"><span>Sim</span></label>
            <input name="fumante" type="radio" id="fumante_nao" value="f" <?php if($fumante!='t') echo "checked";?> >
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
        <td><input name="complemento" id="complemento" type="text" size="40" maxlength="50"  value="<?php echo $complemento; ?>"><span id='scomplemento' class="sErro">&nbsp;*</span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>CEP(*)</span></td>
        <td><input name="cep" id="cep" type="text" size="10" maxlength="9" onKeyPress="mascara(this, mcep);"  value="<?php echo $cep; ?>"><span id='scep' class="sErro">&nbsp;*</span></td>
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
                        echo "                  <td><input id='beneficiario{$i}' name='parentesco{$i}' value='{$parentesco[$i]}' size='40' maxlength='40' /></td>\n";
                        echo "                </tr>\n";
                        echo "                <tr><td><input id='benef$i' name='id_beneficiario{$i}' type='hidden' value='{$id_benef[$i]}' /></td></tr>";
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
        		<span>&nbsp;&nbsp;Semestre do ano de </span><input name="anotcurso" id="anotcurso" type="text" size="10" maxlength="4" value="<?php echo $anotcurso; ?>"   onKeyPress='mascara(this, mnum);'>        		
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
        <?php
            if($status == 0) {
                echo "<tr class='specalt'>
        <td ><span>Ativo</span></td>
        <td><input name='status' type='radio' id='status1' value='1' >        		
        			<label for='status1'><span>Sim</span></label>
        		<input name='status' type='radio' id='status0' value='0' checked >
                <label for='status0'><span>Não</span></label>
        </td>
       </tr>";
            }
        ?>
      <tr class='specalt'>
        <td><span>Tipo de Vínculo</span></td>
        <td><input name="tipo_vinculo" type="radio" id="vinc_estagiario" value="e" <?php if($tipo_vinculo!="b") echo "checked"; ?> >        		
        			<label for="estagiario"><span>Estagiário</span></label>
            <input name="tipo_vinculo" type="radio" id="vinc_bolsista" value="b" <?php if($tipo_vinculo=="b") echo "checked"; ?> >
        			<label for="bolsista"><span>Bolsista</span></label>
        	<span id='stipo_vinculo' class="sErro">&nbsp;*</span>        			
        </td>
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
			<input type="text" name="termo_aceite" id="termo_aceite" value="<?php echo $termo_aceite; ?>" />	        
        </td>  
      </tr>
       <tr class='specalt' id="tipo_estagio">
        <td ><span>Tipo do Estágio</span></td>
        <td><input name="obrig" type="radio" id="obrigs" value="S" <?php if($obrig!="N") echo "checked"; ?> >        		
        			<label for="obrigs"><span>Obrigatório</span></label>
        		<input name="obrig" type="radio" id="obrign" value="N" <?php if($obrig=="N") echo "checked";?> >
        			<label for="obrign"><span>Não Obrigatório</span></label>
        	<span id='sobrig' class="sErro">&nbsp;*</span>        			
        </td>        
       </tr>
		<tr class='specalt'>
        <td ><span>Vigência / TCE(*)</span></td>
        <td><input name="vigenciai" id="vigenciai" type="text" size="10"  maxlength="10" value="<?php echo $vigenciai; ?>">
        		<span>&nbsp;&nbsp;a&nbsp;&nbsp;</span>
        		<input name="vigenciaf" id="vigenciaf" type="text" size="10"  maxlength="10" value="<?php echo $vigenciaf; ?>">
			<span id='svigenciaf' class="sErro">&nbsp;*</span><span id='svigenciai' class="sErro"></span>			
        	</td>
       </tr>       
	   <!-- tr class='specalt'>
        <td><span>Termo Aditivo(TA)</span></td>
        <td><input name="tai" id="tai" type="text" size="10"  maxlength="10" value="<?php echo $tai; ?>">
        		<span>&nbsp;&nbsp;a&nbsp;&nbsp;</span>
        		<input name="taf" id="taf" type="text" size="10"  maxlength="10" value="<?php echo $taf; ?>">
        	</td>
       </tr-->
       <tr>
       	<td style="vertical-align: top;"><span>Termo Aditivo (TA)<br/><span style="font-size:8pt">As datas repetidas (inicio ou fim) serão desconsideradas</span> </span></td>
       	<td><div id="divTAContainer">
       		<?php        		
       		$size = count($ta['inicio']);
       		for ($i=0;$i<$size;$i++){
       			if (valida($ta['inicio'][$i],"data")&&valida($ta['fim'][$i],"data")){
	       			echo "<div>";
	       			echo "<input type='text' name=\"ta[inicio][]\" value='{$ta['inicio'][$i]}' size='10' maxlength='10'/>&nbsp;&nbsp;a&nbsp;&nbsp;";
	       			echo "<input type='text' name=\"ta[fim][]\" value='{$ta['fim'][$i]}' size='10' maxlength='10'/>&nbsp;";
	       			echo "<a href='javascript://' onclick='removeTA(this)'>Remover</a>";
	       			echo "</div>";
       			}
       		}
       		
       		?>
       		</div>
       		<input type="button" value="Adicionar termo adivito" onclick="createDivTA()"/>
       		
       	</td>
       </tr>       
       </tr>       
		<tr class='specalt'>
        <td ><span>Termo de Distrato(TD)</span></td>
        <td><input name="tdistrato" id="tdistrato" type="text" size="10"  maxlength="10" value="<?php echo $tdistrato; ?>">
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
        <td ><span>Remuneração(sem decimal)</span></td>
        <td><input name="remuneracao" id="remuneracao" type="text" size="6" maxlength="8"  value="<?php echo $remuneracao; ?>" onkeypress="return m_dec2(event, this);">
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
        <span id='snumero_projeto' class="sErro">&nbsp;*</span></td>
       </tr> 
       
       <tr class='specalt'>
        <td valign='top'><span>Nome do Projeto</span></td>
        <td><!--<input name="nome_projeto" id="nome_projeto" type="text"  value="<?php echo $nome_projeto; ?>">-->
        <textarea name="nome_projeto" id="nome_projeto" cols='50' rows='4'><?php echo $nome_projeto; ?></textarea>
        <span id='snome_projeto' class="sErro">&nbsp;*</span></td>
       </tr>
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>
       <tr><td colspan='2'>
       <?php 
			echo tabela_horarios("do Estágio","he");       
            
            if($status == 1) {
                echo "<tr><td align='center' colspan='5'><a href='javascript://' onclick=\"document.location.href='../conta/conta.finalizacao.php?id={$id}&id_superv={$supervisor}';\">
                    <img src='../img/icon_delete.gif' width='16' height='16' border='0'>Finalizar Estágio</a></p></td></tr>";
            }
       ?>   
       
  			</td></tr>
        </table>
       </div> 
              



	   <!-- ============ Conteudo da quinta ABA ============ -->       
       <div id="aba5" class='conteudoAba'> 
       <table width="100%" class='formulario'>
         <tbody>
            <tr>
                <td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td>
            </tr>	
            <tr class='specalt'>
                <td width="100%">
                    <table width='100%'>
                    <tbody>
                        <tr>
                            <td> <span>Ano</span></td>
                            <td><select id="select_ano" name="select_ano" class="select">
                                <option value="">-- Ano --</option>
                                <?php
                                    //Busca quais anos tem horas de trabalho
                                    $queryAnoFreq = "SELECT DISTINCT(EXTRACT(year FROM periodo))
                                                     FROM frequencias
                                                     WHERE id_estagiario = {$id}";
                                    $anos_freq = DB::fetch_all($queryAnoFreq);

                                    //Lista os anos no campo select
                                    foreach($anos_freq as $anos){
                                        echo "<option value={$anos['date_part']}";
                                        echo ">".$anos['date_part']."</option>";
                                    }
                                    echo "\n";
                                ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                    </table>

            <tr>
                <td>&nbsp;</td><td align='right'>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr align='right'>
                            <td id="divTotalAnt" width="70%"></td>
                            <td id="divSaldoAnt"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table id='divFreq' class='formulario' width='100%'></table>
                </td>
            </tr>
            <tr>
                <td>
                <table width="100%">
                    <tr align='right'>
                        <td id="divTotalFinal" width="70%"></td>
                        <td id="divSaldoFinal"></td>
                    </tr>
                </table>
                </td>
            </tr>
            <tr name='adicHorasTrab'>
                <td>
                    <table>
                    <tbody>
                        <tr>
                            <td>Período: <input name='novo_periodo'  id='novo_periodo' type='text' size='7' maxlength='7'></td>
                            <td>Horas Planejadas: <input name='novo_horas_mes'  id='novo_horas_mes' type='text' size='6' maxlength='6'></td>
                            <td>Horas Trabalhadas: <input name='novo_horas_trabalhadas'  id='novo_horas_trabalhadas' type='text' size='6' maxlength='6'></td>
                            <td><input type='button' id='buttonAdicHoras' value='Adicionar'/></td>
                        </tr>
                    </tbody>
                    </table>
                </td>
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td>
            </tr>
          </tbody>
       </table>
       </div>



    <!-- ============ Conteudo da sexta ABA ============ -->       
       <div id="aba6" class='conteudoAba'> 
        <table width="100%" class='formulario'>
         <tbody>
            <tr>
                <td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td>
            </tr>	
            <tr class='specalt'>
                <td width="100%">
                    <table width='100%'>
                    <tbody>
                        <tr>
                            <td> <span>Ano</span></td>
                            <td><select id="relat_ano" name="relat_ano" class="select">
                                <option value="">-- Ano --</option>
                                <?php
                                    //Busca quais anos tem horas de trabalho
                                    $queryAnoFreq = "SELECT DISTINCT(EXTRACT(year FROM periodo))
                                                     FROM frequencias
                                                     WHERE id_estagiario = {$id}";
                                    $anos_freq = DB::fetch_all($queryAnoFreq);

                                    if(sizeof($anos_freq == 0)){
                                        echo "<option value=" . date('Y');
                                        echo ">".date('Y')."</option>";
                                    }
                                    else {
                                        //Lista os anos no campo select
                                        foreach($anos_freq as $anos){
                                            echo "<option value={$anos['date_part']}";
                                            echo ">".$anos['date_part']."</option>";
                                        }
                                    }
                                    echo "\n";
                                ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </td>
            <tr>
                <td>&nbsp;</td><td align='right'>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <table id='divRelatFreq' class='formulario' width='100%'></table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td><td align='right'>&nbsp;</td>
            </tr>
          </tbody>
       </table>
       </div>

    </table> 


  <table width="800px" bgcolor="#FFFFFF">
    <tbody>
        <tr align='center'>
            <td>
                <table width="750px" bgcolor="#F5FAFA">
                    <tr align='center'>
                        <td colspan='2' ><input type="submit" name="submit" value="Salvar"/></td>
                    </tr>
                </table> 
            </td>
        </tr>
    </tbody>
  </table>
  </div> 
 </div>

	
 </div>	
</form>  
</div>
  </td>
</tr>
</table>

<script>

function createDivTA(){
	var div = document.createElement("div");	
	var innerTxt = "<input type='text' name=\"ta[inicio][]\" value='' size='10' maxlength='10'/>&nbsp;&nbsp;a&nbsp;&nbsp;";
	innerTxt += "<input type='text' name=\"ta[fim][]\" value='' size='10' maxlength='10'/>&nbsp;";
	innerTxt += "<a href='javascript://' onclick='removeTA(this)'>Remover</a>";
	div.innerHTML = innerTxt;
	document.getElementById('divTAContainer').appendChild(div);
	$('input[name="ta[inicio][]"]').mask('39/19/2999');
    $('input[name="ta[fim][]"]').mask('39/19/2999');
}

function removeTA(linkElement){
	var el = linkElement.parentNode;
	document.getElementById('divTAContainer').removeChild(el);
}


// Soma dois horarios
function soma_hora(hora1,hora2) {
    //Tira o sinal de negativo
    if(hora1[0] == '-'){
        hora1[0] = 0;
        sinal1 = -1;
    }
    else
        sinal1 = 1;
    if(hora2[0] == '-'){
        hora2[0] = 0;
        sinal2 = -1;
    }
    else
        sinal2 = 1;

    hora1 = hora1.split(':');
    hora2 = hora2.split(':');
    
    // Converte para minutos
    h1 = parseInt(60*hora1[0]);
    m1 = parseInt(hora1[1]*sinal1);

    h2 = parseInt(60*hora2[0]);
    m2 = parseInt(hora2[1]*sinal2);

    soma = h1 + m1 + h2 + m2;

    // Converte para o formato HH:MM
    result = Math.abs(soma);
    result0 = parseInt(Math.floor(result/60));
    result1 = parseInt(result) - parseInt(result0 * 60);

    if(result0 >= 0 && result0 < 10)
        result0 = '0' + result0;
    if(result1 >= 0 && result1 < 10)
        result1 = '0' + result1;

    // Coloca o sinal se for negativo e retorna a soma
    if(soma < 0)
        return '-' + result0 + ':' + result1;
    else
        return result0 + ':' + result1;
}


// Atualiza o saldo dos meses a partir do mes_inicial ate dezembro
function atualiza_saldo(mes_inicial, ano) {
    //Se for janeiro, soma com saldo do ano anterior
    if(mes_inicial == 0) {
        anterior = "00:00";
    }
    else
        anterior = ($($($($("table#divFreq tbody tr")[mes_inicial-1]).find("td"))[3]).html());

    for(i=mes_inicial;i<12;i++) {
        //Pega as horas do mes e trabalhadas, verifica se é nula e calcula a diferenca
        horas_mes = ($($($($("table#divFreq tbody tr")[i]).find("td"))[1]).html());
        if(horas_mes == "-")
            horas_mes = "00:00";
        horas_trabalhadas = ($($($($("table#divFreq tbody tr")[i]).find("td"))[2]).html());
        if(horas_trabalhadas == "-")
            horas_trabalhadas = "00:00";

        atual = soma_hora(horas_trabalhadas, '-' + horas_mes);
        anterior = soma_hora(atual, anterior);

        //Atualiza a coluna do saldo
        $($($($("table#divFreq tbody tr")[i]).find("td"))[3]).html(anterior);
    }

    //Saldo no final do ano atual
    saldo_final = anterior;

    //Pega o saldo acumulado do ano anterior
    saldo = $('td#divSaldoAnt').html();
    saldo = saldo.split(': ');
    saldo_anterior = saldo[1];

    //Atualiza o saldo acumulado com o saldo do final do ano
    $('td#divSaldoFinal').html("Saldo em " + ano + ": " + soma_hora(saldo_anterior, anterior));

    id_estagiario = <?= $id; ?>;

    //Atualiza o BD com o novo saldo no final do ano
    $.post('estagiario.saldos.php', {'ano':ano, 'saldo':saldo_final, 'id_estagiario':id_estagiario}, function(dados, text_status) {
        if(dados.status != 'sucesso')
            alert(dados.status);

        horas_anterior = $('td#divTotalAnt').html();
        horas_anterior = horas_anterior.split(': ');
        total_anterior = horas_anterior[1];

        $('td#divTotalFinal').html("Horas trabalhadas até " + ano + ": " + soma_hora(dados.horas_trab, total_anterior));
    });
    return false;
}


// Faz a mudanca no BD quando terminar de editar a textbox
function muda_horario(campo, id_estagiario, $input) {
    var valor = $.trim($input.val());
    var valor_antigo = $input.attr('old_val');
    var $pai = $input.parent();

    // Verifica o formato do período
    if(valor.length < 6){
        alert('Período inválido');
        $pai.html(valor_antigo);
        return false;
    }

    // Dados para inserir/alterar a frequencia
    var id_frequencia = $($pai.parent().find('td')[0]).attr('id');
    var mes = $($pai.parent().find('td')[0]).attr('name');
    var ano = $('#select_ano').val();

    // Se houve mudança no valor da textbox
    if(valor != valor_antigo) {
        $.post('estagiario.atualiza.php', {'id_estagiario': id_estagiario, 'campo': campo, 'id_frequencia':id_frequencia, 'mes':mes, 'ano':ano, 'valor':valor }, function(dados, text_status) {
            if(dados.status == 'sucesso'){
                // Se for insercao, adiciona o id_frequencia como informacao na linha da tabela
                if(dados.codigo != 'alteracao')
                    $($pai.parent().find('td')[0]).attr('id', dados.codigo);
                $pai.html(valor);
                // Atualiza a coluna de saldo dos meses/anos seguintes
                atualiza_saldo(mes-1, ano);
            }
            // Exibe mensagem de erro e retorna ao valor antigo
            else {
                alert(dados.mensagem + "\nComando: " + dados.codigo);
                $pai.html(valor_antigo);
            }
        });
    }
    else 
        $pai.html(valor);
}

function cria_textbox(campo, id_estagiario, $celula) {
    var $inputs = $celula.parent().parent().find('td input');
    var $input = '';

    // Oculta alguma caixa de texto de edicao q esteja sendo exibida, permitindo apenas uma edicao por vez
    $inputs.each(function() {
        $celula.parent().html($celula.val());
    });

    // Exibe um textbox para editar a hora
    if($celula.text() != '')
        $celula.html("<input type='text' name='" + campo + "' id='" + campo + "' value='" + $celula.text() + "' size='6' maxlength='6' />");

    // Adiciona mascara
    $input = $celula.find("#" + campo);
    $input.unbind('blur');
    $input.attr('old_val', $input.val());
    $input.select();
    $input.mask('199:59');

    // Adiciona evento de blur para salvar o que foi alterado no textbox
    $input.blur(function() {
        muda_horario(campo, id_estagiario, $(this));
    });
}

function atualiza_tabela() {
    var id_estagiario = '<?php echo $id; ?>';
    atualiza_saldo(0, $('#select_ano').val());
    $("table#divFreq tbody tr").each(function() {
        $(this).unbind('dblclick');

        var $celulas = $(this).find('td');

        $celulas.each(function() {
            $(this).unbind('dblclick');
        });

        //Duplo clique para editar/alterar as horas planejadas
        $($celulas[1]).dblclick(function() {
            cria_textbox('horas_mes', id_estagiario, $(this));
        });

        //Duplo clique para editar/alterar as horas trabalhadas
        $($celulas[2]).dblclick(function() {
            cria_textbox('horas_trabalhadas', id_estagiario, $(this));
        });
    });
}

//Quando um ano for escolhido no select do ano da Frequencia
function muda_ano(ano) {
    var id_estagiario = '<?php echo $id; ?>';

    //Post para buscar as frequencias
    $.post('estagiario.frequencia.php', {ano:$('#select_ano').val(), id:id_estagiario}, function(dados, text_status){
        if(dados.status == 'sucesso') {
            //Adiciona as frequencias na tabela
            $('table#divFreq').empty();
            $('table#divFreq').html(dados.frequencia);

            //Exibe o saldo do ano anterior
            $('td#divTotalAnt').html("Horas trabalhadas até " + (parseInt(ano)-1) + ": " + dados.horas_trab_anterior);
            $('td#divSaldoAnt').html("Saldo em " + (parseInt(ano)-1) + ": " + dados.saldo_anterior);

            //Atualiza a tabela/calcula os saldos
            atualiza_tabela();
        }
        else {
            //Avisa e limpa a tabela caso de erro
            alert('Nao foi possivel obter os dados para o ano' + $(this).val());
            $('table#divFreq').empty();
        }
    });
}

function atualiza_tabela_entrega() {
    var id_estagiario = '<?php echo $id; ?>';

    $("input:checkbox").click(function() {
        $input = $(this);
        id_frequencia = $input.attr('id');
        id_frequencia = id_frequencia.substr(2);
        mes = $input.attr('name');
        ano = $("#relat_ano").val();
        valor = $input.is(':checked');
        $.post('estagiario.atualiza.php', {'id_estagiario': id_estagiario, 'campo': 'entregou_relatorio', 'id_frequencia':id_frequencia, 'mes':mes, 'ano':ano, 'valor':valor }, function(dados, text_status) {
            if(dados.status == 'sucesso'){
                //Se for insercao, adiciona o id_frequencia como informacao na linha da tabela
                if(dados.codigo != 'alteracao'){
                    $input.attr('id', 'id'+dados.codigo);
                }
            }
            //Exibe mensagem de erro e retorna ao valor antigo
            else {
                alert(dados.mensagem + "\nComando: " + dados.codigo);
            }
        });
    });

}

function muda_ano_relat(ano_relat) {
    var id_estagiario = '<?php echo $id; ?>';

    //Post para buscar as frequencias
    $.post('estagiario.relatfreq.php', {ano:$('#relat_ano').val(), id:id_estagiario}, function(dados, text_status){
        if(dados.status == 'sucesso') {
            //Adiciona as frequencias na tabela
            $('table#divRelatFreq').empty();
            $('table#divRelatFreq').html(dados.tabela);

            //Atualiza a tabela de entrega dos relatórios
            atualiza_tabela_entrega();
        }
        else {
            //Avisa e limpa a tabela caso de erro
            alert('Nao foi possivel obter os dados para o ano' + $(this).val());
            $('table#divRelatFreq').empty();
        }
    });
}


$(document).ready(function() {

    $("input:visible:enabled:first").focus();

    // Mascaras
    $('input#novo_periodo').mask('19/2999');
    $('input#novo_horas_mes').mask('199:59');
    $('input#novo_horas_trabalhadas').mask('199:59');
    $('input#vigenciai').mask('39/19/2999');
    $('input#vigenciaf').mask('39/19/2999');    
    $('input#tai').mask('39/19/2999');    
    $('input#taf').mask('39/19/2999');    
    $('input#tdistrato').mask('39/19/2999');    
    $('input#datanasc').mask('39/19/2999');
    $('input#dataexpedicao').mask('39/19/2999');
    $('input[name="ta[\'inicio\'][]"]').mask('39/19/2999');
    $('input[name="ta[\'fim\'][]"]').mask('39/19/2999');

    $('input#vinc_estagiario').click(function() {
        $('#tipo_bolsa').hide();
        $('#tipo_estagio').show();
    });
    $('input#vinc_bolsista').click(function() {
        $('#tipo_bolsa').show();
        $('#tipo_estagio').hide();
    });
    if('<?= $tipo_vinculo?>' != 'b'){
        $('#tipo_bolsa').hide();
        $('#tipo_estagio').show();
        $('#trTermoAceite').hide();
    }
    else {
        $('#tipo_bolsa').show();
        $('#tipo_estagio').hide();
        if ('<?php echo $id_bolsista; ?>'!='6')
            $('#trTermoAceite').hide();
        else
            $('#trTermoAceite').show();
    }

    $('#id_bolsista').change(function(){
    	if ($('#id_bolsista').val()==6)
        	$('#trTermoAceite').show();
    	else
        	$('#trTermoAceite').hide();    
    });

    // Adiciona uma nova frequencia ao clicar no botao Adicionar
    $('input#buttonAdicHoras').click(function() {
        var id_estagiario = '<?php echo $id; ?>';
        var periodo = $('input#novo_periodo').val();
        var horas_mes = $('input#novo_horas_mes').val();
        var horas_trabalhadas = $('input#novo_horas_trabalhadas').val();
        var mes = periodo.substring(0,2);
        var ano = periodo.substring(3,8);

        // Verifica se os horários digitados são válidos
        var msg_erro = '';
        if(periodo.length < 7)
            msg_erro = "Período inválido!\n";
        if(horas_mes.length != 0 && horas_mes.length < 6)
            msg_erro = "Horas planejadas inválidas! ";
        if(horas_trabalhadas.length != 0 && horas_trabalhadas.length < 6)
            msg_erro = msg_erro + "Horas trabalhadas inválidas! ";
        if(mes > 12 || mes < 1)
            msg_erro = msg_erro + "Mês inválido!";
        if(msg_erro != ''){
            alert(msg_erro);
            return false;
        }

        $.post('estagiario.criafrequencia.php', {'periodo':periodo, 'horas_mes':horas_mes, 'horas_trabalhadas':horas_trabalhadas, 'id_estagiario':id_estagiario }, function(dados, text_status) {
            // Se houver erro ao adicionar, exibe a mensagem de erro
            if(dados.status != 'sucesso'){
                alert(dados.mensagem);
            }
            else {
                alert(dados.mensagem);
                // Apos adicionar atualiza a tabela se esta estiver exibindo o ano em que houve a insercao    
                if($('#select_ano').val() == ano){
                    $($('table#divFreq').find("[name='" + mes + "']")).parent().html(dados.html);
                    atualiza_tabela();
                }
                
                // Se a tabela nao estiver exibindo o ano correspondente, verifica a select box para adicionar o ano
                else {
                    $anos = $('#select_ano').find('option');
                    var teste = '';
                    var pos = 1;
                    var ano_atual = $('#select_ano').val();

                    // Busca se ja existe o ano no select, se nao encontrar ja marca a posicao onde sera adicionado
                    for(; pos < $anos.size(); pos++) {
                        teste = teste + "\n" + $($anos[pos]).html();
                        if(ano < $($anos[pos]).html())
                            break;
                        else if(ano == $($anos[pos]).html()){
                            teste = 'existe';
                            break;
                        }
                    }

                    // Caso nao existir, adiciona
                    if(teste != 'existe') {
                        // Cria o html ordenado
                        option = '<option value="">-- Ano --</option>';
                        for(i = 1; i < pos; i++) {
                            if(ano_atual == $($anos[i]).html())
                                option = option + "<option value='" + $($anos[i]).html() + "' + selected='selected'>" + $($anos[i]).html() + "</option>";
                            else
                                option = option + "<option value='" + $($anos[i]).html() + "'>" + $($anos[i]).html() + "</option>";
                        }
                        option = option + "<option value='" + ano + "'>" + ano + "</option>";
                        for(; i < $anos.size(); i++) {
                            if(ano_atual == $($anos[i]).html())
                                option = option + "<option value='" + $($anos[i]).html() + "' + selected='selected'>" + $($anos[i]).html() + "</option>";
                            else
                                option = option + "<option value='" + $($anos[i]).html() + "'>" + $($anos[i]).html() + "</option>";
                        }
                        // Troca o conteudo do select pelo novo que contem o ano adicionado
                        $('#select_ano').html(option);
                    }

                    // Muda o select do ano para o ano em que houve a insercao
                    $('#select_ano').val(ano);
                    muda_ano(ano);
                }
            }
        });
        $('input#novo_periodo').val('');
        $('input#novo_horas_mes').val('');
        $('input#novo_horas_trabalhadas').val('');
    });

    // Quando escolher o ano no select box da aba de Frequencia
    $('#select_ano').change(function() {
        //Verifica se escolheu algum ano
        ano = $('#select_ano').val();
        if(ano != ''){
            muda_ano(ano);
        }
        //Limpa a tabela se escolher --Ano-- (nenhum ano escolhido)
        else {
            $('table#divFreq').empty();
            $('td#divSaldoAnt').empty();
            $('td#divSaldoFinal').empty();
            $('td#divTotalAnt').empty();
            $('td#divTotalFinal').empty();
        }
    });

    // Quando escolher o ano no select box da aba de Relatório de Frequencia
    $('#relat_ano').change(function() {
        //Verifica se escolheu algum ano
        ano_relat = $('#relat_ano').val();
        if(ano_relat != ''){
            muda_ano_relat(ano_relat);
        }
        //Limpa a tabela se escolher --Ano-- (nenhum ano escolhido)
        else {
            $('table#divRelatFreq').empty();
        }
    });


    //Define os parametros padroes para chamadas AJAX
    $.ajaxSetup({
        'type': 'post',
        'dataType': 'json',
        'async': false,
        'cache': false,
        'timeout': 60000,
        'complete': function(XMLHTTPRequest, text_status) {
            var data = '';
            if(XMLHTTPRequest.responseText != '') {
                //alert(XMLHTTPRequest.responseText); // Para debugar mensagens do backend, descomentar essa linha
                data = eval('(' + XMLHTTPRequest.responseText + ')');
            }
        }
     });
});


</script>


<?php 
include_once('../inc/copyright.php');
?>
</div>

