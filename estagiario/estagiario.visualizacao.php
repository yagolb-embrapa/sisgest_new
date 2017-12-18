<?php 

$qtd_abas = 0;
require_once("../inc/header.php");
require_once("../classes/DB.php");

include("../functions/functions.database.php");//temporario 
include("../functions/functions.forms.php");

function dia_extenso($d){
	switch($d){
		case 2:
			return "Segunda-Feira";			
			break;	
		case 3:
			return "Terça-Feira";
			break;
		case 4:
			return "Quarta-Feira";
			break;
		case 5:
			return "Quinta-Feira";
			break;
		case 6:
			return "Sexta-Feira";
			break;
		default:
			return "<i>Indefinido</i>";
	}
}

?>

<script language="javascript" src="../js/TAjax.js"></script>
<!-- TR de CONTEUDO -->  
<tr>
  <td width='752' height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
	<!-- DIV DE ESPAÇAMENTO -->  
   <div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>  
		
	<div class='divTitulo' align='left'>
		<span class='titulo'>.: Visualização de Estagiário</span>
		<div align="center" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;"></div>
		
<?php
$id = $_GET['id'];

if(empty($id))
	$msg_erro = "O estagiário não foi encontrado.";		
else{		
	$query_estag = "SELECT * FROM estagiarios WHERE id = {$id}";
	$result_estag = sql_executa($query_estag);	
	if(sql_num_rows($result_estag)==0)
		$msg_erro = "O estagiário não foi encontrado.";			
	else
		$campo = sql_fetch_array($result_estag);
}

