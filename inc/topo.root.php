<div align="center"><img src="img/banner3.jpg" width="800" height="90" border="0" >
<div class="meiogeral" style="height:20px; color:#7d6d5e vertical-align:middle;" align="right">
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left" style="color:#7d6d5e"> 
<?php
	
	//inclui a funcao e o menu criado por ela	
	if($_SESSION["USERID"]){
		include_once('functions/menu.functions.php');//inclui funcao que cria menu horizontal superior
		show_menu_root(0,0,0);//chamada da funcao de menu
	}//obs: o menu antigo se encontra junto com o menu novo na pagina incluida
	

?>
</td>
<td align="right" style="color:#000000">
<?php
$mes = date("n");
$mesextenso = array("","Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
$ano = date("Y");
$dia = date("j");
$semana = date("w");
$semanaextenso = array("Domingo","Segunda","Ter&ccedil;a","Quarta","Quinta","Sexta","S&atilde;bado");
echo "$semanaextenso[$semana], $dia de $mesextenso[$mes] de $ano";
?>
</td>
</tr>
</table>

</div>
</div>

<br>