<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");
	session_start();
	$cor = true;
			
    $pagina = isset($_GET['pag']) ? $_GET['pag'] : 'a';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'estagiario';
	$offset = 10; //resultados por pagina.		
	if (!$_GET["pagina"]) $pagina = 1; else $pagina = $_GET["pagina"];
	$qryStr = "SELECT id_supervisor FROM users WHERE id = '".$_SESSION["USERID"]."'";
	$qry = sql_executa($qryStr);
	$row = sql_fetch_array($qry);
	$id_supervisor = $row[0];
?>
			
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}

</style>
<?php
$qryStr = "SELECT nome, id_categoria, id_status, vigencia_inicio, vigencia_fim 
			FROM estagiarios 
			WHERE id_supervisor = '".$id_supervisor."' 
			ORDER BY id_status ASC, vigencia_inicio DESC 
			LIMIT ".$offset." OFFSET ".(($offset*$pagina)-$offset)."";
$qry = sql_executa($qryStr);
if($qry) {
	$qtd = sql_num_rows($qry);
	if($qtd>0) { ?>
		<table width="100%" border="0" cellpadding="1" cellspacing="1" class="lista_registros2">
			<tr>
	  			<th width="40%" align="left"><strong>Nome do aluno</strong></th>
	  			<th width="17%"><strong>Categoria</strong></th>
	  			<th width="13%"><strong>Status</strong></th>
	  			<th width="30%"><strong>Vigência</strong></th>
			</tr>
			</table>
		
<?php 
		while ($row = sql_fetch_array($qry)) {	
			$cor = !$cor;
			if($cor) {
				?><table width="100%" border="0" cellpadding="0" cellspacing="0" class="lista_registros0"><?php
			} else {
				?><table width="100%" border="0" cellpadding="0" cellspacing="0" class="lista_registros1"><?php
			}
			?>
			<tr>
				<td align="left" width="40%"><?php echo $row["nome"]; ?></td>
				<?php
					$qryStrCat = "SELECT descricao FROM categorias WHERE id_categoria = '".$row["id_categoria"]."'";
					$qryCat = sql_executa($qryStrCat);
					$rowCat = sql_fetch_array($qryCat); 
				?>
				<td align="center" width="17%"><?php echo $rowCat[0]; ?></td>
				<?php 
					$qryStrStatus = "SELECT descricao FROM status_estagiario WHERE id_status = '".$row["id_status"]."'";
					$qryStatus = sql_executa($qryStrStatus);
					$rowStatus = sql_fetch_array($qryStatus); 
				?>
				<td align="center" width="13%"><?php echo $rowStatus[0]; ?></td>
				<?php
					$dataInicio = new DateTime($row["vigencia_inicio"]);
					$dataFim = new DateTime($row["vigencia_fim"]);
					$dataInicio = $dataInicio->format('d/m/Y');
					$dataFim = $dataFim->format('d/m/Y'); 
				?>
				<td align="center" width="30%"><?php echo $dataInicio; ?> - <?php echo $dataFim; ?></td>
			</tr>
			</table>
			<?php
		}
		$qryStr = "SELECT nome FROM estagiarios WHERE id_supervisor = '".$id_supervisor."'";
		$qry = sql_executa($qryStr);
		$qtdAlunos = sql_num_rows($qry);
		echo "<table width=\"500\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" class=\"listaEstagiarios\">
<tr><td align=\"left\" style=\"padding-left:20px;\">";
		if($pagina-1>0) {
			echo "<a href=\"javascript://\" onclick=\"ajax.loadDiv('divManip','estagiario.lista.php?pagina=".($pagina-1)."');\"><img border=\"0\" align=\"middle\" src=\"../img/anterior.gif\" width=\"28\" height=\"40\" />Anterior</a>";
		}
		
		echo "</td>
<td align=\"right\" style=\"padding-right:20px;\">";
		if($qtdAlunos > ($pagina * $offset)) {
			echo "<a href=\"javascript://\" onclick=\"ajax.loadDiv('divManip','estagiario.lista.php?pagina=".($pagina+1)."');\">Proxima<img align=\"middle\" src=\"../img/proximo.gif\" width=\"28\" height=\"40\" border=\"0\"/></a>";
		}
		
		echo "</td>
</tr>
</table>	";
	} else {
		//Nenhum estagiario
		echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#F5FAFA'>
					<td align='center'><span align='center' style='color:black;'>Você não possui alunos cadastrados.</span></td>
				</tr>
			</table>";
	}
} else {
	//Erro ao fazer consulta ao BDD
	echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#F5FAFA'>
					<td align='center'><span align='center' style='color:black;'>Ocorreu um erro ao tentar acessar a base de dados.</span></td>
				</tr>
			</table>";
}	
?>
