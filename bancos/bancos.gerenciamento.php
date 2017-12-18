<?php 	
$qtd_abas = 0;
require_once("../inc/header.php");
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

?>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
  <div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
	<div align='left' class='divTitulo'>
		<span class='titulo'>.: Gerenciamento de Bancos</span>
		<div align="center" style="width:700px;margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
	</div> 
  
  <div align="center" id="divManip" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;">
  </div>
  <div align="center" id="divListUsr">
  </div>
  </td>
</tr>
<tr><td>
<?php include("../inc/copyright.php"); ?>
</td></tr>
</table>

</div>
<script language="javascript">
	var ajax = new TAjax();
	ajax.loadDiv('divManip','bancos.lista.php');
</script>
</body>
</html>

