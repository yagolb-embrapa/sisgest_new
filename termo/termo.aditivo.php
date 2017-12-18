<?php 

$qtd_abas = 0;
require_once("../sessions.php");
if(!$_SESSION["USERID"]){
	echo "<script language='javascript'> window.location.href='../login.php'; </script>";
}

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

$idEstagiario = $_GET["id"];
//$idEstagiario = 86;

$queryEstagiario = "SELECT es.*, ie.razao_social as instituicao, ie.cnpj as cnpj, ec.estado_civil as estadocivil, 
						  mu.nome as municipio, mumu.nome as municipioie, mumu.uf as ufie FROM estagiarios es 
						  INNER JOIN instituicoes_ensino ie ON ie.id = es.id_instituicao_ensino
						  INNER JOIN estado_civil ec ON es.id_estado_civil = ec.id
						  INNER JOIN municipios mu ON mu.id = es.id_municipio
						  INNER JOIN municipios mumu ON mumu.id = ie.id_municipio
						  WHERE es.id = {$idEstagiario}";
$resultEstagiario = sql_executa($queryEstagiario);
if(sql_num_rows($resultEstagiario)>0){
	$estagiario = sql_fetch_array($resultEstagiario);	
}
		
?>

<html>
<head>
	<title>SisGest - Embrapa Informática Agropecuária</title>
<meta http-equiv="content-type" content="="text/ht; charset=UTF-8" >

	<link href="../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/style.form.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css" />
	<link href="../css/abas.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript" src="../js/masks.js"></script>	
    <script language="javascript" src="../js/TAjax.js"></script>	
</head>
<body>
    <div align="center">
    <table width="800" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >

<!-- TR de CONTEUDO -->  
<tr>
  <td width='800' height="300" valign="top" style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo'>
	<center><img src="../img/embrapa_logo.gif" width="450" height="150"></center>
	<br>
	<div align="left"  style="margin: 0 0 0 250px;">	
		<p class='tituloTermo'>TERMO ADITIVO Nº _ _ _ AO TERMO DE COMPROMISSO DE ESTÁGIO FIRMADO EM 
		<?php echo linha(formata($estagiario['vigencia_inicio'],'redata'),'data'); ?>, QUE ENTRE SI CELEBRAM A EMPRESA
		 BRASILEIRA DE PESQUISA AGROPECUÁRIA – EMBRAPA E O ALUNO <?php echo linha($estagiario['nome'],'gra'); ?>, COM 
		 INTERVENIÊNCIA DA INSTITUIÇÃO DE ENSINO <?php echo linha($estagiario['instituicao'],'gra'); ?></p>
	</div>	
	<div align="left" id="corpo">
	<p class="corpoTermo">
	A <b>Empresa Brasileira de Pesquisa Agropecuária – Embrapa</b>, empresa pública federal, vinculada ao 
	Ministério da Agricultura, Pecuária e Abastecimento, criada por força da Lei nº 5.851, de 07.12.72, com
	 Estatuto Social aprovado pelo Decreto nº 2.291, de 04.08.97, por intermédio de sua Unidade <b>Embrapa Informática
	  Agropecuária</b>, inscrita no CNPJ/MF sob nº 00.348.003/ 0116 -60, sediada em Campinas/SP, endereço: Avenida 
	  André Tosello, 209 – Barão Geraldo, neste ato representada por seu Chefe Geral Kleber Xavier Sampaio de 
	  Souza, doravante designada simplesmente <b>Embrapa</b>, e, de outro lado, o <b>aluno</b> <?php echo linha($estagiario['nome'],'gra'); ?>, 
	  nacionalidade <?php echo linha($estagiario['nacionalidade'],'med'); ?>, estado civil <?php echo linha($estagiario['estadocivil'],'med'); ?>, 
	  data de nascimento <?php echo linha(formata($estagiario['data_nascimento'],'redata'),'data'); ?>, portador do 
	  RG nº <?php echo linha($estagiario['rg'],'med'); ?>, Órgão Expedidor: <?php echo linha($estagiario['orgao_expedidor'],'med'); ?> , 
	  data de expedição: <?php echo linha(formata($estagiario['data_expedicao'],'redata'),'data'); ?>, inscrito no CPF/MF sob o N°
	  <?php echo linha($estagiario['cpf'],'med')?>, residente e domiciliado em (Cidade/Estado) 
	  <?php echo linha($estagiario['municipio'],'gra')?>-<?php echo linha($estagiario['uf'],'peq')?>, endereço <?php echo linha($estagiario['endereco'],'gra')?>, 
	   doravante designado simplesmente <b>Estudante</b>, com a interveniência da <b>Instituição de Ensino</b>
	    <?php echo linha($estagiario['instituicao'],'gra'); ?>, inscrita no CNPJ/MF sob o
	     nº <?php echo linha($estagiario['cnpj'],'med'); ?>, sediada em (Cidade/Estado) 
	     <?php echo linha($estagiario['municipioie'],'gra')?>-<?php echo linha($estagiario['ufie'],'peq')?>,
	     endereço: Cidade Universitária Zeferino Vaz, neste ato representada por seu 
	     (Reitor/Diretor etc.) <?php echo linha($estagiario[''],'gra'); ?>, nome do representante legal
	      <?php echo linha($estagiario[''],'gra'); ?>, doravante designada simplesmente Instituição de 
	      Ensino, resolvem celebrar o presente TERMO ADITIVO nº _ _ _ ao TERMO DE COMPROMISSO DE ESTÁGIO
	      firmado entre as partes em <?php echo linha(formata($estagiario['vigencia_inicio'],'redata'),'data'); ?>, 
	      na forma das seguintes cláusulas e condições:
	 </div>
	 <div align="left" id="corpo2">	
	<p class="corpoTermo">      
	<b>CLÁUSULA PRIMEIRA – Da prorrogação</b>
	<br><br>
	<span class="paragrafoTermo">A vigência do TERMO DE COMPROMISSO DE ESTÁGIO, firmado entre as partes 
	em <?php echo linha(formata($estagiario['vigencia_inicio'],'redata'),'data'); ?>, fica prorrogada por
	 _ _ _ (_ _ _ _ _ _) mês(es), passando a vigorar de ___/___/___ a ___/___/___. </span>
	<br><br></p>

