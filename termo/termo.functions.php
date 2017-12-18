<?php
require_once("../classes/DB.php");

//le o arquivo rtf e coloca o conteudo numa variavel
function le_termo_rtf($arquivo){
	$texto = file_get_contents($arquivo);
	$tam_texto = sizeof($arquivo);
	return $texto;
}

function rtf($arq_entrada, $arq_saida, $tipo_termo, $id, $num_cracha = 0){
	$time = time();
	$arq_saida = $arq_saida.$time.".rtf";	
		
	$texto = le_termo_rtf($arq_entrada);
	
	$matriz = explode("sectd",$texto);
	$cabecalho = $matriz[0]."sectd";
	$inicio = strlen($cabecalho);
	$final = strrpos($texto,"}");
	$tamanho = $final-$inicio;
	$corpo = substr($texto, $inicio, $tamanho);
	
	$punt = fopen($arq_saida,"w");
	fputs($punt,$cabecalho);
	
	$queryEstagiario = "SELECT  es.*, ie.endereco as enderecoie, ie.razao_social as instituicao, ie.cnpj as cnpj, ec.estado_civil as estadocivil, 
                                mu.nome as municipio, mumu.nome as municipioie, mumu.uf as ufie, su.nome as nome_supervisor, ie.data_convenio,
                                ie.numero_saic, su.cpf as cpf_supervisor
                        FROM estagiarios es 
                        INNER JOIN instituicoes_ensino ie ON ie.id = es.id_instituicao_ensino
                        INNER JOIN estado_civil ec ON es.id_estado_civil = ec.id
                        INNER JOIN municipios mu ON mu.id = es.id_municipio
                        INNER JOIN municipios mumu ON mumu.id = ie.id_municipio
                        INNER JOIN supervisores su ON es.id_supervisor = su.id
                        WHERE es.id = {$id}";						  
	$resultEstagiario = sql_executa($queryEstagiario);
	if(sql_num_rows($resultEstagiario)>0){
		$estagiario = sql_fetch_array($resultEstagiario);	
	}else{
		echo "Estagiário não encontrado";
		exit();
	}	
	
	$sqlTA = "SELECT * FROM termos_aditivos WHERE id_estagiario={$id} ORDER BY data_fim DESC LIMIT 1";
	$resultTA = sql_executa($sqlTA);
	if (sql_num_rows($resultTA)>0){
		$termo_aditivo = sql_fetch_array($resultTA);
	}

    $queryBeneficiarios = "SELECT   nome as nome_beneficiario, parentesco
                           FROM     beneficiarios
                           WHERE    id_estagiario = {$id}
                           ORDER BY nome_beneficiario DESC;";
    $resultBeneficiarios = DB::fetch_all($queryBeneficiarios);


    //total de horas trabalhadas
    $query = "  SELECT to_char(sum(horas_trabalhadas),'HH24:MI') AS total
                FROM   saldos
                WHERE  id_estagiario={$id};";
    $horas_trab = DB::fetch_all($query);
	
    
    
	//posicao 0 fornece a string a ser substituida, a 1 fornece o dado que a substituira e 2 o tamanho da linha 
	//que sera impressa no caso de nao haver dado na posicao 1 	
	$matriz_dados[0][0] = "#NOME#";
	$matriz_dados[0][1] = str_replace(array('á', 'é', 'í', 'ó', 'ú', 'ã', 'ç', 'õ', 'ô', 'ü', 'ö', 'â'), array ('Á', 'É', 'Í', 'Ó', 'Ú', 'Ã', 'Ç', 'Õ', 'Ô', 'Ü', 'Ö', 'Â'),(strtoupper($estagiario['nome'])));
	$matriz_dados[0][2] = "g";	
	$matriz_dados[1][0] = "#nome#";
	$matriz_dados[1][1] = $estagiario['nome'];
	$matriz_dados[1][2] = "g";	
	$matriz_dados[2][0] = "#INST#";
	$matriz_dados[2][1] = strtoupper($estagiario['instituicao']);
	$matriz_dados[2][2] = "g";	
	$matriz_dados[3][0] = "#instituicao#";
	$matriz_dados[3][1] = $estagiario['instituicao'];
	$matriz_dados[3][2] = "g";
	$matriz_dados[4][0] = "#nacionalidade#";
	$matriz_dados[4][1] = $estagiario['nacionalidade'];
	$matriz_dados[4][2] = "m";	
	$matriz_dados[5][0] = "#estado_civil#";
	$matriz_dados[5][1] = $estagiario['estadocivil'];
	$matriz_dados[5][2] = "m";		
	$matriz_dados[6][0] = "#data_nascimento#";
	$matriz_dados[6][1] = formata($estagiario['data_nascimento'],'redata');
	$matriz_dados[6][2] = "d";
	$matriz_dados[7][0] = "#rg#";
	$matriz_dados[7][1] = $estagiario['rg'];
	$matriz_dados[7][2] = "m";
	$matriz_dados[8][0] = "#orgao_expedidor#";
	$matriz_dados[8][1] = $estagiario['orgao_expedidor'];
	$matriz_dados[8][2] = "m";	
	$matriz_dados[9][0] = "#data_expedicao#";
	$matriz_dados[9][1] = formata($estagiario['data_expedicao'],'redata');
	$matriz_dados[9][2] = "d";
	$matriz_dados[10][0] = "#cpf#";
	$matriz_dados[10][1] = $estagiario['cpf'];
	$matriz_dados[10][2] = "m";
	$matriz_dados[11][0] = "#cidade_estado#";
	$matriz_dados[11][1] = $estagiario['municipio']."-".$estagiario['uf'];
	$matriz_dados[11][2] = "g";
	$matriz_dados[12][0] = "#endereco#";
	$matriz_dados[12][1] = $estagiario['endereco'];
	$matriz_dados[12][2] = "g";
	$matriz_dados[13][0] = "#cnpj#";
	$matriz_dados[13][1] = $estagiario['cnpj'];
	$matriz_dados[13][2] = "m";
	$matriz_dados[14][0] = "#cidade_estado_inst#";
	$matriz_dados[14][1] = $estagiario['municipioie']."-".$estagiario['ufie'];
	$matriz_dados[14][2] = "g";
	$matriz_dados[15][0] = "#endereco_inst#";
	$matriz_dados[15][1] = $estagiario['enderecoie'];
	$matriz_dados[15][2] = "g";
	$matriz_dados[16][0] = "#reitor#";
	$matriz_dados[16][1] = $estagiario[''];
	$matriz_dados[16][2] = "g";
	$matriz_dados[17][0] = "#representante_legal#";
	$matriz_dados[17][1] = $estagiario[''];
	$matriz_dados[17][2] = "g";
	$matriz_dados[18][0] = "#data#";
	$matriz_dados[18][1] = date("d/m/Y");
	$matriz_dados[18][2] = "d";	
	$matriz_dados[19][0] = "#numero_registro#";
	$matriz_dados[19][1] = "";
	$matriz_dados[19][2] = "g";
	$matriz_dados[20][0] = "#curso#";
	$matriz_dados[20][1] = $estagiario['curso'];
	$matriz_dados[20][2] = "g";
	$matriz_dados[21][0] = "#semestre_inicio#";
	$matriz_dados[21][1] = semestre(substr($estagiario['inicio_curso'],6,1));
	$matriz_dados[21][2] = "p";
	$matriz_dados[22][0] = "#ano_inicio#";
	$matriz_dados[22][1] = substr($estagiario['inicio_curso'],0,4);
	$matriz_dados[22][2] = "p";
	$matriz_dados[23][0] = "#semestre_fim#";
	$matriz_dados[23][1] = semestre(substr($estagiario['termino_curso'],6,1));
	$matriz_dados[23][2] = "p";
	$matriz_dados[24][0] = "#ano_fim#";
	$matriz_dados[24][1] = substr($estagiario['termino_curso'],0,4);
	$matriz_dados[24][2] = "p";
	$matriz_dados[25][0] = "#horario_inicio#";
	$matriz_dados[25][1] = "";
	$matriz_dados[25][2] = "m";
	$matriz_dados[26][0] = "#horario_fim#";
	$matriz_dados[26][1] = "";
	$matriz_dados[26][2] = "m";
	$matriz_dados[27][0] = "#area#";
	$matriz_dados[27][1] = $estagiario['area_atuacao'];
	$matriz_dados[27][2] = "g";
	$matriz_dados[28][0] = "#setor#";
	$matriz_dados[28][1] = $estagiario['projeto_setor'];
	$matriz_dados[28][2] = "g";
	$matriz_dados[29][0] = "#carga_horaria_diaria#";
	$matriz_dados[29][1] = floor($estagiario['carga_horaria']/5);
	$matriz_dados[29][2] = "p";
	$matriz_dados[30][0] = "#carga_horaria_diaria_extenso#";
	$matriz_dados[30][1] = extenso(floor($estagiario['carga_horaria']/5));
	$matriz_dados[30][2] = "m";
	$matriz_dados[31][0] = "#carga_horaria#";
	$matriz_dados[31][1] = $estagiario['carga_horaria'];
	$matriz_dados[31][2] = "p";
	$matriz_dados[32][0] = "#carga_horaria_extenso#";
	$matriz_dados[32][1] = extenso($estagiario['carga_horaria']);
	$matriz_dados[32][2] = "m";
	$matriz_dados[33][0] = "#horarios_estagio#";
	$matriz_dados[33][1] = horarios($estagiario['id'], 'e');
	$matriz_dados[33][2] = "g";
	$matriz_dados[34][0] = "#bolsa#";
	$matriz_dados[34][1] = $estagiario['remuneracao'];
	$matriz_dados[34][2] = "p";
	$matriz_dados[35][0] = "#bolsa_extenso#";
	$matriz_dados[35][1] = extenso($estagiario['remuneracao']);
	$matriz_dados[35][2] = "g";
	$matriz_dados[36][0] = "#vigencia#";
	$matriz_dados[36][1] = calcVigencia($estagiario['vigencia_inicio'],$estagiario['vigencia_fim']);
	$matriz_dados[36][2] = "p";
	$matriz_dados[37][0] = "#vigencia_extenso#";
	$matriz_dados[37][1] = extenso(calcVigencia($estagiario['vigencia_inicio'],$estagiario['vigencia_fim']));
	$matriz_dados[37][2] = "g";
	$matriz_dados[38][0] = "#vigencia_inicio#";
	$matriz_dados[38][1] = formata($estagiario['vigencia_inicio'],'redata');
	$matriz_dados[38][2] = "d";
	$matriz_dados[39][0] = "#vigencia_fim#";
	$matriz_dados[39][1] = formata($estagiario['vigencia_fim'],'redata');
	$matriz_dados[39][2] = "d";
	$matriz_dados[40][0] = "#dia#";
	$matriz_dados[40][1] = date("d");
	$matriz_dados[40][2] = "p";
	$matriz_dados[41][0] = "#mes#";
	$matriz_dados[41][1] = mes_extenso(date("m"));
	$matriz_dados[41][2] = "m";
	$matriz_dados[42][0] = "#ano#";
	$matriz_dados[42][1] = date("Y");
	$matriz_dados[42][2] = "p";	
	$matriz_dados[43][0] = "#cracha#";
	$matriz_dados[43][1] = $num_cracha;	
	$matriz_dados[44][0] = "#cep#";
	$matriz_dados[44][1] = $estagiario['cep'];	
	$matriz_dados[45][0] = "#telefone#";
	$matriz_dados[45][1] = formata($estagiario['tel_residencial'], 'telefone');
	$matriz_dados[46][0] = "#celular#";
	$matriz_dados[46][1] = formata($estagiario['tel_celular'], 'telefone');
	$matriz_dados[47][0] = "#email_embrapa#";
	$matriz_dados[47][1] = $estagiario['email_embrapa'];
	$matriz_dados[48][0] = "#email#";
	$matriz_dados[48][1] = $estagiario['email'];
	$matriz_dados[49][0] = "#ramal#";
	$matriz_dados[49][1] = $estagiario['ramal'];
	$matriz_dados[50][0] = "#nome_projeto#";
	$matriz_dados[50][1] = $estagiario['nome_projeto'];
	$matriz_dados[51][0] = "#numero_projeto#";
	$matriz_dados[51][1] = $estagiario['numero_projeto'];
	$matriz_dados[52][0] = "#complemento#";
	$matriz_dados[52][1] = ($estagiario['complemento'] == '') ? ' ' : '- ' . utf8_decode($estagiario['complemento']);
	$matriz_dados[53][0] = "#bairro#";
	$matriz_dados[53][1] = $estagiario['bairro'];
	$matriz_dados[54][0] = "#nmes#";
	$matriz_dados[54][1] = date('m');

    $data_vigencia_inicio = explode('/', formata($estagiario['vigencia_inicio'],'redata'));
    $dia_vigencia = $data_vigencia_inicio[0];
    $mes_vigencia = $data_vigencia_inicio[1];
    $ano_vigencia = $data_vigencia_inicio[2];

	$matriz_dados[55][0] = '#dia_vigencia_inicio#';
	$matriz_dados[55][1] = $dia_vigencia; 
	$matriz_dados[56][0] = '#mes_vigencia_inicio#';
	$matriz_dados[56][1] = mes_extenso((int)$mes_vigencia);
	$matriz_dados[57][0] = '#ano_vigencia_inicio#';
	$matriz_dados[57][1] = $ano_vigencia; 
	$matriz_dados[58][0] = '#supervisor#';
	$matriz_dados[58][1] = $estagiario['nome_supervisor'];
	$matriz_dados[59][0] = '#tipo_vinculo#';
    $matriz_dados[59][1] = tipo_vinculo($estagiario['tipo_vinculo']);
	$matriz_dados[60][0] = '#sexo#';
    $matriz_dados[60][1] = $estagiario['sexo'];
	$matriz_dados[61][0] = '#pronome_sexo#';
    $matriz_dados[61][1] = ($estagiario['sexo'] == 'f') ? 'a' : 'o';
	$matriz_dados[62][0] = '#PRONOME_SEXO#';
    $matriz_dados[62][1] = ($estagiario['sexo'] == 'f') ? 'A' : 'O';
	$matriz_dados[63][0] = '#data_convenio#';
    $matriz_dados[63][1] = formata($estagiario['data_convenio'],'redata');
	$matriz_dados[64][0] = '#numero_saic#';
    $matriz_dados[64][1] = $estagiario['numero_saic'];

	$matriz_dados[65][0] = "#horarios_curso#";
	$matriz_dados[65][1] = horarios($estagiario['id'], 'a');
	$matriz_dados[66][0] = '#cpf_supervisor#';
	$matriz_dados[66][1] = $estagiario['cpf_supervisor'];
    $matriz_dados[67][0] = "#determina_sexo#";
    $matriz_dados[67][1] = determina_sexo($estagiario['id'], $estagiario['sexo']);
    $matriz_dados[68][0] = "#beneficiario0#";
    $matriz_dados[68][1] = ($resultBeneficiarios[0]['nome_beneficiario'] == '') ? ' ' : $resultBeneficiarios[0]['nome_beneficiario'];
    $matriz_dados[69][0] = "#parentesco0#";
    $matriz_dados[69][1] = ($resultBeneficiarios[0]['parentesco'] == '') ? ' ': $resultBeneficiarios[0]['parentesco'];
    $matriz_dados[70][0] = "#beneficiario1#";
    $matriz_dados[70][1] = ($resultBeneficiarios[1]['nome_beneficiario'] == '') ? ' ' : $resultBeneficiarios[1]['nome_beneficiario'];
    $matriz_dados[71][0] = "#parentesco1#";
    $matriz_dados[71][1] = ($resultBeneficiarios[1]['parentesco'] == '') ? ' ': $resultBeneficiarios[1]['parentesco'];
    $matriz_dados[72][0] = "#beneficiario2#";
    $matriz_dados[72][1] = ($resultBeneficiarios[2]['nome_beneficiario'] == '') ? ' ' : $resultBeneficiarios[2]['nome_beneficiario'];
    $matriz_dados[73][0] = "#parentesco2#";
    $matriz_dados[73][1] = ($resultBeneficiarios[2]['parentesco'] == '') ? ' ': $resultBeneficiarios[2]['parentesco'];
    $matriz_dados[74][0] = "#beneficiario3#";
    $matriz_dados[74][1] = ($resultBeneficiarios[3]['nome_beneficiario'] == '') ? ' ' : $resultBeneficiarios[3]['nome_beneficiario'];
    $matriz_dados[75][0] = "#parentesco3#";
    $matriz_dados[75][1] = ($resultBeneficiarios[3]['parentesco'] == '') ? ' ': $resultBeneficiarios[3]['parentesco'];
    $matriz_dados[76][0] = "#beneficiario4#";
    $matriz_dados[76][1] = ($resultBeneficiarios[4]['nome_beneficiario'] == '') ? ' ' : $resultBeneficiarios[4]['nome_beneficiario'];
    $matriz_dados[77][0] = "#parentesco4#";
    $matriz_dados[77][1] = ($resultBeneficiarios[4]['parentesco'] == '') ? ' ': $resultBeneficiarios[4]['parentesco'];

    $data_vigencia_fim = explode('/', formata($estagiario['vigencia_fim'],'redata'));
    $dia_vigencia = $data_vigencia_fim[0];
    $mes_vigencia = $data_vigencia_fim[1];
    $ano_vigencia = $data_vigencia_fim[2];

	$matriz_dados[78][0] = '#dia_vigencia_fim#';
	$matriz_dados[78][1] = $dia_vigencia; 
	$matriz_dados[79][0] = '#mes_vigencia_fim#';
	$matriz_dados[79][1] = mes_extenso((int)$mes_vigencia);
	$matriz_dados[80][0] = '#ano_vigencia_fim#';
	$matriz_dados[80][1] = $ano_vigencia; 
	$matriz_dados[81][0] = '#horas_trab#';
	$matriz_dados[81][1] = $horas_trab[0]['total'];
	$matriz_dados[82][0] = '#vinculo#';
	$matriz_dados[82][1] = ($estagiario['tipo_vinculo'] == 'b') ? 'bolsista' : 'estagiário';
	$matriz_dados[83][0] = '#TIPO_VINCULO#';
	$matriz_dados[83][1] = ($estagiario['tipo_vinculo'] == 'b') ? 'BOLSISTA' : 'ESTAGIÁRIO';
	$matriz_dados[84][0] = '#Tipo_Vinculo#';
	$matriz_dados[84][1] = ($estagiario['tipo_vinculo'] == 'b') ? 'Bolsista' : 'Estagiário';
	$matriz_dados[85][0] = "#nasc#";
	$matriz_dados[85][1] = formata($estagiario['data_nascimento'],'redata');
	$matriz_dados[86][0] = "#termo_aditivo_inicio#";
	$matriz_dados[86][1] = formata($termo_aditivo["data_inicio"],'redata');
	$matriz_dados[87][0] = "#termo_aditivo_fim#";
	$matriz_dados[87][1] = formata($termo_aditivo["data_fim"],'redata');
	
	$data_TA_inicio = explode('/', formata($termo_aditivo['data_inicio'],'redata'));
    $dia_TA = $data_TA_inicio[0];
    $mes_TA = $data_TA_inicio[1];
    $ano_TA = $data_TA_inicio[2];
    
    $matriz_dados[88][0] = '#dia_ta_inicio#';
	$matriz_dados[88][1] = $dia_TA; 
	$matriz_dados[88][2] = 'dta';
	$matriz_dados[89][0] = '#mes_ta_inicio#';
	$matriz_dados[89][1] = mes_extenso((int)$mes_TA);
	$matriz_dados[89][2] = 'mta';
	$matriz_dados[90][0] = '#ano_ta_inicio#';
	$matriz_dados[90][1] = $ano_TA;
	$matriz_dados[90][2] = 'ata'; 
	
	$data_TA_inicio = explode('/', formata($termo_aditivo['data_fim'],'redata'));
    $dia_TA = $data_TA_fim[0];
    $mes_TA = $data_TA_fim[1];
    $ano_TA = $data_TA_fim[2];
    
    $matriz_dados[91][0] = '#dia_ta_fim#';
	$matriz_dados[91][1] = $dia_TA; 
	$matriz_dados[92][0] = '#mes_ta_fim#';
	$matriz_dados[92][1] = mes_extenso((int)$mes_TA);
	$matriz_dados[93][0] = '#ano_ta_fim#';
	$matriz_dados[93][1] = $ano_TA;
	
	$matriz_dados[94][0] = "#inst#";
	$matriz_dados[94][1] = $estagiario['instituicao'];
	$matriz_dados[95][0] = "#ra#";
	$matriz_dados[95][1] = $estagiario['ra'];
	
	
    $novotxt = gera_termo($corpo,$matriz_dados);
		
	fputs($punt,$novotxt);	
	fputs($punt, "}");//sinaliza o fim do arquivo rtf	
	fclose($punt);
	chmod($arq_saida,0777);//altera permissoes para poder editar etc
	return $arq_saida;
	
}

