<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");

	$cor = true;
			
    $pagina = isset($_GET['pag']) ? $_GET['pag'] : 'a';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'estagiario';
	$offset = 10; //resultados por pagina.		
	
    if($tipo == 'estagiario') {
	//selecao de estagiarios	
    $qryStr = "SELECT id,nome,email,email_embrapa,id_supervisor
        FROM estagiarios 
        WHERE LOWER(nome) LIKE LOWER('{$pagina}%')
        AND status = 1
        ORDER BY nome ";
    }
    else {
    $qryStr = "SELECT es.id,es.nome,es.email,es.email_embrapa,es.id_supervisor
        FROM estagiarios es
        LEFT JOIN supervisores su ON es.id_supervisor=su.id
        WHERE LOWER(su.nome) LIKE LOWER('{$pagina}%')
        AND es.status = 1
        ORDER BY su.nome ASC, es.nome ASC ";
    }
	
		
	//botao de inclusao
	echo "<div align='center' style='padding: 0 0 10px 0;'>
		<input type='button' value='Incluir Novo Estagiário' style='padding: 2px 15px 2px 15px; height:24px;' onClick=\"document.location.href='estagiario.inclusao.php';\"></div>";
?>

<script>
    function trocarTipoListagem(pagina, tipo) {

        var site = "estagiario.lista.php?pag=";
        ajax.loadDiv('divManip', site.concat(pagina, '&tipo=', tipo));

    }

</script>

    <!-- Botao para escolher como será a listagem -->
    <div align='left'>Listar por: <br>
    <input name="listagem" id="listagem" type="radio" value="estagiario" onClick="trocarTipoListagem('<?= $pagina?>', 'estagiario')"<?php if($_GET['tipo'] != 'supervisor') echo 'checked';?>>Estagiário
    <input name="listagem" id="listagem2" type="radio" value="supervisor" onClick="trocarTipoListagem('<?= $pagina?>', 'supervisor')" <?php  if($_GET['tipo'] == 'supervisor') echo 'checked';?>>Supervisor<br>
			
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}

</style>
<?php
$rowLim = sql_fetch_array(sql_executa("SELECT COUNT(*) AS ct FROM estagiarios;"));		
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
          	echo (strlen($row['nome'])>45)?substr($row['nome'],0,42)."...":$row['nome'];
		echo "</td>
			 <td width='17%' rowspan='2' align='center' valign='middle'>
				<a href='javascript://' onclick=\"document.location.href='estagiario.visualizacao.php?id=".$row['id']."';\">
				<img src='../img/icone_lupa.png' width='16' height='16' />Visualizar Dados</a> </td>		  						
          <td width='15%' rowspan='2' align='center' valign='middle'>
         	<a href='javascript://' onclick=\"document.location.href='estagiario.edicao.php?id=".$row['id']."';\">         	
         	<img src='../img/icon_edit.gif' width='16' height='16' border='0'>Editar Dados</a></td>          
          <td width='17%' rowspan='2' align='center' valign='middle'>
         	<a href='javascript://' onclick=\"if (confirm('Deseja realmente excluir o estagiário? Esta operação não poderá ser desfeita.')){ajax.loadDiv('divManip','estagiario.exclusao.php?id=".$row['id']."');}\">
         	<img src='../img/icon_delete.gif' width='16' height='16' border='0'>Excluir Estagiário</a> </td>
        </tr>
        <tr>
          <td height='18' style='font-size:8pt;'><strong>Supervisor: </strong>";
 			$q_sup = 'SELECT nome FROM supervisores WHERE id = '.$row['id_supervisor'].'';         
         $r_sup = sql_executa($q_sup);
         $c_sup = sql_fetch_array($r_sup);          
         echo $c_sup['nome']."</td></tr><tr><td height='18' style='font-size:8pt;'><strong>E-mail: </strong>";
         if(empty($row["email_embrapa"])) echo $row["email"]; else echo $row["email_embrapa"];  
         echo "</td>
        </tr>
      </table>
	</div>";	
		
	}
}else{
    $tipo_atual = ($tipo == 'estagiario') ? 'estagiário' : 'supervisor';
	//Nao encontrou nenhum estagiario com aquela letra
	echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#F5FAFA'>
					<td align='center'><span align='center' style='color:black;'>Nenhum {$tipo_atual} cadastrado com a letra <b>{$pagina}</b>.</span></td>
				</tr>
			</table>";
}	
	
echo "<br/><div id='paginacao' align='center'>";
			$letras = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			foreach ($letras as $letra) {
				if($letra==$pagina)
					echo "[<span style='color:black;font-weight:bold;'>" .$letra. "</span>]";									
				else 
					echo "<a href=javascript:// onClick=\"ajax.loadDiv('divManip','estagiario.lista.php?pag=".$letra."&tipo=".$tipo."');\">" . $letra . "</a>";
				echo "&nbsp;&nbsp;";
				
			}
			echo "</div>";	
	
	
	
?>
