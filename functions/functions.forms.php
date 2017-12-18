<?php
 
//funcao para alternancia de cores em linhas de formularios
function alterna_cor($n){	 	
	if ($n%2 == 1)
		return " specalt ";
	else
		return " specalt ";
}

//retorna true se o valor passado estiver no formato correto e false caso contrario 
function valida($valor,$tipo){
	switch($tipo){

		case 'data':// Formato DD/MM/AAAA						 
			if (!preg_match("/^\d{1,2}\/\d{1,2}\/\d{4}$/", $valor)) 
				return false;								
			if(!valida_data($valor))								
				return false;			
			break;
				
		case 'cep':// Formato XXXXX-XXX			
			if (!preg_match("/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/", $valor))
				return false;					
			break;
		
		case 'email':// Formato xxx@xxx.xxx
			if (!preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $valor))
				return false;			
			break;
				
		case 'cpf':// Formato xxx.xxx.xxx-xx 			
			if (!preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/", $valor)) 
				return false;			
			break;
			
		case 'cnpj':
			if (!preg_match("/^([0-9]){2}.([0-9]){3}.([0-9]){3}/[0-9]{4}-[0-9]{2}$/", $valor))
				return false;
			break;
		
		default: 
			return false;
	}
	return true;
}

//por enquanto só e usado pra data
function formata($valor, $tipo){
	if(empty($valor))
		return;
	switch($tipo){
		case 'data':		
			return substr($valor,-4)."-".substr($valor,3,2)."-".substr($valor,0,2);			
			break;
		case 'redata':
			return substr($valor,-2)."/".substr($valor,5,2)."/".substr($valor,0,4);
			break;								
        case 'telefone':
            list($ddd,$telefone) = explode(' ', $valor);
            return '(' . $ddd . ') ' . $telefone;
            break;
	}
}

//verifica se nao ha combinacao irregular de dia e mes e ano
function valida_data($valor){
	$dia = substr($valor,0,2);
	$mes = substr($valor,3,2);
	$ano = substr($valor,-4);
	
	$meses31 = array(1,3,5,7,8,10,12);	
	if($dia < 1 || $dia > 31)//basico para dia
		return false;
	if($mes < 1 || $mes > 12)//basico para mes
		return false;
	
	switch($dia){
		case 31:			
			if(!in_array($mes,$meses31))//so meses do vetor tem 31 dias				
				return false;			
			break;			
		case 30: 
			if($mes == 2)//so fevereiro nao pode
				return false;
			break;
		case 29:
			if($mes == 2 && !($ano%4==0))//se for fevereiro, verifica se e bissexto
				return false;
			break;
		default:
			return true;					
	}
	return true;
}

/* Cria a tabela para inserção de horarios */
function tabela_horarios($tipo, $nome){

	//tr de titulo
	$tabela = "<table width='100%' class='formulario' align='center' style='border:none;'>       
  	  	 <tr align='center'><td colspan='2'><div align='center' style='margin: 0 0 0px 0; padding: 2px 2px 2px 2px;'></div></td></tr>	
		<tr class='specalt' align='center'>
				<td colspan='5' align='center'><span><b>Horários {$tipo}</b></span></td>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";		

	//tr de cabecalho			       
	$tabela .= "  <tr class='specalt' align='center'>        
        <td width='20%'><span><b>Dia</b></span></td>
        <td width='20%'><span><b>Entrada</b></span></td>
        <td width='20%'><span><b>Saída</b></span></td>
        <td width='20%'><span><b>Entrada</b></span></td>
        <td width='20%'><span><b>Saída</b></span></td>                
        </tr>";

	$dias = array("Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira");                

	//Imprime linha a linha
    for($j=0;$j<5;$j++){
        $tabela .= "<tr class='specalt' align='center'>
            <td ><span>{$dias[$j]}</span></td>";
        for($i=0;$i<4;$i++){
            $indiceh = $nome.$j.$i."h";
            $indicem = $nome.$j.$i."m";     
            $tabela .= "<td><span><input type='text' class='horario' maxlength='2' id='{$nome}{$j}{$i}h' name='{$nome}{$j}{$i}h' value='".$_POST[$indiceh]."'   onKeyPress='mascara(this, mnum);'>h <input type='text' class='horario' maxlength='2' id='{$nome}{$j}{$i}m' name='{$nome}{$j}{$i}m' value='".$_POST[$indicem]."'  onKeyPress='mascara(this, mnum);'>min </span></td>";
        }	      
    }
    $tabela .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table>";

	return $tabela;
}

