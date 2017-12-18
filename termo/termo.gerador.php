<?php 

$qtd_abas = 0;
require_once("../sessions.php");
if(!$_SESSION["USERID"]){
	echo "<script language='javascript'> window.location.href='../login.php'; </script>";
}

include_once("../functions/functions.database.php");//temporario 
include_once("../functions/functions.forms.php");
include_once("termo.functions.php");
require_once("../classes/DB.php");


function atualiza_cracha($idEstagiario, $num_cracha) {
    $query = "SELECT  cracha
              FROM    estagiarios
              WHERE   id = {$idEstagiario};";
    $cracha = DB::fetch_all($query);

    if($cracha[0]['cracha'] != $num_cracha) {
        $query = "UPDATE  estagiarios
                  SET     cracha = $num_cracha
                  WHERE   id = {$idEstagiario};";
        DB::execute($query);
    }
}

$idEstagiario = $_GET["id"];
$termo = $_GET["t"];

//Selecionando os arquivos "a ser modificado" e "a ser gerado"
switch($termo){
	case 1:
		$arq_entrada = "../termos_rtf/termo_aditivo.rtf";
		$arq_saida = "../termos_gerados/termo_aditivo";		
		break;
	case 2:
		$arq_entrada = "../termos_rtf/termo_compromisso_bolsista.rtf";
		$arq_saida = "../termos_gerados/termo_compromisso_bolsista";
		break;
	case 3:
		$arq_entrada = "../termos_rtf/termo_biblioteca.rtf";
		$arq_saida = "../termos_gerados/termo_biblioteca";
		break;
	case 4:
		$arq_entrada = "../termos_rtf/termo_certidao_negativa.rtf";
		$arq_saida = "../termos_gerados/termo_certidao_negativa";
		break;
	case 5:
		$arq_entrada = "../termos_rtf/termo_certificado.rtf";
		$arq_saida = "../termos_gerados/termo_certificado";
		break;
    case 6:
		$arq_entrada = "../termos_rtf/termo_checklist_bolsista.rtf";
		$arq_saida = "../termos_gerados/termo_checklist_bolsista";
		break;
    case 7:
		$arq_entrada = "../termos_rtf/termo_checklist_estagiario.rtf";
		$arq_saida = "../termos_gerados/termo_checklist_estagiario";
		break;
    case 8:
		$arq_entrada = "../termos_rtf/termo_checklist_desligamento.rtf";
		$arq_saida = "../termos_gerados/termo_checklist_desligamento";
		break;
	case 9:
		$arq_entrada = "../termos_rtf/termo_codigo_conduta.rtf";
		$arq_saida = "../termos_gerados/termo_codigo_conduta";		
		break;
	case 10:
		$arq_entrada = "../termos_rtf/termo_compromisso_nao_obrigatorio.rtf";
		$arq_saida = "../termos_gerados/termo_compromisso_nao_obrigatorio";		
		break;
	case 11:
		$arq_entrada = "../termos_rtf/termo_compromisso_obrigatorio.rtf";
		$arq_saida = "../termos_gerados/termo_compromisso_obrigatorio";		
		break;	
	case 12:
		$arq_entrada = "../termos_rtf/termo_confidencialidade.rtf";
		$arq_saida = "../termos_gerados/termo_confidencialidade";		
		break;	
	case 13:
		$arq_entrada = "../termos_rtf/termo_cracha.rtf";
		$arq_saida = "../termos_gerados/termo_cracha";		
        $num_cracha = $_GET['num_cracha'];
        atualiza_cracha($idEstagiario, $num_cracha);
		break;
	case 14:
		$arq_entrada = "../termos_rtf/termo_distrato.rtf";
		$arq_saida = "../termos_gerados/termo_distrato";
		break;
	case 15:
		$arq_entrada = "../termos_rtf/termo_indice_pasta.rtf";
		$arq_saida = "../termos_gerados/termo_indice_pasta";
		break;
	case 16:
		$arq_entrada = "../termos_rtf/termo_seguro_vida.rtf";
		$arq_saida = "../termos_gerados/termo_seguro_vida";
		break;
	case 17:
		$arq_entrada = "../termos_rtf/termo_indice_pasta_bolsista.rtf";
		$arq_saida = "../termos_gerados/termo_indice_pasta_bolsista";
		break;
	case 18:
		$arq_entrada = "../termos_rtf/termo_compromisso_pibic.rtf";
		$arq_saida = "../termos_gerados/termo_compromisso_pibic";
		break;
    case 19:
		$arq_entrada = "../termos_rtf/termo_checklist_renovacao_estagiario.rtf";
		$arq_saida = "../termos_gerados/termo_checklist_renovacao_estagiario";
		break;
	default:
		$arq_entrada = "../termos_rtf/termo_compromisso_obrigatorio.rtf";
		$arq_saida = "../termos_gerados/termo_compromisso_obrigatorio";
		break;	
}

//gera o arquivo rtf
$arq = isset($num_cracha) ? rtf($arq_entrada, $arq_saida, $termo, $idEstagiario, $num_cracha) : rtf($arq_entrada, $arq_saida, $termo, $idEstagiario);
echo "<script language='javascript'>window.open('{$arq}');window.close();</script>";		
?>

