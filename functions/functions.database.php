<?php
 
function sql_executa ( $query )
{
 	$con= pg_connect("host=localhost port=5432 dbname=sisgest user=sisgest password=sisgest");
	if( ! $con ) return 'erro';

	return pg_query ( $con, $query);

} 

function sql_num_rows ( $result )
{
	return pg_num_rows ( $result);
}


function sql_fetch_array ( $result )
{
	$array = pg_fetch_array( $result);
	$retorno = array();
	if( count( $array) > 1 )
	{
		foreach( $array as $chave => $valor)
			$retorno[$chave] = $valor;
				
		return $retorno;
	}
	else
		return $array;
}



?>
