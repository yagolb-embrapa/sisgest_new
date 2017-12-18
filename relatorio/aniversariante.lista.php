<?php
	$qtd_abas = 0;
	require_once("../functions/functions.database.php");//temporario 
	require_once("../functions/functions.forms.php");

	$cor = true;
			
	if (!$_GET["pag"]) $pagina = '1'; else $pagina = $_GET["pag"];
	$offset = 10; //resultados por pagina.	?>
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}
<?php 
$extenso = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro','Todos');
?>

</style>
<span class='titulo'>Aniversariantes do mês de</span><br>
<span class='subtitulo'><?php echo $extenso[$pagina]; ?></span>
<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
<?php
	$query_niver = "SELECT * FROM estagiarios  WHERE status = 1";
	$result_niver = sql_executa($query_niver);	
	
  	if(sql_num_rows($result_niver)>0){
	  while ( $campo = sql_fetch_array($result_niver) ){
	  		$mes = substr($campo['data_nascimento'],5,2);
	  		$dia = substr($campo['data_nascimento'],-2);	  		
	  		if($mes == $pagina){
				$aniversariantes[] = $campo['nome'];									  		
				$dias[] = $dia;			
	  		}
	  }	  
	  /*$rowLim = sql_fetch_array(sql_executa("SELECT COUNT(*) AS ct FROM estagiarios;"));		
	  if (ceil($rowLim[0]/$offset)==$pagina) $numreg = $rowLim[0];
	  else $numreg = $pagina*$offset;*/
	  	  
	  if(count($dias)>0){	  
	  	 array_multisort($dias, $aniversariantes);//ordena o array de dias e mantem o chaveamento com array 2			  
		 //Imprime uma linha com cada estagiario daquela letra
	  	 for($i=0;$i<count($dias);$i++){
		    $cor = !$cor;
	
		    echo "
  		    <div class='lista_registros";echo ($cor)?"1":"0"; echo "'>	
   	  	 <table width='100%' height='36' border='0' cellpadding='0' cellspacing='0' class='lista_registros_content'>
			<tr align='left'>
          <td width='18%' style='font-size:8pt;' align='left'><strong>".$dias[$i]."</strong></td>
          <td width='82%' style='text-align: left;'>".$aniversariantes[$i]."</td>			 
          </tr>          
           </table>
		     </div>";
		}
	}else{	
		//Nao encontrou nenhum estagiario com aquela letra
		$extenso = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro','Todos');
		echo "<table width='700px' style='border:0px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#F5FAFA'>
					<td align='center'><span align='center' style='color:black;'>";
					if($pagina != 13) echo "Nenhum aniversariante no mês de <b>{$extenso[$pagina]}</b>.</span></td>";
					else echo "Não foram encontrados aniversariantes no sistema.</span></td>";
		echo "</tr>
			</table>";
	}
}	
	
echo "<br/><div id='paginacao' align='center'>";						
			$letras = array('','Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez','Todos');
			for ($m=1;$m<13;$m++) {
				if($m==$pagina)
					echo "[<span style='color:black;font-weight:bold;'>" .$letras[$m]. "</span>]";
				else 
					echo "<a href=javascript:// onClick=\"ajax.loadDiv('divManip','aniversariante.lista.php?pag=".$m."');\">" . $letras[$m] . "</a> ";
				echo "&nbsp;";
			}
			echo "</div>";	
	
	
	
	?>
