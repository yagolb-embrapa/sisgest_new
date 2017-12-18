<?php 

$qtd_abas = 0;
include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");
require_once("../classes/DB.php");
?>

<script language="javascript" src="../js/TAjax.js"></script>
<div align='center'>
    <span class='titulo'>Relação de Estagiários Vigentes</span><br>
    <span class='subtitulo'><?php echo date("d/m/Y"); ?></span>
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
    $query = "SELECT es.nome AS est_nome,
                     es.data_nascimento,
                     es.email_embrapa,
                     es.ramal,
                     su.nome AS sup_nome,
                     es.nome_projeto,
                     es.carga_horaria,
                     es.remuneracao,
                     es.vigencia_inicio,
                     es.vigencia_fim,
                     es.termino_curso,
                     ie.razao_social AS inst_nome,
                     es.taditivo_inicio,
                     es.taditivo_fim,
                     es.id
                     FROM   estagiarios AS es
                     INNER JOIN supervisores su ON es.id_supervisor = su.id
                     INNER JOIN instituicoes_ensino ie ON es.id_instituicao_ensino = ie.id
                     WHERE status = 1 AND es.tipo_vinculo = 'e'
                     ORDER BY es.nome ASC;";
	$result = sql_executa($query);	
	
	if(sql_num_rows($result)>0){
		echo "<table  width='100%' class='formulario'>						
			<tr>
			<th style='text-align:center;' width='2%'>
			</th>
			<th style='text-align:center;' >
					Estagiário
			</th>
			<th style='text-align:center;' >
					Aniversário
			</th>
			<th style='text-align:center;' >
					E-mail
			</th>
			<th style='text-align:center;' >
					Ramal
			</th>
			<th style='text-align:center;' >
					Supervisor
			</th>
			<th style='text-align:center;' >
					Projeto
			</th>
			<th style='text-align:center;' >
					Carga Horária
			</th>
			<th style='text-align:center;' >
					Remuneração
			</th>
			<th style='text-align:center;' >
					Início
			</th>
			<th style='text-align:center;' >
					Vencimento
			</th>
			<th style='text-align:center;' >
					Conclusão
			</th>
			<th style='text-align:center;' >
					Instituição
			</th>
		</tr>";
		$classe = "spec";
        $qtde = 1;
		while ( $campo = sql_fetch_array($result) ){
            if($ultimo_est != $campo['est_nome']){
                $classe = ($classe == "specalt")?"spec":"specalt";	
            }
            $campo['data_nascimento'] = formata($campo['data_nascimento'], 'redata');
            $campo['vigencia_inicio'] = formata($campo['vigencia_inicio'], 'redata');
            $campo['vigencia_fim'] = formata($campo['vigencia_fim'], 'redata');
            $campo['taditivo_inicio'] =  formata($campo['taditivo_inicio'],'redata');
            $campo['taditivo_fim'] =  formata($campo['taditivo_fim'],'redata');
            $campo['termino_curso'] = explode('-',$campo['termino_curso']);
            $campo['conclusao'] = $campo['termino_curso'][1] . '/' . $campo['termino_curso'][0];
            $supervisor = explode(' ', $campo['sup_nome']);
            $tas = null;
            $sqlTas = "select * from termos_aditivos where id_estagiario = {$campo['id']} order by data_inicio";
            $resTas = sql_executa($sqlTas);
            if (sql_num_rows($resTas)>0){
            	while ($rowTas = sql_fetch_array($resTas))
            		$tas[] = $rowTas;         	
            }
            
            
            
			echo "<tr class='{$classe}'>
			    <td align='center' '5%'>
					<span >{$qtde}</span>
				</td>
				<td align='center' >
					<span >{$campo['est_nome']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['data_nascimento']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['email_embrapa']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['ramal']}</span>
				</td>
			    <td align='center' >
                    <span>{$supervisor[0]} {$supervisor[sizeof($supervisor)-1]}</span>
				</td>
			    <td align='center' >
					<span >{$campo['nome_projeto']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['carga_horaria']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['remuneracao']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['vigencia_inicio']}";
				if ($tas!=null){
					foreach ($tas as $ta){
						echo "<br/><i>".formata($ta['data_inicio'],"redata")."</i>";
					}
				}
				echo "</span>
				</td>
			    <td align='center' >
					<span >{$campo['vigencia_fim']}"; 
				if ($tas!=null){
					foreach ($tas as $ta){
						echo "<br/><i>".formata($ta['data_fim'],"redata")."</i>";
					}
				}
				echo "</span>
				</td>
			    <td align='center' >
					<span >{$campo['conclusao']}</span>
				</td>
			    <td align='center' >
					<span >{$campo['inst_nome']}</span>
				</td>
			</tr>";				
            $ultimo_est = $campo['est_nome'];
            $qtde++;
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

<table width='100%' style='border:0px;' cellspacing='0' cellpadding='0'>  
<tr><td>&nbsp;</td></tr>
<tr align='center'><td>
<a onclick="window.open('estagiarios.vigentes.print.php','relatorio print','width=850,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');" style="color: rgb(0, 0, 255); font-size: 14px;" href="javascript://">
<img border='0' src='../img/icone_impressora.gif'>
 Imprimir
</a></td></tr>
</table>
