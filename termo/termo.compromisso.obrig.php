<?php 

$qtd_abas = 0;
require_once("../sessions.php");
if(!$_SESSION["USERID"]){
	echo "<script language='javascript'> window.location.href='../login.php'; </script>";
}

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

$idEstagiario = $_GET["id"];
//$idEstagiario = 87;

if(!empty($idEstagiario)){
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
	}else{
		$flag_erro = 1;	
	}
}else{
	$flag_erro = 1;
}

if($flag_erro == 1){
	echo "Estagiário não encontrado.";
	exit();
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
		<p class='tituloTermo'>TERMO DE COMPROMISSO DE ESTÁGIO OBRIGATÓRIO, QUE ENTRE SI
		CELEBRAM A EMPRESA BRASILEIRA DE PESQUISA AGROPECUÁRIA - EMBRAPA E O ALUNO <?php echo linha($estagiario['nome'],'gra'); ?>,
		COM A INTERVENIÊNCIA DA INSTITUIÇÃO DE ENSINO <?php echo linha($estagiario['instituicao'],'gra'); ?></p>
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
	      Ensino, resolveram celebrar o presente TERMO DE COMPROMISSO DE ESTÁGIO OBRIGATÓRIO, que será 
	      regido pela Lei nº 11.788, de 25.09.2008, e respectivas alterações subseqüentes, bem como pelas 
	      seguintes cláusulas e condições:
	 </div>
	 <div align="left" id="corpo2">	
	<p class="corpoTermo">      
	<b>CLÁUSULA PRIMEIRA – Da Vinculação ao Convênio</b>
	<br><br>
	<span class="paragrafoTermo">Este Termo de Compromisso vincula-se, para todos os efeitos legais, ao Convênio de Concessão de Estágio celebrado 
	em __/__/__, entre a Embrapa e a Instituição de Ensino, registrado no SAIC/Embrapa sob o nº _ _ _ _ _ _ _ _ _ _.</span>
	<br><br>
	<b>CLÁUSULA SEGUNDA – Do Curso ou Programa</b>
	<br><br>
	<?php 
		rodapeTermo();	
		echo "<br>";
		
		//*********************************** QUEBRA DE PAGINA MANUAL ****************************
			
	 	cabecalhoTermo();
 	?>	
	<span class="paragrafoTermo">O Estudante é aluno formalmente matriculado/inscrito e com freqüência regular no Curso/Programa 
	<?php echo linha($estagiario['curso'],'gra'); ?>, iniciado no <?php echo linha(substr($estagiario['inicio_curso'],6,1),'peq')."º"; ?> semestre do ano de 
	<?php echo linha(substr($estagiario['inicio_curso'],0,4),'med'); ?> e com sua conclusão prevista para 
	o  <?php echo linha(substr($estagiario['termino_curso'],6,1),'peq')."º"; ?>  semestre do ano de
	 <?php echo linha(substr($estagiario['termino_curso'],0,4),'med');?>, nos horários de _ _ _ _ a _ _ _ _ _ _ _, tudo de conformidade com a declaração específica da 
	Instituição de Ensino à qual se vincula o citado Curso/Programa, declaração esta que passa a integrar o presente Termo de Compromisso como Anexo I.</span>
	<br><br>
		<b>CLÁUSULA TERCEIRA – Do Objeto</b>
		<br><br>
		<span class="paragrafoTermo">A Embrapa, por este instrumento, concede, ao Estudante, estágio com vistas a complementar sua formação 
		educacional e à sua preparação para o trabalho produtivo, com sua efetiva atuação nas atividades pertinentes
		 à área de <?php echo linha($estagiario['area_atuacao'],'med'); ?>, junto ao Órgão/Departamento/Setor: <?php echo linha($estagiario['projeto_setor'],'med'); ?> de 
		 sua Unidade: <b>Embrapa Informática Agropecuária</b> situada no endereço discriminado no preâmbulo deste 
		 instrumento, em consonância com o "PLANO DE ESTÁGIO" que, rubricado pelas partes e pela Instituição de 
		 Ensino, integra este Termo de Compromisso como Anexo II.</span>
	<br><br>	
	<b>CLÁUSULA QUARTA – Da jornada de atividade</b>
	<br><br>
	<span class="paragrafoTermo">O <b>Estudante</b> obriga-se a cumprir uma jornada de atividade 
	de <?php echo linha(floor($estagiario['carga_horaria']/5),'peq'); ?> (<?php echo linha(extenso(floor($estagiario['carga_horaria']/5)),'med'); ?>) horas diárias 
	e <?php echo linha($estagiario['carga_horaria'],'peq'); ?> (<?php echo linha(extenso($estagiario['carga_horaria']),'gra'); ?>) horas semanais, nos seguintes horários _ _ _ _ _ _.</span>

<b>SUBCLÁUSULA PRIMEIRA:</b> O <b>Estudante</b> em nível de pós-graduação deverá estar vinculado a um projeto de pesquisa ou processo da Unidade da <b>Embrapa</b>, cujo objetivo esteja relacionado ao tema do trabalho de conclusão do curso a ser elaborado.
<br><br>
<b>SUBCLÁUSULA SEGUNDA:</b> A jornada de atividade do <b>Estudante</b> poderá ser flexibilizada pelo empregado supervisor, desde que mantida sua supervisão e a carga horária definida nesta cláusula.
<br><br>
<b>SUBCLÁUSULA TERCEIRA:</b> A critério do empregado supervisor, poderá ser adotado o sistema de compensação de horas, quando compatível com a jornada de atividade definida nesta cláusula.
<br><br>

<b>CLÁUSULA QUINTA – Das Obrigações Especiais</b><br><br>
<span class="paragrafoTermo">Sem prejuízo do disposto nas demais cláusulas deste instrumento, o <b>Estudante</b> obriga-se especialmente ao seguinte:</span>

	<div style="padding: 0px 0px 0px 30px;">
		<table><tr><td valign="top"><b><span class="corpoTermo">a)</span></b></td><td><span class="corpoTermo">atuar com zelo e dedicação na execução de suas atribuições, de forma a evidenciar desempenho satisfatório nas avaliações periódicas a serem realizadas pelo Empregado Supervisor do estágio;</span></td></tr></table>		
	<?php
		echo "<br>"; 
		rodapeTermo();	
		echo "<br>";
		
		//*********************************** QUEBRA DE PAGINA MANUAL ****************************
			
	 	cabecalhoTermo();
 	?>		
		<table><tr><td valign="top"><b><span class="corpoTermo">b)</span></b></td><td><span class="corpoTermo">cumprir fielmente todas as instruções, recomendações de normas relativas ao estágio emanadas da Instituição de Ensino e da Embrapa, em especial as constantes do "Plano de Estágio";</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">c)</span></b></td><td><span class="corpoTermo">manter total reserva em relação a quaisquer dados ou informações a que venha ter acesso em razão de sua atuação no cumprimento do estágio, não repassando-as a terceiros sob qualquer forma ou pretexto, sem prévia autorização formal da Embrapa, independentemente de se tratar ou não de informação reservada, confidencial ou sigilosa;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">d)</span></b></td><td><span class="corpoTermo">preencher e assinar a proposta de seguro de acidentes pessoais referente ao Plano de Seguro de Vida em Grupo da Embrapa no ato da celebração deste instrumento;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">e)</span></b></td><td><span class="corpoTermo">responsabilizar-se por qualquer dano ou prejuízo que venha a causar ao patrimônio da Embrapa por dolo ou culpa;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">f)</span></b></td><td><span class="corpoTermo">manter assiduidade e aproveitamento escolar satisfatórios em relação ao curso/programa de que trata a cláusula segunda durante a vigência do estágio;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">g)</span></b></td><td><span class="corpoTermo">manter conduta compatível com a ética, os bons costumes e a probidade administrativa no desenvolvimento de estágio, evitando a prática de atos que caracterizem falta grave;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">h)</span></b></td><td><span class="corpoTermo">não manter vínculo de emprego com terceiro, enquanto estiver realizando estágio não obrigatório na <b>Embrapa</b>;</span></td></tr></table>		
		<table><tr><td valign="top"><b><span class="corpoTermo">i)</span></b></td><td><span class="corpoTermo">observar a regulamentação interna da <b>Embrapa</b> no exercício de suas atividades, conforme orientação do empregado supervisor. </span></td></tr></table>
	</div>
	</p>	
	<p class="corpoTermo">
	<b>CLÁUSULA SEXTA – Do Acesso às Instalações</b>
	<br><br>
	<span class="paragrafoTermo">O acesso à infra-estrutura e instalações da <b>Embrapa</b>, pelo <b>Estudante</b>, será o estritamente necessário à execução das atividades objeto do estágio, observada a regulamentação interna da Embrapa.</span>
	<br><br>
	<b>CLÁUSULA SÉTIMA – Dos Resultados</b>
	<br><br>
	<span class="paragrafoTermo">A exploração, a qualquer título, dos resultados dos trabalhos realizados pelo <b>Estudante</b>, privilegiáveis ou não, pertencerá automática e exclusivamente à <b>Embrapa</b>, especialmente Direitos da Propriedade Industrial, Direito sobre Cultivares e Direitos Autorais.</span>
	<br><br>
	<b>CLÁUSULA OITAVA – Do Seguro</b>
	<br>
	<span class="paragrafoTermo">A <b>Embrapa</b> obriga-se a contratar e a custear, direta ou indiretamente, seguro de acidentes pessoais em favor do <b>Estudante</b>, que tenham como causa direta o desempenho das atividades decorrentes do estágio.</span>
	<br><br></P>
	
	<?php 
		rodapeTermo();	
		echo "<br>";
		
		//*********************************** QUEBRA DE PAGINA MANUAL ****************************
			
	 	cabecalhoTermo();
 	?>		
	<p class="corpoTermo">
	<b>CLÁUSULA NONA – Do recesso</b>
	<br><br>
	<span class="paragrafoTermo">É assegurado ao <b>Estudante</b>, sempre que o estágio tenha duração igual ou superior a 1 (um) ano, um período de recesso de 30 (trinta) dias, a ser gozado preferencialmente durante suas férias escolares.</span>
	<br><br>
	<b>SUBCLÁUSULA ÚNICA:</b> Os dias de recesso previstos nesta cláusula serão concedidos de maneira proporcional nos casos de o estágio ter duração inferior a 1 (um) ano.
	<br><br>	
	<b>CLÁUSULA DÉCIMA – Do certificado de estágio</b>
	<br><br>
	<span class="paragrafoTermo">Ao término do estágio com aproveitamento, a <b>Embrapa</b> emitirá o correspondente certificado de estágio, do qual constará:</span>
	<br><br>	
	<div style="padding: 0px 0px 0px 30px;">
		<table><tr><td valign="top"><b><span class="corpoTermo">a)</span></b></td><td><span class="corpoTermo">a identificação do <b>Estudante</b> (nome, nacionalidade, RG, CPF e outros);</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">b)</span></b></td><td><span class="corpoTermo">a identificação do curso e da <b>Instituição de Ensino</b> freqüentados pelo <b>Estudante</b>;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">c)</span></b></td><td><span class="corpoTermo">a unidade de lotação;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">d)</span></b></td><td><span class="corpoTermo">o período de realização do estágio e respectiva carga horária;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">e)</span></b></td><td><span class="corpoTermo">as atividades desenvolvidas no estágio, conforme previsto no plano de estágio; e</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">f)</span></b></td><td><span class="corpoTermo">a avaliação quanto ao aproveitamento do <b>Estudante</b>.</span></td></tr></table>
	</div>
	</p><p class="corpoTermo">
	<br>
	<b>SUBCLÁUSULA ÚNICA:</b> A emissão do certificado de estágio ficará condicionada à entrega, pelo Estudante, da seguinte documentação:
	<br>
	<div style="padding: 0px 0px 0px 30px;">
		<table><tr><td valign="top"><b><span class="corpoTermo">a)</span></b></td><td><span class="corpoTermo">nada consta da biblioteca da <b>Embrapa</b>;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">b)</span></b></td><td><span class="corpoTermo">freqüências apuradas;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">c)</span></b></td><td><span class="corpoTermo">formulário de avaliação do <b>Estudante</b> preenchido, assinado e datado pelo empregado supervisor;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">d)</span></b></td><td><span class="corpoTermo">formulário de avaliação do estágio preenchido, assinado e datado pelo estagiário;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">e)</span></b></td><td><span class="corpoTermo">crachá, quando for utilizado;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">f)</span></b></td><td><span class="corpoTermo">relatório do projeto, caso o <b>Estudante</b> esteja vinculado a algum.</span></td></tr></table>
	</div>
	</p></div>

