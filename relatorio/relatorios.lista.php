<?php 	
$qtd_abas = 0;
require_once("../inc/header.php");
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

?>  
<tr>
  <td width="752" height="100" align="center" valign="top" style="padding:20px 10px 0 10px;">
  <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
  <div class='divTitulo' align='left'>
		<span class='titulo'>.: Relatórios</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
	</div>  
  <div align="left" id="divPeriodo" >
		<form id="formPeriodo" name="formPeriodo" method="get">
		<table width='750px' border='0' cellpadding='0' cellspacing='0'>			
		  <tr class='spec'>
        <td style="font-size:10pt;" width'10%'>
        		<span><b>&nbsp;&nbsp;&nbsp;&nbsp;Selecione Relatório</b></span>        
        		&nbsp;&nbsp;<select name="relatorio" id="relatorio" onChange="loadOpcao('relatorio');">
				<option value="0" selected="true">Selecione</option>
				<option value="1">Aniversariantes</option>
				<option value="2">Bolsistas por Modalidade</option>				
				<option value="3">Bolsistas por Supervisor</option>				
				<option value="4">Contratos a Vencer</option>				
				<option value="5">Entrega de Frequência</option>
				<option value="15">Estagiários por Horário</option>								
				<option value="6">Estagiários por Modalidade</option>
				<option value="14">Estagiários por Nível</option>				
				<option value="7">Estagiários por Supervisor</option>				
				<option value="8">Estagiários Vigentes</option>				
				<option value="9">Estágios Finalizados</option>	
				<option value="13">Folha de Pagamento de Estagiários</option>			
				<option value="10">Participação no PIEC</option>
				<option value="11">Relação de Crachás</option>
			</select>	
        	</td>        	
       </tr>       
       </table> 		
		</form>
		<table width='750px'>
		<tr class='spec' align='left'>
			<td><div id="divOpcoes"></div></td></tr></table>  
  </div>
  <br>
  <div align="center" id="divManip" style="width: 750px;"></div> 
  
  </td>
<tr><td>
<?php include("../inc/copyright.php"); ?>
</td></tr>
</table>

</div>
<script language="javascript">
	var ajax = new TAjax();

	//Manipula os elementos da pagina de acordo com a opcao de relatorio escolhida
	function loadOpcao(relatorio){
		var rel = document.getElementById(relatorio).value;
		
		switch(rel){
			case '0': alert("Selecione um relatório!"); break;
			case '1':
				ajax.loadDiv('divOpcoes',''); 
				ajax.loadDiv('divManip','aniversariante.lista.php');				
				break;
			case '2':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','bolsistas.por.modalidade.lista.php');				
				break;
			case '3':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','bolsistas.por.supervisor.lista.php');				
				break;
			case '4':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','selecione.periodo.avencer.php');				
				break;
			case '5':
				ajax.loadDiv('divOpcoes',''); 
				ajax.loadDiv('divManip','selecione.mes.ano.php?tipo=freq');
				break;
			case '6':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','relatorio.listar.estagiarios.php');				
				break;
			case '7':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','estagiarios.por.supervisor.lista.php');				
				break;
			case '8':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','estagiarios.vigentes.php');				 
				break;
			case '9':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','selecione.periodo.finalizados.php');				 
				break;
			case '10':
				ajax.loadDiv('divOpcoes',''); 
				ajax.loadDiv('divManip','piec.lista.php');
				break;					
			case '11':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','selecione.origem.periodo.php');
				break;					
			case '12':
				ajax.loadDiv('divOpcoes',''); 
				ajax.loadDiv('divManip','cracha.lista.php');
				break;
			case '13':
                                ajax.loadDiv('divOpcoes','');
                                ajax.loadDiv('divManip','pagamento.estagiarios.lista.php');
                                break;
			case '14':
				ajax.loadDiv('divManip',''); 
				ajax.loadDiv('divOpcoes','relatorio.listar.estagiarios.nivel.php');				
				break;
			case '15':
				ajax.loadDiv('divOpcoes',''); 
				ajax.loadDiv('divManip','horarios.estagiarios.php');				
				break;
		}
	}

    function valida_mes_ano(periodo) {
        mes = 13;
        if(periodo != "") {
            mes = periodo.substring(0,2);
            if(mes > 13)
                return false;
        }
        else
            return false;

        return true;
    }

	//carrega o relatorio de pagamento dos estagiarios
	function loadPeriodo(data_periodo){
		var periodo = document.getElementById(data_periodo).value;

        if(!valida_mes_ano(periodo))
            alert("Data inválida");
        else
            ajax.loadDiv('divManip','entrega.frequencia.lista.php?periodo='+periodo);
	}

	function confirma(mes){

    	var elem = document.getElementsByName('listaconf');
		var sendNomes = "";
		var sendEmails = "";

    	for (var i=0; i<elem.length; i++){
        	if (elem[i].checked){
            	if (sendNomes!=""){
                	sendNomes += "|";
                	sendEmails += "|";
            	}
            	sendNomes += arrayNomes[i];
            	sendEmails += arrayEmails[i];
    		}
	  	}

    	$.post('email.frequencia.php', {'nomes':sendNomes, 'emails':sendEmails, 'mes':mes }, function(dados, text_status) {
            alert(dados.mensagem);
        }, "json");	  	
    	    
    }

	//versao para impressao do relatorio de estagios finalizados
	function imprimeFinalizados(ini, fim){	
		var i = document.getElementById(ini).value;
		var f = document.getElementById(fim).value;		
		window.open('relatorio.finalizados.print.php?i='+i+'&f='+f,'relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');
	}

	
	//carrega o relatorio de estagios finalizados	
	function loadRelatorioFinalizados(ini, fim){
		var i = document.getElementById(ini).value;
		var f = document.getElementById(fim).value;
		//Só mostra icone de impressao e carrega pagina se foi selecionado um periodo		
		if(i!="" && f!=""){			
			ajax.loadDiv('divManip','finalizado.lista.php?i='+i+'&f='+f);
		}else{
			alert("Selecione um período!");		
		}
	}	
	//versao para impressao do relatorio de estagios finalizados
	function imprimeFinalizados(ini, fim){	
		var i = document.getElementById(ini).value;
		var f = document.getElementById(fim).value;		
		window.open('relatorio.finalizados.print.php?i='+i+'&f='+f,'relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');
	}
	
	//carrega o relatorio de estagios a vencer
	function loadRelatorioAVencer(ini, fim){
		var i = document.getElementById(ini).value;
		var f = document.getElementById(fim).value;
		//Só mostra icone de impressao e carrega pagina se foi selecionado um periodo		
		if(i!="" && f!=""){			
			ajax.loadDiv('divManip','avencer.lista.php?i='+i+'&f='+f);
		}else{
			alert("Selecione um período!");		
		}
	}	
	//versao para impressao do relatorio de estagios a vencer
	function imprimeAVencer(ini, fim){	
		var i = document.getElementById(ini).value;
		var f = document.getElementById(fim).value;		
		window.open('relatorio.avencer.print.php?i='+i+'&f='+f,'relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');
	}



</script>
</body>
</html>