//mostra mensagem de erro ou mostra os dados
if($msg_erro){
	echo "<table width='100%' style='border:1px solid black;' bgcolor='' cellspacing='0' cellpadding='5' height='50px'>						
			<tr bgcolor='#FFEFEF'>					<td align='center'><span align='center' style='color:red;'>{$msg_erro}</span></td>
			</tr>
		</table>		
	<div align='center' style='margin: 0 0 25px 0; padding: 2px 2px 2px 2px;'></div>";
}else{
    echo "<p><a href='javascript://' onclick=\"document.location.href='estagiario.edicao.php?id=".$id."';\">
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
        <td width="33%"><span>Nome</span></td>
        <td width="67%"><span><?php echo (empty($campo['nome']))?" <i>Não preenchido</i> ":$campo['nome']; ?></span></td>        
      </tr>           
      <tr class='specalt'>     
        <td ><span>Data de nascimento</span></td>       
        <td><span><?php echo (empty($campo['data_nascimento']))?" <i>Não preenchido</i> ":formata($campo['data_nascimento'],'redata'); ?></span></td>
       </tr>
		<tr class='specalt'>
        <td ><span>Nacionalidade</span></td>
        <td><span><?php echo (empty($campo['nacionalidade']))?" <i>Não preenchido</i> ":$campo['nacionalidade']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Telefone Residencial</span></td>
        <td><span><?php echo (empty($campo['tel_residencial']))?" <i>Não preenchido</i> ":$campo['tel_residencial']; ?></span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Telefone Celular</span></td>
        <td><span><?php echo (empty($campo['tel_celular']))?" <i>Não preenchido</i> ":$campo['tel_celular']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>E-mail Pessoal</span></td>
        <td><span><?php echo (empty($campo['email']))?" <i>Não preenchido</i> ":$campo['email']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>E-mail Embrapa</span></td>
        <td><span><?php echo (empty($campo['email_embrapa']))?" <i>Não preenchido</i> ":$campo['email_embrapa']; ?></span></td>
       </tr>             
       <tr class='specalt'>
        <td ><span>Fumante</span></td>
        <td><span><?php echo ($campo['fumante'] == 't') ? "Sim": "Não"; ?></span></td>
       </tr>             
       <tr class='specalt'>
        <td ><span>RG</span></td>
        <td><span><?php echo (empty($campo['rg']))?" <i>Não preenchido</i> ":$campo['rg']; ?></span></td>
       </tr>               
		 <tr class='specalt'>
        <td ><span>Data de expedição</span></td>
        <td><span><?php echo (empty($campo['data_expedicao']))?" <i>Não preenchido</i> ":formata($campo['data_expedicao'],'redata'); ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Órgão Expedidor</span></td>
        <td><span><?php echo (empty($campo['orgao_expedidor']))?" <i>Não preenchido</i> ":$campo['orgao_expedidor']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>CPF</span></td>
        <td><span><?php echo (empty($campo['cpf']))?" <i>Não preenchido</i> ":$campo['cpf']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Estado Civil</span></td>
        <td><span><?php
        			$q_ec = "SELECT estado_civil FROM estado_civil WHERE id = {$campo['id_estado_civil']}";
        			$r_ec = sql_executa($q_ec);
        			$c_ec = sql_fetch_array($r_ec); 
        			echo (empty($c_ec['estado_civil']))?" <i>Não preenchido</i> ":$c_ec['estado_civil']; ?></span>
			</td>
       </tr>       
       <tr class='specalt'>
        <td><span>Endereço</span></td>
        <td><span><?php echo (empty($campo['endereco']))?" <i>Não preenchido</i> ":$campo['endereco']; ?></span></td>
      </tr>
        <tr class='specalt'>
        <td ><span>Complemento</span></td>
        <td><span><?php echo (empty($campo['complemento']))?" <i>Não preenchido</i> ":$campo['complemento']; ?></span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>CEP</span></td>
        <td><span><?php echo (empty($campo['cep']))?" <i>Não preenchido</i> ":$campo['cep']; ?></span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Bairro</span></td>
        <td><span><?php echo (empty($campo['bairro']))?" <i>Não preenchido</i> ":$campo['bairro']; ?></span></td>
       </tr>       
       <tr  class='specalt'>
        <td><span>UF</span></td>
        <td><span><?php echo (empty($campo['uf']))?" <i>Não preenchido</i> ":$campo['uf']; ?></span></td></tr>
       <tr  class='specalt'>
        <td><span>Município</span></td>
        <td><span><?php 
	   		$q_mun = "SELECT nome FROM municipios WHERE id = {$campo['id_municipio']}";
        		$r_mun = sql_executa($q_mun);
        		$c_mun = sql_fetch_array($r_mun);     
   	      echo (empty($c_mun['nome']))?" <i>Não preenchido</i> ":$c_mun['nome']; ?></span></td>	    
       </tr>

       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>

       <tr>
        <td colspan='2'>
            <table class='horarios'>
                <tbody>
                <tr class='specalt' align='center'><td colspan='5' align='center'><span><b>Beneficiários do Seguro de Vida</b></span></td></tr>

                <tr align="center">
                    <td width="50%"><span><b>Nome</b></span></td>
                    <td width="50%"><span><b>Parentesco</b></span></td>
                </tr>
                <?php
                      //Recuperando beneficiarios
                      $query = "SELECT id, nome as beneficiario_nome, parentesco
                                FROM   beneficiarios
                                WHERE  id_estagiario = {$id}
                                ORDER BY nome DESC;";
                      $benef = DB::fetch_all($query);
                      $i=0;
                      foreach($benef as $b) {
                        $beneficiario[$i] = $b['beneficiario_nome'];
                        $parentesco[$i] = $b['parentesco'];
                        $id_benef[$i] = $b['id'];
                        $i++;
                      }

                      if($beneficiario[0] == ''){
                        echo "                <tr align='center'>\n";
                        echo "                  <td><i>Não preenchido</i></td>\n";
                        echo "                  <td><i>Não preenchido</i></td>\n";
                        echo "                </tr>\n";
                      }
                      else {
                        for($i = 0; $i < 5; $i++) {
                            if($beneficiario[$i] != '') {
                                echo "                <tr align='center'>\n";
                                echo "                  <td>{$beneficiario[$i]}</td>\n";
                                echo "                  <td>{$parentesco[$i]}</td>\n";
                                echo "                </tr>\n";
                            }
                        }
                      }
                ?>
                </tbody>
            </table>
        </td>
       </tr>
       
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>            	
  	  	
       <tr class='specalt'>
        <td width="25%"><span>Nível</span></td>
        <td><span><?php
        			$q_niv = "SELECT nivel FROM niveis WHERE id = {$campo['id_nivel']}";        			
        			$r_niv = sql_executa($q_niv);
        			$c_niv = sql_fetch_array($r_niv); 
        			echo (empty($c_niv['nivel']))?" <i>Não preenchido</i> ":$c_niv['nivel']; ?></span>
			</td>
       </tr>
        <tr class='specalt'>
        <td ><span>Curso</span></td>
        <td><span><?php echo (empty($campo['curso']))?" <i>Não preenchido</i> ":$campo['curso']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Instituição de ensino</span></td>
        <td><span><?php
        			$q_ins = "SELECT razao_social FROM instituicoes_ensino WHERE id = {$campo['id_instituicao_ensino']}";
        			$r_ins = sql_executa($q_ins);
        			$c_ins = sql_fetch_array($r_ins);  
        		echo (empty($c_ins['razao_social']))?" <i>Não preenchido</i> ":$c_ins['razao_social']; ?></span>
			</td>
       </tr>
        <tr>
			<td width="25%"><span>Início do Curso</span></td>
         <td width="75%">
         	<span><?php echo (empty($campo['inicio_curso']))?" <i>Não preenchido</i> ":substr($campo['inicio_curso'],-2)."º semestre de ".substr($campo['inicio_curso'],0,4); ?></span>       	
        	</td>      
      </tr>
        <tr>
			<td width="25%"><span>Término do Curso</span></td>
         <td width="75%"><span><?php echo (empty($campo['termino_curso']))?" <i>Não preenchido</i> ":substr($campo['termino_curso'],-2)."º semestre de ".substr($campo['termino_curso'],0,4); ?></span></td>      
      </tr>
        <tr class='specalt'>
        <td ><span>RA</span></td>
        <td><span><?php echo (empty($campo['ra']))?" <i>Não preenchido</i> ":$campo['ra']; ?></span></td>        
       </tr>
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>      
       <tr class='specalt'>
        <td colspan='2'>
				<table class='horarios'>
					<tr><th colspan='5'>Horários das Aulas</th></tr>
					<?php					
					//Pegando horarios
					$q_horarios = "SELECT * FROM horarios WHERE id_estagiario = {$campo['id']} AND tipo = 'a' ORDER BY dia";					  
					$r_horarios = sql_executa($q_horarios);
																			
					if(sql_num_rows($r_horarios)>0){												
						echo "<tr><td width='15%' align='center'>Dia</td><td width='21%' align='center'>Entrada</td><td width='21%'>Saída</td><td width='21%'>Entrada</td><td width='21%'>Saída</td></tr>";
						$count = 0;																
						while($c_horarios = sql_fetch_array($r_horarios)){
							$dados[$count]['dia'] = $c_horarios['dia'];														
							$dados[$count]['e'] = $c_horarios['entrada'];
							$dados[$count]['s'] = $c_horarios['saida']; 							
							$count++;								
                            $tipo_horario = $c_horarios['tipo'];
						}												
						$count=0;
						for($i=2;$i<7;$i++){						
							echo "<tr align='left'><td>".dia_extenso($i)."</td>";
							if($dados[$count]['dia'] == $i){
                                if($tipo_horario == 'e') {
                                    if(substr($dados[$count]['e'],0,2)<12){
                                        echo "<td>".$dados[$count]['e']."</td>
                                            <td>".$dados[$count]['s']."</td>";
                                        $count++;											
                                    }else{																
                                        echo "<td>-</td><td>-</td>";																		
                                    }								
                                    if($dados[$count]['dia'] == $i){
                                        echo "<td>".$dados[$count]['e']."</td>
                                            <td>".$dados[$count]['s']."</td>";
                                        $count++;										
                                    }else{								
                                        echo "<td>-</td><td>-</td>";
                                    }
                                }
                                else {
                                    if($dados[$count]['dia'] == $i){
                                        echo "<td>".$dados[$count]['e']."</td>
                                            <td>".$dados[$count]['s']."</td>";
                                        $count++;										
                                    }else{								
                                        echo "<td>-</td><td>-</td>";
                                    }
                                    if($dados[$count]['dia'] == $i){
                                        echo "<td>".$dados[$count]['e']."</td>
                                            <td>".$dados[$count]['s']."</td>";
                                        $count++;										
                                    }else{								
                                        echo "<td>-</td><td>-</td>";
                                    }
                                }
							}else{
								echo "<td>-</td><td>-</td><td>-</td><td>-</td>";															
							}																																				
							echo "</tr>";						
						}					
											
					}else{
						echo "<tr><td colspan='5' align='center'>Nenhum horário cadastrado</td></tr>";					
					}					
					?>
					
					</tr>
				</table>        
        </td>                
       </tr>                  
       <tr class='specalt'>
        <td ><span>Observação</span></td>
        <td><span><?php echo (empty($campo['observacao']))?" <i>Não preenchido</i> ":$campo['observacao']; ?></span></td>
       </tr>
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>
       <tr class='specalt'>
        <td ><span>Status do Estágio</span></td>
        <td><span><?php echo ($campo['status']!='0')?"Em andamento":"Finalizado"; ?></span>    			
        </td>        
       </tr>
      <tr class='specalt'>
        <td ><span>Tipo do Estágio</span></td>
        <td><span><?php echo (empty($campo['estagio_obrigatorio']))?" <i>Não preenchido</i> ":(($campo['estagio_obrigatorio']=='S')?"Obrigatório":"Não Obrigatório") ?></span>    			
        </td>        
		<tr class='specalt'>
        <td ><span>Vigência:</span></td>
        <td><span><?php echo (empty($campo['vigencia_inicio']))?" <i>Não preenchido</i> ":formata($campo['vigencia_inicio'],'redata'); ?></span>
        		<span>&nbsp;&nbsp;a&nbsp;&nbsp;</span>
        		<span><?php echo (empty($campo['vigencia_fim']))?" <i>Não preenchido</i> ":formata($campo['vigencia_fim'],'redata'); ?></span>						
        	</td>
       </tr>
       <?php
       		$sqltaditivo = "select * from termos_aditivos where id_estagiario = {$id}";
       		$qrytaditivo = sql_executa($sqltaditivo);
       		if (sql_num_rows($qrytaditivo)>0){
       ?>
       <tr class='specalt'>
       <td><span>Termo(s) aditivo(s):</span></td><td>
       <?php
			while ($rowtaditivo = sql_fetch_array($qrytaditivo)){
		?>
		<div><span><?php echo (empty($rowtaditivo['data_inicio']))?" <i>Não preenchido</i> ":formata($rowtaditivo['data_inicio'],'redata'); ?></span>
        		<span>&nbsp;&nbsp;a&nbsp;&nbsp;</span>
        		<span><?php echo (empty($rowtaditivo['data_fim']))?" <i>Não preenchido</i> ":formata($rowtaditivo['data_fim'],'redata'); ?></span>						
        </div>	
		<?php 	
			}       
       ?>
       </td></tr>
       <?php
       		}
       ?>
       <tr class='specalt'>
        <td ><span>Termo Distrato:</span></td>
        <td><span><?php echo (empty($campo['tdstrato_ini']))?" <i>Não preenchido</i> ":formata($campo['vigencia_inicio'],'redata'); ?></span>        								
        	</td>
       </tr>  
              
       </tr>        
       <tr class='specalt'>
        <td ><span>Área de atuação</span></td>
        <td><span><?php echo (empty($campo['area_atuacao']))?" <i>Não preenchido</i> ":$campo['area_atuacao']; ?></span></td>
       </tr>
		<tr>
			<td width="25%"><span>Carga Horária</span></td>
         <td width="75%">
         	<span><?php echo (empty($campo['carga_horaria']))?" <i>Não preenchido</i> ":$campo['carga_horaria']."h semanais"; ?></span>        		
        	</td>      
      </tr>
      <tr><td width="25%"></td><td width="75%" align="center"><div id="outra" style="display:none;">Carga <input type="text"></input></div></td></tr>
       <tr class='specalt'>
        <td ><span>Remuneração</span></td>
        <td><span>R$ 
        		<?php if(empty($campo['remuneracao'])){
        					echo " <i>Não preenchido</i> ";
        				}else{
        					$rem = str_replace('.',',',$campo['remuneracao']);
        					if(!strpos($rem,',')){
        						$rem .= ",00";	 
        					}
        					echo $rem;
        				}
        		?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Origem dos Recursos</span></td>
        <td><span><?php 
		      	$q_ori = "SELECT origem FROM origens_recursos WHERE id = {$campo['id_origem_recursos']}";
        			$r_ori = sql_executa($q_ori);
        			$c_ori = sql_fetch_array($r_ori);	  
      		   echo (empty($c_ori['origem']))?" <i>Não preenchido</i> ":$c_ori['origem']; ?></span>
			</td>
       </tr>  
       <tr class='specalt'>
        <td ><span>Crachá</span></td>
        <td><span><?php echo (empty($campo['cracha']))?" <i>Não preenchido</i> ":$campo['cracha']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Participou do PIEC?</span></td>
        <td><span><?php echo (empty($campo['participou_piec']))?" <i>Não preenchido</i> ":(($campo['participou_piec']=='S')?"Sim":"Não") ?></span>    			
        </td>        
       </tr>
       <tr class='specalt'>
        <td ><span>Supervisor</span></td>
        <td><span><?php 
        		$q_sup = "SELECT nome FROM supervisores WHERE id = {$campo['id_supervisor']}";
        		$r_sup = sql_executa($q_sup);
        		$c_sup = sql_fetch_array($r_sup);	
        		echo (empty($c_sup['nome']))?" <i>Não preenchido</i> ":$c_sup['nome']; ?></span>
			</td>
       </tr>    
       <tr class='specalt'>
        <td ><span>Ramal</span></td>
        <td><span><?php echo (empty($campo['ramal']))?" <i>Não preenchido</i> ":$campo['ramal']; ?></span></td>
       </tr>
       <tr class='specalt'>
        <td ><span>Número do Projeto</span></td>
        <td><span><?php echo (empty($campo['numero_projeto']))?" <i>Não preenchido</i> ":$campo['numero_projeto']; ?></span></td>
       </tr>        
       <tr class='specalt'>
        <td ><span>Nome do Projeto</span></td>
        <td><span><?php echo (empty($campo['numero_projeto']))?" <i>Não preenchido</i> ":$campo['nome_projeto']; ?></span></td>
       </tr>        
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>      
       <tr class='specalt'>
        <td colspan='2'>
				<table class='horarios'>
					<tr><th colspan='5'>Horários do Estágio</th></tr>
					<?php					
					$q_horarios = "SELECT * FROM horarios WHERE id_estagiario = {$campo['id']} AND tipo = 'e' ORDER BY dia";					  
					$r_horarios = sql_executa($q_horarios);
																			
					if(sql_num_rows($r_horarios)>0){												
						echo "<tr><td width='15%' align='center'>Dia</td><td width='21%' align='center'>Entrada</td><td width='21%'>Saída</td><td width='21%'>Entrada</td><td width='21%'>Saída</td></tr>";
						$count = 0;										
						while($c_horarios = sql_fetch_array($r_horarios)){
							$dados[$count]['dia'] = $c_horarios['dia'];														
							$dados[$count]['e'] = $c_horarios['entrada'];
							$dados[$count]['s'] = $c_horarios['saida']; 							
							$count++;								
						}												
						$count=0;
						for($i=2;$i<7;$i++){						
							echo "<tr align='left'><td>".dia_extenso($i)."</td>";
							if($dados[$count]['dia'] == $i){
								if(substr($dados[$count]['e'],0,2)<12){
									echo "<td>".$dados[$count]['e']."</td>
											<td>".$dados[$count]['s']."</td>";
											$count++;											
								}else{																
									echo "<td>-</td><td>-</td>";																		
								}								
								if($dados[$count]['dia'] == $i){
									echo "<td>".$dados[$count]['e']."</td>
											<td>".$dados[$count]['s']."</td>";
									$count++;										
								}else{								
									echo "<td>-</td><td>-</td>";
								}																		
							}else{
								echo "<td>-</td><td>-</td><td>-</td><td>-</td>";															
							}																																				
							echo "</tr>";						
						}					
											
					}else{
						echo "<tr><td colspan='5' align='center'>Nenhum horário cadastrado</td></tr>";					
					}					
					?>
					
					</tr>
				</table>        
        </td>                
       </tr>                  
       <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
       
       <!--Separador--><tr class='specalt'><td colspan="2"><hr size="1" color="#DFDFDF"></td></tr>
  	  	 	
        <tr class='specalt'>
        <td width="25%"><span>Agência</span></td>
        <td width="75%"><span><?php echo (empty($campo['agencia']))?" <i>Não preenchido</i> ":$campo['agencia']; ?></span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Conta Corrente</span></td>
        <td><span><?php echo (empty($campo['conta_corrente']))?" <i>Não preenchido</i> ":$campo['conta_corrente']; ?></span></td>
       </tr>
        <tr class='specalt'>
        <td ><span>Banco</span></td>
        <td><span><?php 
                    if(!empty($campo['id_banco'])){
                        $q_ban = "SELECT banco FROM bancos WHERE id = {$campo['id_banco']}";
                        $r_ban = sql_executa($q_ban);
                        $c_ban = sql_fetch_array($r_ban);	     
                    }
                    echo (empty($c_ban['banco']))?" <i>Não preenchido</i> ":$c_ban['banco']; ?></span></td>
       </tr>
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