<?php 
		rodapeTermo();	
		echo "<br>";
		
		//*********************************** QUEBRA DE PAGINA MANUAL ****************************
			
	 	cabecalhoTermo();
 	?>

<div align='left' id='corpoy'>
	
	<p class="corpoTermo">
	<b>CLÁUSULA DÉCIMA PRIMEIRA – Da Vigência</b>
	<br><br>
	
	<span class="paragrafoTermo">O estágio terá vigência inicial de <?php echo linha(calcVigencia($estagiario['vigencia_inicio'],$estagiario['vigencia_fim']),'gra'); ?> mês(es), 
	com início em <?php echo linha(formata($estagiario['vigencia_inicio'],'redata'),'gra'); ?> e término em <?php echo linha(formata($estagiario['vigencia_fim'],'redata'),'gra'); ?>, podendo ser prorrogado, no interesse das partes, 
	mediante celebração de Termo Aditivo por iguais períodos, até completar o limite máximo de 2 (dois) anos, 
	observadas as condições legais específicas e as exigências regulamentares da <b>Instituição de Ensino</b>.</span>
	<br><br>
	<b>CLÁUSULA DÉCIMA SEGUNDA – Da Rescisão</b>
	<br><br>
	<span class="paragrafoTermo">A <b>Embrapa</b> poderá rescindir o presente Termo de Compromisso, independentemente de prévia interpelação judicial ou extrajudicial, por descumprimento de qualquer de suas cláusulas ou condições pelo <b>Estudante</b>, respondendo este pelos prejuízos ocasionados, salvo hipótese de caso fortuito ou de força maior.</span>
	<br><br>
	<b>SUBCLÁUSULA ÚNICA:</b> Além do acima exposto, o presente Termo de Compromisso extingui-se automaticamente nas seguintes hipóteses:
	<br></p>
	<div style="padding: 0px 0px 0px 30px;">
		<table><tr><td valign="top"><b><span class="corpoTermo">a)</span></b></td><td><span class="corpoTermo">conduta reprovável do <b>Estudante</b> no ambiente de trabalho;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">b)</span></b></td><td><span class="corpoTermo">conclusão, abandono de curso ou trancamento da matrícula pelo <b>Estudante</b> junto à <b>Instituição de Ensino</b> interveniente;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">c)</span></b></td><td><span class="corpoTermo">quando atingido o prazo limite de 2 (dois) anos;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">d)</span></b></td><td><span class="corpoTermo">ao final do prazo estabelecido no Termo de Compromisso de Estágio, se o mesmo não for prorrogado;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">e)</span></b></td><td><span class="corpoTermo">extinção do convênio com a <b>Instituição de Ensino</b>;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">f)</span></b></td><td><span class="corpoTermo">insuficiência de desempenho do estagiário no cumprimento do plano de estágio;</span></td></tr></table>
		<table><tr><td valign="top"><b><span class="corpoTermo">g)</span></b></td><td><span class="corpoTermo">pela ausência injustificada por 8 (oito) dias consecutivos ou 15 (quinze) dias intercalados no período de 30 (trinta) dias.</span></td></tr></table>
	</div>	
	<p class="corpoTermo">
	<b>CLÁUSULA DÉCIMA TERCEIRA – Da Denúncia</b>
	<br><br>
	<span class="paragrafoTermo">Quaisquer das partes, independentemente de justo motivo e quando bem lhe convier, poderá denunciar o presente Termo de Compromisso, desde que o faça por escrito, mediante aviso prévio de, pelo menos, 05 (cinco) dias úteis.</span>
	<br><br></p>
<?php 
		rodapeTermo();	
		echo "<br>";
		
		//*********************************** QUEBRA DE PAGINA MANUAL ****************************
			
	 	cabecalhoTermo();
 	?>	
	<p class="corpoTermo">
	<b>CLÁUSULA DÉCIMA QUARTA – Do Foro</b>
	<br><br>
	<span class="paragrafoTermo">Para solução de quaisquer controvérsias porventura oriundas da execução deste Convênio, as partícipes elegem o Foro da Justiça Federal, Seção Judiciária de Campinas/SP.</span>
	<br><br>
	<span class="paragrafoTermo">Estando assim justas e acordes, firmam o presente em 03 (três) vias de igual teor e forma, para um só efeito legal, na presença das testemunhas instrumentárias abaixo nomeadas e subscritas.</span>
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
	for($i=0;$i<15;$i++) echo "<br>";
	rodapeTermo();
 ?>
</div>