//Funcao de preenchimento do termo
function gera_termo($corpo, $matriz_dados){
		
	//substitui as strings no formato #string# pelo dado correspondente na matriz ou por linhas de tamanho indicado
	//for($i=0;$i<86;$i++){
	for ($i=0;$i<sizeof($matriz_dados);$i++){
		$corpo = rtf_replace($matriz_dados[$i][0],$matriz_dados[$i][1],$corpo,$matriz_dados[$i][2]);				
	}
	return rtf_acentuacao($corpo);	
	
}

//Substitui pelo valor ou por linhas para preenchimento, de acordo com o tamanho do campo
function rtf_replace($search, $replace, $texto, $tamanho){
	if(empty($replace)){
		switch($tamanho){
			case 'p':
				for($k=0;$k<9;$k++)
					$replace .= "_";
				break;
			case 'm':
				for($k=0;$k<26;$k++)
					$replace .= "_";					
				break;
			case 'g':
				for($k=0;$k<50;$k++)
					$replace .= "_";					
				break;
			case 'd':
				$replace = "____/____/____";
				break;	
			default:
				for($k=0;$k<26;$k++)
					$replace .= "_";					
				break;
		}
	}	
	$texto = str_replace($search,$replace,$texto);	
	return $texto;
}


function tipo_vinculo($tipo) {

    if($tipo == 'b')
        return "( X )Bolsista    (   ) Consultor   (   ) Empregado    (   ) Estagiário    (   ) Outros";
    else
        return "(   )Bolsista    (   ) Consultor   (   ) Empregado    ( X ) Estagiário    (   ) Outros";

}

