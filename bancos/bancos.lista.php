<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");
	require_once("../sessions.php");

	$cor = true;
			
	if (!$_GET["pag"]) $pagina = 'a'; else $pagina = $_GET["pag"];
	$offset = 10; //resultados por pagina.		
	
	$pagina = strtolower($pagina);
	//selecao de bancos	
	$qryStr = "select b.*, exists(select id from estagiarios where id_banco = b.id) em_uso from bancos b where ";
	//verificando se é a letra b (pra evitar o retorno de todos os registros que comecem com 'banco')
	if ($pagina=='b') $qryStr .= "(lower(b.banco) like 'b%' and lower(b.banco) not like 'banco %') or lower(b.banco) like 'banco b%' ";
	else $qryStr .= "lower(b.banco) like '{$pagina}%' or lower(b.banco) like 'banco {$pagina}%' ";
	//verificacao feita... ordenando
	$qryStr .= "order by b.banco";
	
	
		
	//botao de inclusao
	echo "<div align='center' style='padding: 0 0 10px 0;'>
		<input type='button' value='Incluir Novo Banco' style='padding: 2px 15px 2px 15px; height:24px;' onClick=\"document.location.href='bancos.inclusao.php';\"></div>";
	
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

$qry = sql_executa($qryStr);
if(sql_num_rows($qry)>0){
	//Imprime uma linha com cada banco daquela letra
	while ($row = sql_fetch_array($qry)){
		$cor = !$cor;
	
		echo "
		<div class='lista_registros";echo ($cor)?"1":"0"; echo "'>	
	  	<table width='100%' height='36' border='0' cellpadding='0' cellspacing='0' class='lista_registros_content'>
        <tr>
          <td height='18%'>";          
          	echo (strlen($row['banco'])>45)?substr($row['banco'],0,42)."...":$row['banco'];
		echo "</td>
			 <td width='17%' rowspan='2' align='center' valign='middle'>
				<a href='javascript://' onclick=\"document.location.href='bancos.visualizacao.php?id=".$row['id']."'\">
				<img src='../img/icone_lupa.png' width='16' height='16' />Visualizar Dados</a> </td>		  						
          <td width='15%' rowspan='2' align='center' valign='middle'>
         	<a href='javascript://' onclick=\"document.location.href='bancos.edicao.php?id=".$row['id']."';\">         	
         	<img src='../img/icon_edit.gif' width='16' height='16' border='0'>Editar Dados</a></td>          
          <td width='17%' rowspan='2' align='center' valign='middle'>";
		
          if($_SESSION['USERNIVEL'] == 'a' && $row['em_uso']=='f'){
         	echo "<a href='javascript://' onclick=\"if (confirm('Deseja realmente excluir o banco? Esta operação não poderá ser desfeita.')){ajax.loadDiv('divManip','bancos.exclusao.php?id=".$row['id']."');}\">
         	<img src='../img/icon_delete.gif' width='16' height='16' border='0'>Excluir Banco</a> ";
          }
          echo "</td></tr>";
          echo "<tr><td height='18' style='font-size:8pt;'><b>Código do banco: </b>{$row['codigo_banco']}
          </td></tr>";
      echo "</table>
	</div>";	
		
	}
}else{
	//Nao encontrou nenhum estagiario com aquela letra
	echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#F5FAFA'>
					<td align='center'><span align='center' style='color:black;'>Nenhum banco cadastrado com a letra <b>{$pagina}</b>.</span></td>
				</tr>
			</table>";
}	
	
echo "<br/><div id='paginacao' align='center'>";
			$letras = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','w','y','z');
			foreach ($letras as $letra) {
				if($letra==$pagina)				
					echo "[<span style='color:black;font-weight:bold;'>" .$letra. "</span>]";									
				else 
					echo "<a href=javascript:// onClick=\"ajax.loadDiv('divManip','bancos.lista.php?pag=".$letra."');\">" . $letra . "</a> ";
				echo "&nbsp;";
				
				
			}
			echo "</div>";	
	
	
	
	?>
