<?php 

$qtd_abas = 0;
//require_once("../sessions.php");

require_once("../classes/DB.php");
//require_once("../inc/header.php");

include("../functions/functions.database.php");//temporario 
//include("../functions/functions.forms.php");


//if(!$_SESSION["USERID"]){
//	echo "<script language='javascript'> window.location.href='../login.php'; </script>";	
//}

$origem_recursos = DB::fetch_all("SELECT r.id, r.origem FROM origens_recursos r where r.origem <> 'CNPQ' and r.origem <> 'Bolsa'");

function getCb($name,$count=30){
	$ret= "<select name='".$name."[]' style='font-size:8pt;'>\n";
	for ($i=0; $i<=$count; $i++){
		$ret.= "\t<option value='".$i."'>".$i."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}

function getCbOrigem($index,$array){
	if (isset($array)){
		echo "<select name='origem[]'  style='font-size:8pt;'>\n";
		foreach ($array as $origem_recurso){
			echo "\t<option value='".$origem_recurso["id"]."'";
			if ($origem_recurso["id"]==$index) echo " selected";
			echo ">".$origem_recurso["origem"]."</option>\n";
		}
		echo "</select>\n";	
	}	
}

function getCbFOrigem($array){
	if (isset($array)){
		echo "<select name='forigem'  style='font-size:8pt;' onchange='changeFilter(this.value);'>\n";
		echo "\t<option value='-1'>Todos</option>\n";
		foreach ($array as $origem_recurso){
			echo "\t<option value='".$origem_recurso["id"]."'";
			echo ">".$origem_recurso["origem"]."</option>\n";
		}
		echo "</select>\n";	
	}	
}

$origem = $_GET['origem'];
$periodo = explode('/', $_GET['periodo']);
$meses = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
?>
<!-- html>
<head>
	<title>SisGest - Embrapa Informática Agropecuária</title>
<meta http-equiv="content-type" content="text/ht" charset="UTF-8" >

	<link href="../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/style.form.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css" />
	<link href="../css/abas.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript" src="../js/masks.js"></script>	
    <script language="javascript" src="../js/TAjax.js"></script>	
    <script language="javascript" src="../js/jquery.js"></script>	
</head>
<body-->
<tr><td>
    <div align="center">
    <script>
		function applyChecks(checked){
			var checkes = document.getElementsByName("finalList[]");
			for (index in checkes)
				checkes[index].checked=checked;
		}

		function changeFilter(value){

			var origens = document.getElementsByName("origem[]");
			var checkes = document.getElementsByName("finalList[]");

			for (index in origens){
				if (origens[index].value==value)
					checkes[index].checked=true;
			}
			
		}
		
    </script>
    <div align='left' class='divTitulo' style="padding-top:4em; padding-bottom: 3dlem;">
    <table width="100%">
    <tr>
    <td align="left"><span class='titulo'>.: Folha de pagamentos de estagi&aacute;rios</span></td>
    <td align="right">Selecionar: 
    <a href="javascript://" onclick="applyChecks(true)">todos</a> | <a href="javascript://"  onclick="applyChecks(false)">nenhum</a></td>
    </tr>
    </table>
    
		
	</div>
    <form id="frmPagamento" target="_blank" method="post" action="relatorio.pagamento.estagiario.php">
    	<table align='center' width='800' class='formPrintSmall' cellspacing='1' cellpadding="0">
    		<tr>
    			<th width="30%">Estagiário</th>
    			<th>Supervisor</th>
    			<th width="5%">Horas</th>
    			<th width="20%">Fonte do recurso</th>
    			<th width="7%">Dias a descontar</th>
    			<th width="7%">Dias a descontar VT</th>    			
    			<th width="14%">Dias a pagar recesso rem. não gozado</th>
    			<th width="7%">Exibir na folha</th>
    		</tr>
    		<?php 
    		
    		$sql = "SELECT e.id, e.nome, e.carga_horaria, s.nome supervisor, e.id_origem_recursos FROM estagiarios e 
    		inner join supervisores s on (e.id_supervisor=s.id) where e.status = 1 and e.tipo_vinculo='e' ";
    		if (isset($_GET["idFonte"])) $sql.="and e.id_origem_recursos = ".$_GET["idFonte"]." ";
    		$sql .= "order by e.nome";
    		DB::execute($sql);
    		
    		$classe  = 'spec';
    		while ($estagiario = DB::fetch()){
    			$classe = ($classe == "specalt")?"spec":"specalt";
    			echo "<tr class=\"".$classe."\">";
    			echo "<input type='hidden' name='idEstagiario[]' value='".$estagiario["id"]."' />";
    			echo "<td>".$estagiario["nome"]."</td>";
    			echo "<td>".$estagiario["supervisor"]."</td>";
    			echo "<td align='center'>".$estagiario["carga_horaria"]."<input type='hidden' name='cargaHoraria[]' value='".$estagiario["carga_horaria"]."' /></td>";
    			echo "<td>";
    			getCbOrigem($estagiario["id_origem_recursos"],$origem_recursos);
    			echo "</td>";
    			echo "<td align='center'>".getCb("diasDescontar")."</td>";
    			echo "<td align='center'>".getCb("diasDescontarVT")."</td>";
    			echo "<td align='center'><input type=\"text\" name=\"diasRecesso[]\" /></td>";
    			echo "<td align='center'><input type=\"checkbox\" name=\"finalList[]\" value=\"{$estagiario["id"]}\"/></td>";
    			echo "</tr>";
    		}
    		
    		?>
    	</table>
    	Filtrar fontes de recursos <?php getCbFOrigem($origem_recursos); ?><br/>
    	</form>
    	<a href="javascript://" onclick="document.getElementById('frmPagamento').submit();" style="color:blue;">Gerar relat&oacute;rio</a>
    </div>
    </tr></td></table>
<?php 
//include_once('../inc/copyright.php');
?>