function rtf_acentuacao($texto){
	$matriz_correspondencias[0][0] = "á";
	$matriz_correspondencias[0][1] = "\u225\'3f";
	$matriz_correspondencias[1][0] = "Á";
	$matriz_correspondencias[1][1] = "\u193\'3f";
	$matriz_correspondencias[2][0] = "é";
	$matriz_correspondencias[2][1] = "\u233\'3f";
	$matriz_correspondencias[3][0] = "É";
	$matriz_correspondencias[3][1] = "\u201\'3f";
	$matriz_correspondencias[4][0] = "í";
	$matriz_correspondencias[4][1] = "\u237\'3f";
	$matriz_correspondencias[5][0] = "Í";
	$matriz_correspondencias[5][1] = "\u205\'3f";
	$matriz_correspondencias[6][0] = "ó";
	$matriz_correspondencias[6][1] = "\u243\'3f";
	$matriz_correspondencias[7][0] = "Ó";
	$matriz_correspondencias[7][1] = "\u211\'3f";
	$matriz_correspondencias[8][0] = "ú";
	$matriz_correspondencias[8][1] = "\u250\'3f";
	$matriz_correspondencias[9][0] = "Ú";
	$matriz_correspondencias[9][1] = "\u218\'3f";

	$matriz_correspondencias[10][0] = "â";
	$matriz_correspondencias[10][1] = "\u226\'3f";
	$matriz_correspondencias[11][0] = "Â";
	$matriz_correspondencias[11][1] = "\u194\'3f";
	$matriz_correspondencias[12][0] = "ê";
	$matriz_correspondencias[12][1] = "\u234\'3f";
	$matriz_correspondencias[13][0] = "Ê";
	$matriz_correspondencias[13][1] = "\u202\'3f";
	$matriz_correspondencias[14][0] = "ô";
	$matriz_correspondencias[14][1] = "\u244\'3f";
	$matriz_correspondencias[15][0] = "Ô";
	$matriz_correspondencias[15][1] = "\u212\'3f";

	$matriz_correspondencias[16][0] = "ü";
	$matriz_correspondencias[16][1] = "\u252\'3f";
	$matriz_correspondencias[17][0] = "Ü";
	$matriz_correspondencias[17][1] = "\u220\'3f";
	
	$matriz_correspondencias[18][0] = "ñ";
	$matriz_correspondencias[18][1] = "\u241\'3f";
	$matriz_correspondencias[19][0] = "Ñ";
	$matriz_correspondencias[19][1] = "\u209\'3f";
	
	$matriz_correspondencias[20][0] = "ã";
	$matriz_correspondencias[20][1] = "\u227\'3f";
	$matriz_correspondencias[21][0] = "Ã";
	$matriz_correspondencias[21][1] = "\u195\'3f";
	$matriz_correspondencias[22][0] = "õ";
	$matriz_correspondencias[22][1] = "\u245\'3f";
	$matriz_correspondencias[23][0] = "Õ";
	$matriz_correspondencias[23][1] = "\u213\'3f";
	
	$matriz_correspondencias[24][0] = "ç";
	$matriz_correspondencias[24][1] = "\u231\'3f";
	$matriz_correspondencias[25][0] = "Ç";
	$matriz_correspondencias[25][1] = "\u199\'3f";
	
	$matriz_correspondencias[25][0] = "º";
	$matriz_correspondencias[25][1] = "\u186\'3f";		
	$matriz_correspondencias[26][0] = "°";
	$matriz_correspondencias[26][1] = "\u186\'3f";		

	$matriz_correspondencias[27][0] = "à";
	$matriz_correspondencias[27][1] = "\u224\'3f";
	$matriz_correspondencias[28][0] = "À";
	$matriz_correspondencias[28][1] = "\u192\'3f";
		
	for($i=0;$i<29;$i++){	
		$texto = str_replace($matriz_correspondencias[$i][0],$matriz_correspondencias[$i][1],$texto);
	}
	return $texto;
}