/* Função para salvar os horarios da tabela criada pela funcao acima */
function salva_horarios($id, $post){
	/*a indicacao de horarios esta no formato "htxyp", 
	onde t é o tipo estagio ou aula 
	x é inteiro indicando dia da semana
	y indica entrada ou saida
	p indica hora ou minuto	
	*/
	
	extract($post);
	//print_r($post);
	echo "<br><br><br>";
	
	$tipos = array("a","e");//tipos de horario podem ser aulas ou estagio
	$periodos = array("h","m");//periodos podem ser hora ou minuto
	
	foreach($tipos as $tipo){
		for($j=0;$j<5;$j++){//percorrendo o inteiro x, para dias da semana
			$k=0;$flag_per = 0;
			for($i=0;$i<4;$i++){//percorrendo o inteiro y, para entradas ou saidas (0 e 2 entrada | 1 e 3 saida)
				foreach($periodos as $periodo){					
					if($periodo == "h"){
						//se a hora de entrada estiver vazia, pula 
						if($flag_per == 1 && $k%2 == 1){$k++; $flag_per = 0; break;}						 
						$indicehora = "h".$tipo.$j.$i.$periodo;						
						$hora = $_POST[$indicehora];
						//se a hora de saida estiver vazia, temos que retirar a hora de entrada do array
						if($k%2 == 1 && empty($hora)) {$k++; array_pop($hora_minuto[$tipo][$j]); break;} 						
						if(empty($hora) || $hora == "00" || $hora == "0"){ $k++; $flag_per = 1; break;}
					}else{
						$indiceminuto = "h".$tipo.$j.$i.$periodo;						
						$minuto = $_POST[$indiceminuto];
						if(empty($minuto)) $minuto = "00";
						else if(strlen($minuto) == 1) $minuto = "0".$minuto; 
						if(strlen($hora) == 1) $hora = "0".$hora; 						
						$hora_minuto[$tipo][$j][$k] = $hora.":".$minuto;
						//echo "Dia {$j}, e/s {$k} -> ".$hora_minuto[$tipo][$j][$k]."<br>";
						$k++;
						unset($hora);unset($minuto);						
					}
				}			
			}			
		}	
	}		
	// Montando querys de insercao
	foreach($tipos as $tipo){
		for($j=0;$j<5;$j++){
			for($i=0;$i<4;$i++){
				if(!empty($hora_minuto[$tipo][$j][$i])){
					if($i%2==0) $entrada = $hora_minuto[$tipo][$j][$i];
					else {
						$saida = $hora_minuto[$tipo][$j][$i];
					 			
						$query_horarios .= "INSERT INTO horarios (id_estagiario, tipo, entrada, saida, dia) 
						VALUES (".$id.", '".$tipo."', '".$entrada."', '".$saida."', ".($j+2).");";						
					}
				} 			
			}
		}	
	}
	// Executando as querys
	if(!empty($query_horarios)){
		$result_query = sql_executa($query_horarios);		
		if($result_query) return true;
		else return false;
	}else{
		return true;	
	}	 	
}

