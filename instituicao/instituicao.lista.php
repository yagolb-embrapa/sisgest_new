<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");
	require_once("../sessions.php");

	$cor = true;
			
	if (!$_GET["pag"]) $pagina = 'a'; else $pagina = $_GET["pag"];
	$offset = 10; //resultados por pagina.		
	
	//selecao de estagiarios	
	$qryStr = "SELECT id,razao_social,id_municipio FROM instituicoes_ensino 
	 			  WHERE razao_social LIKE '".$pagina."%' OR razao_social LIKE '".strtoupper($pagina)."%' ORDER BY razao_social ";
		
	//botao de inclusao
	echo "<div align='center' style='padding: 0 0 10px 0;'>
		<input type='button' value='Incluir Nova Instituição' style='padding: 2px 15px 2px 15px; height:24px;' onClick=\"document.location.href='instituicao.inclusao.php';\"></div>";
	
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
$rowLim = sql_fetch_array(sql_executa("SELECT COUNT(*) AS ct FROM instituicoes_ensino;"));		
if (ceil($rowLim[0]/$offset)==$pagina) $numreg = $rowLim[0];
else $numreg = $pagina*$offset;

$qry = sql_executa($qryStr);
if(sql_num_rows($qry)>0){
	//Imprime uma linha com cada estagiario daquela letra
	while ($row = sql_fetch_array($qry)){
		$cor = !$cor;
	
		echo "
		<div class='lista_registros";echo ($cor)?"1":"0"; echo "'>	
	  	<table width='100%' height='36' border='0' cellpadding='0' cellspacing='0' class='lista_registros_content'>
        <tr>
          <td height='18%'>";          
          	echo (strlen($row['razao_social'])>45)?substr($row['razao_social'],0,42)."...":$row['razao_social'];
		echo "</td>
			 <td width='17%' rowspan='2' align='center' valign='middle'>
				<a href='javascript://' onclick=\"document.location.href='instituicao.visualizacao.php?id=".$row['id']."'\">
				<img src='../img/icone_lupa.png' width='16' height='16' />Visualizar Dados</a> </td>		  						
          <td width='15%' rowspan='2' align='center' valign='middle'>
         	<a href='javascript://' onclick=\"document.location.href='instituicao.edicao.php?id=".$row['id']."';\">         	
         	<img src='../img/icon_edit.gif' width='16' height='16' border='0'>Editar Dados</a></td>          
          <td width='17%' rowspan='2' align='center' valign='middle'>";
          if($_SESSION['USERNIVEL'] == 'a'){
         	echo "<a href='javascript://' onclick=\"if (confirm('Deseja realmente excluir a instituição? Esta operação não poderá ser desfeita.')){ajax.loadDiv('divManip','instituicao.exclusao.php?id=".$row['id']."');}\">
         	<img src='../img/icon_delete.gif' width='16' height='16' border='0'>Excluir Instituição</a> </td>";
          }
          echo "</tr>
        <tr>
          <td height='18' style='font-size:8pt;'><strong>Município: </strong>";
         if($row['id_municipio'] != 0){
          	$q_mun = "SELECT * FROM municipios WHERE id = {$row['id_municipio']};";
          	$r_mun = sql_executa($q_mun);          
          	if(sql_num_rows($r_mun)>0){
          		$c_mun = sql_fetch_array($r_mun);  			         
         		echo $c_mun['nome']."-".$c_mun['uf']."</td></tr>";
         	}
         }else{
				echo "<i>Não preenchido</i></td></tr>";          
         }
      echo "</table>
	</div>";	
		
	}
}else{
	//Nao encontrou nenhum estagiario com aquela letra
	echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#F5FAFA'>
					<td align='center'><span align='center' style='color:black;'>Nenhuma instituição cadastrada com a letra <b>{$pagina}</b>.</span></td>
				</tr>
			</table>";
}	
	
echo "<br/><div id='paginacao' align='center'>";
			$letras = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','w','y','z');
			foreach ($letras as $letra) {
				if($letra==$pagina)				
					echo "[<span style='color:black;font-weight:bold;'>" .$letra. "</span>]";									
				else 
					echo "<a href=javascript:// onClick=\"ajax.loadDiv('divManip','instituicao.lista.php?pag=".$letra."');\">" . $letra . "</a> ";
				echo "&nbsp;";
				
				
			}
			echo "</div>";	
	
	
	
	?>
