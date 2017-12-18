<?php 

$qtd_abas = 0;
require_once("../inc/header.php");
require_once("../classes/DB.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<!-- TR de CONTEUDO -->  
<tr>
  <td width='752' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo' align='left'>
		<span class='titulo'>.: Visualização de Email</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
<?php
$id_setor = $_GET['id_setor'];

if(empty($id_setor))
	$msg_erro = "O email não foi encontrado.";		
else{		
	$query_emails = "SELECT * FROM emails WHERE id_setor = {$id_setor}";
	$emails = DB::fetch_all($query_emails);

    $query_setor = "SELECT setor FROM setores WHERE id = {$id_setor}";
    $setores = DB::fetch_all($query_setor);

	if(sizeof($emails)==0)
		$msg_erro = "O email não foi encontrado.";
}

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>		
	<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
}else{
    echo "<p><a href='javascript://' onclick=\"document.location.href='emails.edicao.php?id_setor=".$id_setor."';\">
        <img src='../img/icon_edit.gif' width='16' height='16' border='0'>Editar Dados</a></p>";
?>
   <!-- Abas -->	
	<ul class='listaAbas'>
       <li><a id='a1' class='active'>Dados</a></li>      
   </ul>
   </div>
	<div id="aba1" class='conteudoAba' style='display:block;'>		  	 	
  	  	<table width="100%" class='visualizacao'>
  	  	<tr><td colspan='2'><div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div></td></tr>		  
      <tr class='specalt'>
        <td width="33%"><span>Setor</span></td>
        <td width="67%"><span><?php echo (empty($setores[0]['setor']))?" <i>Não preenchido</i> ":$setores[0]['setor']; ?></span></td>        
      </tr>

        <?php
            foreach($emails as $email) {
                echo "
                    <tr class='specalt'>
                    <td width='33%'><span>Email</span></td>";
                $valor = (empty($email['email'])) ? '<i>Não preenchido</i>' : $email['email'];
                echo "
                    <td width='67%'><span>{$valor}</span></td>
                    </tr>";
            }
        ?>
        
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       </table>
       </div>
            
    </table>  
  </div></div>
 </div> 
</div>
<?php
}
echo "
  </td>
</tr>
</table>";
 
include_once('../inc/copyright.php');
?>
</div>