function horarios($id_estagiario, $tipo) {

    $query = "SELECT    dia, entrada, saida
              FROM      horarios
              WHERE     id_estagiario = {$id_estagiario} and tipo='{$tipo}';";
    $horarios = DB::fetch_all($query);

    $str = "";
    $anterior = 0;
    for($i = 0; $i < sizeof($horarios); $i++) {
        switch($horarios[$i]['dia']) {
        //segunda
        case 2:
            if($anterior == 2)
                $str .= " e das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            else{
                if($anterior != 0)
                    $str .= ', ';
                $str .= "segunda das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            }
            break;
        //terca
        case 3:
            if($anterior == 3)
                $str .= " e das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            else {
                if($anterior != 0)
                    $str .= ', ';
                $str .= "terça das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            }
            break;
        //quarta
        case 4:
            if($anterior == 4)
                $str .= " e das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            else{
                if($anterior != 0)
                    $str .= ', ';
                $str .= "quarta das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            }
            break;
        //quinta
        case 5:
            if($anterior == 5)
                $str .= " e das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            else{
                if($anterior != 0)
                    $str .= ', ';
                $str .= "quinta das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            }
            break;
        //sexta
        case 6:
            if($anterior == 6)
                $str .= " e das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            else{
                if($anterior != 0)
                    $str .= ', ';
                $str .= "sexta das " . $horarios[$i]['entrada'] . " às " . $horarios[$i]['saida'];
            }
            break;
        }
        $anterior = $horarios[$i]['dia'];
    }
    return $str;

}

function determina_sexo($id_estagiario, $sexo) {

    if($sexo == 'f')
        $str = "( X ) F   (   ) M";
    else 
        $str = "(   ) F   ( X ) M";

    return $str;
}

function semestre($semestre) {

    if($semestre == 1)
        return "1° (primeiro)";
    else
        return "2° (segundo)";

}

?>
