<?php 

$qtd_abas = 0;
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../classes/DB.php");

$periodo = explode('/', $_GET['periodo']);
$meses = array('','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
?>


<div align='center'>
    <span class='titulo'>Relação de Estagiários que não entregaram o Relatório de Frequência</span><br>
    <span class='subtitulo'><?php echo $meses[(int)$periodo[0]].'/'.$periodo[1]; ?></span>
</div>
<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
<?php

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>";	
	
}else{
    $query = "
        SELECT  su.nome as sup_nome,
                es.nome as est_nome,
                es.ramal,
                es.email_embrapa
        FROM    estagiarios AS es
                INNER JOIN supervisores su ON es.id_supervisor = su.id
        WHERE   status = 1 AND es.tipo_vinculo = 'e'
                AND es.id NOT IN (
                                    SELECT es.id
                                    FROM   estagiarios AS es
                                    INNER JOIN frequencias fr ON es.id = fr.id_estagiario
                                    WHERE status = 1 AND es.tipo_vinculo = 'e' AND fr.periodo='{$periodo[1]}-{$periodo[0]}-01' AND fr.entregou_relatorio='t'
                                 )
        ORDER BY es.nome, su.nome;";
	$result = sql_executa($query);	
	
	if(sql_num_rows($result)>0){
		echo "<table  width='100%' class='formulario'>
			<tr>
			<th style='text-align:center;' width='300'>
					Estagiário
			</th>
			<th style='text-align:center;' width='350'>
					Supervisor
			</th>
			<th style='text-align:center;' width='30'>
					Ramal
			</th>
			<th style='text-align:center;' width='30'>
					E-mail
			</th>
			<th style='text-align:center;' width='30'>
					Enviar e-mail
			</th>
		</tr>";
		$classe = "spec";
        $qtde = 1;
        $lista_email = '';
        $lista_nome = '';
        $scriptArrNomes = "";
        $scriptArrEmails = "";
        $i =0;
		while ( $campo = sql_fetch_array($result) ){
            if($ultimo_supervisor != $campo['sup_nome']){
                $classe = ($classe == "specalt")?"spec":"specalt";	
                $qtde = 1;
            }
			echo "<tr class='{$classe}'>
			    <td align='left' width='45%'>
					<span >{$campo['est_nome']}</span>
				</td>
				<td align='left' width='45%'>
					<span >{$campo['sup_nome']}</span>
				</td>
			    <td align='center' width='5%'>
					<span >{$campo['ramal']}</span>
				</td>
			    <td align='left' width='5%'>
					<span >{$campo['email_embrapa']}</span>
				</td>
				<td align='center'>
					<input type='checkbox' name='listaconf' value='{$i}'/>
				</td>
			</tr>";
			$i++;
            $ultimo_supervisor = $campo['sup_nome'];
            $lista_email .= $campo['email_embrapa'] . '|';
            $lista_nome .= $campo['est_nome'] . '|';            
            $qtde++;
            if ($scriptArrNomes!="") $scriptArrNomes.= ","; 
            $scriptArrNomes .= "'".str_replace("'", "´", $campo['est_nome'])."'";
            if ($scriptArrEmails!="") $scriptArrEmails.= ","; 
            $scriptArrEmails .= "'".str_replace("'", "´", $campo['email_embrapa'])."'";
            
		}
	}else{
	 	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
				<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>Não foram encontrados estagiários cadastrados no sistema.</span></td>
			</tr>
		</table>";	
    }
    echo "</table><br>
            <div align='right'> Total = <strong>" . sql_num_rows($result) . " estagiários. </strong></div>";
}
 
?>

</table>
<script type="text/javascript">
var arrayNomes = [<?php echo $scriptArrNomes; ?>];
var arrayEmails = [<?php echo $scriptArrEmails ?>];
</script>
<table width='100%' style='border:0px;' cellspacing='0' cellpadding='0'>  
<tr><td colspan="2">&nbsp;</td></tr>
<tr align='center'><td>
<a id='email' href="javascript://">
<img border='0' src='../img/email_icon.jpg'>
 Avisar Estagiários
</a></td><td><a href="javascript://" onclick="confirma('<?= $meses[$periodo[0]]; ?>')">
<img border='0' src='../img/email_icon.jpg'>
 Avisar Estagiários Selecionados
</a></td></tr>
</table>
<script>
    $(document).ready(function() {
        $('a#email').click(function() {
            $.post('email.frequencia.php', {'nomes':'<?= $lista_nome; ?>', 'emails':'<?= $lista_email; ?>', 'mes':'<?= $meses[$periodo[0]]; ?>' }, function(dados, text_status) {
                alert(dados.mensagem);
            }, "json");
        });	
    });
    
    

</script>


