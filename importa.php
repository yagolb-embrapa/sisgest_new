<?php
/*set_time_limit(120);
phpinfo();*/

/***********************************************************************************************
"CÓDIGO";"NOME"
"CÓDIGO";"NOME"
*************************************************************************************************/
 
/*Banco de dados*/
include_once ("functions/functions.database.php");

//============================================================================================
//Configurando o script
//============================================================================================
$nome_arquivo = "bancos.csv";//nome do arquivo a ser aberto (ou caminho do diretorio). Ex: arquivo.txt
$separador_campo = ';';//separador de campos
$qtde_campos = 4;
//============================================================================================

//abre o arquivo para leitura
$arquivo = fopen($nome_arquivo, "r");

//verificando se o arquivo pode ser aberto
if (!$arquivo) {
    echo "Não foi possível abrir o arquivo '".$nome_arquivo."'!";
	exit();
}
$count=0;
while(!feof($arquivo)){
	$registro = fgets($arquivo);//pega linha inteira
	echo $registro."<br>";
	$campos = explode($separador_campo, $registro);//separa os campos e coloca num vetor		
	
	for($i=0;$i<$qtde_campos;$i++){		
		echo "Campo = ".$campos[$i]."<br>";		
	}	
	//insere_paciente($campos);
	if(!empty($campos[0]) && !empty($campos[2])){
		$count++;
		$query_insercao = "INSERT INTO bancos(codigo_banco, banco)VALUES('{$campos[0]}','{$campos[2]}');";
		echo $query_insercao."<br><br>";	
		/*$resultado_insercao = sql_executa($query_insercao);		
		if(!$resultado_insercao)
			echo "ERRO NO REGISTRO ".$campo[2]."<br>";*/
	}
	echo "<br>-------------------------------------------<br>";			
}
echo "TOTAL: ".$count;
?>