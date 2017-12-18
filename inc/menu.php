<div align="left" style="padding: 10px 0 0 0;">
<div id="menu_principal">
  <?php
  
  		$perm = array(1, 1,2,8,4,63,16,63);
		$links = array("myRevision.php", "submissao.php","revisao.php","acompan.php","relatorios.php","ctrlUsr.php","preferences.php","index.php?log=logout");
		$textos = array("Meus trabalhos", "Submiss&atilde;o","Revis&atilde;o","Acompanhamento","Relat&oacute;rios","Gerenciar Usu&aacute;rios","Prefer&ecirc;ncias","Logout");
		$pi = 0;
		
		for ($i = 0; $i < sizeof($textos); $i++){
			if ($_SESSION["PERMISSAO"] & $perm[$i]){
				if (($textos[$i]=="Gerenciar Usu&aacute;rios")&&(!($_SESSION["PERMISSAO"] & 16))) $textos[$i] = "Gerenciar Conta";
				echo "<div class=\"item_menu_principal\"><a href=\"".$links[$i]."\">".$textos[$i]."</a></div><br/>";
			}
			
		}
		if (isset($_SESSION["PERMISSAO"])) echo "<div class=\"item_menu_principal\"<a href=\"http://intranet.cnptia.embrapa.br/content/cp\" target=\"_blank\">Ajuda</a></li>"	
  
  ?>
</div>
  
</div>
