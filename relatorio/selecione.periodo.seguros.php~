<table><tr>
<td  style="font-size:10pt;" width'10%'><span><b>&nbsp;&nbsp;Selecione Período</b></span>
&nbsp;&nbsp;
	<select name="mes" id="mes">		
		<option value="0" selected="true">Selecione</option>
		<option value="1" >Janeiro</option>
		<option value="2" >Fevereiro</option>
		<option value="3" >Março</option>
		<option value="4" >Abril</option>
		<option value="5" >Maio</option>
		<option value="6" >Junho</option>
		<option value="7" >Julho</option>
		<option value="8" >Agosto</option>
		<option value="9" >Setembro</option>
		<option value="10" >Outubro</option>
		<option value="11" >Novembro</option>
		<option value="12" >Dezembro</option>
	</select>
	<select name="ano" id="ano">
	<?php
		$atual = date("Y");
		//anos de 2009 pra frente 		
		for($i=$atual;$i>2008;$i--){
			echo "<option value='{$i}' selected='true'>{$i}</option>";
		}		
	?>
	</select>		
	<!--		
	<input name="vigenciai" id="vigenciai" type="text" size="10"  maxlength="10" onkeypress="return mdata(this, event);" value="">
	<span>&nbsp;&nbsp;a&nbsp;&nbsp;</span>
	<input name="vigenciaf" id="vigenciaf" type="text" size="10"  maxlength="10" onkeypress="return mdata(this, event);" value="">
	-->
	<input type="button" onClick="loadRelatorioAVencer('vigenciai', 'vigenciaf');" value="Ok">
	
</td>
</tr>
</table>  	
  	