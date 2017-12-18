<?php include("inc/header.root.php"); ?>

<script language="javascript" src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script language="javascript" src="js/TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<script language="javascript">
	function handleEnter (event,nome) {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode==13){
			if (typeof nome == "undefined") document.getElementById("btnSub").click();
			else document.getElementById(nome).focus();
		}
	}  	  
	
	function verifica(){
		if ((document.getElementById('senha').value=='')||(document.getElementById('login').value=='')){ 
			alert('Login e Senha são campos obrigatórios.'); 
			return false;
		}
		if ((document.getElementById('login').value.search(/\D/)!=-1)&&(document.getElementById('login').value.search(/\W/)!=-1)) { 
			alert("O Login deve conter apenas letras e/ou numeros.");
			return false;
		}
		return true;
	}  
</script>

<div align="center">
<table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td><?php include("inc/topo.root.php"); ?></td></tr>
  
<tr>
  <td height="300" align="center" valign=middle style="padding-top:20px;">
  <div class="divLogin" align="center">
  
    <form name="form1" id="formLogin" method="post" action="doLogin.php">
      <table width="225" height="72" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50" height="25">Login&#160;&#160;</td>
          <td width="175"><input name="login" type="text" maxlength="20" id="login" onKeyPress="handleEnter (event,'senha')"></td>
        </tr>
        <tr>
          <td height="25">Senha&#160;&#160;</td>
          <td><input name="senha" type="password" maxlength="12" id="senha" onKeyPress="handleEnter (event)"></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input type="button" id="btnSub" name="Button" value="ENTRAR" onClick="if (verifica()) document.getElementById('formLogin').submit();"></td>
        </tr>
      </table>
      <!--<a href="#" onClick=" window.open('esqueceu.senha.php', 'Recuperação de Senha', 'height = 150, width = 350, left=400,top=400,screenX=400,screenY=400');">Esqueceu sua senha?</a>--> 
      <?php if ($_GET["login"]){ ?>
      <div align="center" id="errorMsg">
        <?php 	if ($_GET["login"]=="nouser") echo "Usuário inexistente!";
			else if ($_GET["login"]=="nopass") echo "Senha inválida"; 
	?>
      </div>
      <?php } ?>
    </form>
  </div></td>
</tr>
<tr>
<td><?php include("inc/copyright.php"); ?></td>
</tr>
</table>

</div>
<script>
document.getElementById('login').focus();
</script>
</body>
</html>