//recebe um inteiro entre 0 e 5000 e retorna sua leitura por extenso (quase bom!)
function extenso($num){	
	$ext[0] = 'zero';
	$ext[1] = 'um';
	$ext[2] = 'dois';
	$ext[3] = 'três';
	$ext[4] = 'quatro';
	$ext[5] = 'cinco';
	$ext[6] = 'seis';
	$ext[7] = 'sete';
	$ext[8] = 'oito';
	$ext[9] = 'nove';
	$ext[10] = 'dez';
	$ext[11] = 'onze';
	$ext[12] = 'doze';
	$ext[13] = 'treze';
	$ext[14] = 'quatorze';
	$ext[15] = 'quinze';
	$ext[16] = 'dezesseis';
	$ext[17] = 'dezessete';
	$ext[18] = 'dezoito';
	$ext[19] = 'dezenove';
	$ext[20] = 'vinte';	
	$ext[30] = 'trinta';
	$ext[40] = 'quarenta';
	$ext[50] = 'cinquenta';
	$ext[60] = 'sessenta';
	$ext[70] = 'setenta';
	$ext[80] = 'oitenta';
	$ext[90] = 'noventa';
	$ext[100] = 'cem';
	$ext[200] = 'duzentos';
	$ext[300] = 'trezentos';
	$ext[400] = 'quatrocentos';
	$ext[500] = 'quinhentos';
	$ext[600] = 'seiscentos';
	$ext[700] = 'setecentos';
	$ext[800] = 'oitocentos';
	$ext[900] = 'novecentos';
	$ext[1000] = 'mil';	
	$ext[2000] = 'dois mil';
	$ext[3000] = 'três mil';
	$ext[4000] = 'quatro mil';
	$ext[5000] = 'cinco mil';
	
	//pega o numero de digitos do numero
	$tamanho = strlen($num);	
	
	//verifica algarismo a algarismo e verifica a nomenclatura dependendo do posicionamento dele (adicionando zeros)	
	if($tamanho == 1)
		return $ext[$num];
	if($tamanho > 1 && $tamanho < 5) {
        if($tamanho == 2 && substr($num, 0, 1) == 1)
            return $ext[$num];
        else {
            for($i=1;$i<=$tamanho;$i++){
                $algarismo = substr($num,-$i,1);
                if($algarismo != 0){
                    $indice = $algarismo;
                    //adiciona zeros à direita
                    for($j=0;$j<$i-1;$j++)
                        $indice .= "0";
                    if(empty($string))
                        $string = $ext[$indice];
                    elseif($i==4)
                        $string = $ext[$indice]." ".$string;
                    else
                        $string = $ext[$indice]." e ".$string;
                }
            }
        }
		return $string;		
	}
	return;	
}

//calcula o tempo aproximado em meses da vigencia, a partir da data de inicio e de fim
function calcVigencia($inicio, $fim){
	
	$inicio = formata($inicio,'redata');
	$fim = formata($fim,'redata');
	//echo "INICIO: ".$inicio."FIM: ".$fim;
	//confere formato das datas
	if(!valida($inicio,'data')) {return;}
	if(!valida($fim,'data')) {return;}
	
	//separando valores da data  			
	$diai = substr($inicio,0,2);
	$mesi = substr($inicio,3,2);
	$anoi = substr($inicio,-4);
	$diaf = substr($fim,0,2);
	$mesf = substr($fim,3,2);
	$anof = substr($fim,-4);
	
	//timestamp das datas
	$datai = mktime(0,0,0,$mesi,$diai,$anoi);
	$dataf = mktime(0,0,0,$mesf,$diaf,$anof);
			
	if($dataf < $datai){
		//something is wrong!!
	}else{
		$time = $dataf - $datai;
			
		$months = round($time/2592000);
		$rest = round(($time % 2592000)/86400);				
		return $months;														
	}		
	return;
}

//retorna o mes por extenso
function mes_extenso($mes){
	$meses = array("", "janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
	return $meses[(int)$mes];
}

//se a variavel estiver vazia, retorna linha no tamanho definido
function linha ($var, $tipo){
	if(!empty($var)){
		return $var;	
	}else{
		switch($tipo){
			case 'peq':
				return "_________";
				break;			
			case 'med':
				return "_______________________";
				break;
			case 'gra':
				return "__________________________________________";	
				break;
			case 'data':
				return "____/____/____";
				break;					
		}	
	}
	return "_______________________";//padrao
		
}

function cabecalhoTermo(){
	echo "<div class='divTitulo'>
				<center><img src='../img/embrapa_logo.gif' width='450' height='150'></center>
			</div>";			
}

function rodapeTermo(){
	echo "<div class='rodapeTermos' align='center'>
				Empresa Brasileira de Pesquisa Agropecuária<br>
				Ministério da Agricultura, Pecuária e Abastecimento<br>
				Av. André Tosello, 209 - Caixa Postal 6041, Barão Geraldo - 13083-970 - Campinas - SP<br>
				Telefone (19) 3789-5700  Fax (19) 3289-9594<br>
				www.cnptia.embrapa.br
			</div>";
}

?>