<?php 
		rodapeTermo();	
		echo "<br>";
		
		//*********************************** QUEBRA DE PAGINA MANUAL ****************************
			
	 	cabecalhoTermo();
 	?>			
	<p class="corpoTermo">
	<b>CLÁUSULA SEGUNDA – Da ratificação</b>
	<br><br>
	<span class="paragrafoTermo">Ficam ratificadas as demais cláusulas e condições estipuladas no 
	Termo de Compromisso ora aditado que não foram alteradas por este Termo Aditivo.
	<br><br>
	E, por estarem assim ajustadas, as partes firmam o presente instrumento em 03 (três) vias de igual teor
	 e forma, na presença das duas testemunhas abaixo nomeadas e subscritas.</span>	
	
	<br><br><br>		
	
	<span class="paragrafoTermo">
		<?php $MES = date('m'); $DIA = date('d'); $ANO = date('Y');
		
		echo "Campinas, ".$DIA." de ".mes_extenso($MES)." de ".$ANO.".";?>
	</span>	
	<table border="0" align="center">
	<tr height="100px;" align="center">
		<td align="center">
			<table border="0"  align="center">
				<tr align="center"><td>________________________________</td></tr>
				<tr align="center"><td>Pela Embrapa</td></tr>
			</table>			
		</td>
		<td>
			<table border="0" align="center">
				<tr align="center"><td align="center">________________________________</td></tr>
				<tr align="center"><td align="center">Pela Instituição de Ensino</td></tr>
			</table>
		</td>		
	</tr>
	<tr colspan='2' align="center"><td colspan='2' align="center">
		<table border="0">
			<tr align="center"><td align="center">________________________________</td></tr>
			<tr align="center"><td align="center">Estudante</td></tr>
		</table>
	</td></tr>
	<tr align="center"><td align="center">
		<table border="0" align="center">
				<tr align="center"><td align="center">________________________________</td></tr>
				<tr><td>Nome:</td></tr>
				<tr><td>CPF:</td></tr>
			</table>			
		</td>
		<td>
			<table border="0" align="center">
				<tr align="center"><td align="center">________________________________</td></tr>
				<tr><td>Nome:</td></tr>
				<tr><td>CPF:</td></tr>
			</table>
		</td>
	</tr>	
	</table>	
		
</div>	
	 		      	
</div>
<?php 
	for($i=0;$i<17;$i++) echo "<br>";
	rodapeTermo();
 ?> 
</div>
