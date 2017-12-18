<?php
require_once("../classes/DB.php");

$id_relat = (int)$_POST['relatorios_pagamento'];

//Se nao tiver o relatorio, cria no BD
if($id_relat == '') {
    $query = "  INSERT INTO relatorios_pagamento(data)
                VALUES('{$_POST['ano']}-{$_POST['mes']}-01');";
    if(!DB::execute($query)){
        $JSON['status'] = 'erro';
        $JSON['mensagem'] = 'Erro ao inserir novo relatorio no BD';
    }
    else {
        //Caso a inserção seja realizada, recupera o id do relatório adicionado
        $query = "  SELECT  id
                    FROM    relatorios_pagamento 
                    WHERE   data = '{$_POST['ano']}-{$_POST['mes']}-01';";
        $result = DB::fetch_all($query);

        $id_relat = $result[0]['id'];

        //Retorna o id pra alterar no página
        $JSON['status'] = 'novo_relatorio';
        $JSON['id_relat'] = $id_relat;
    }

}

if($JSON['status'] != 'erro') {
    if($JSON['status'] == 'novo_relatorio'){
        for($i = 0; $i < $_POST['total_estag']; $i++) {
            $origem = ($_POST['origem'.$i] != '') ? $_POST['origem'.$i] : 'null';
            $query = "  INSERT INTO estagiario_relatorio_pagamento(id_relatorios_pagamento, id_estagiario, id_origem, observacao, ferias, vale_transporte, remuneracao)
                        VALUES      ({$id_relat},{$_POST['estag'.$i]},$origem,'{$_POST['obs'.$i]}','{$_POST['ferias'.$i]}','{$_POST['vale_transporte'.$i]}','{$_POST['remuneracao'.$i]}')";
            if(!DB::execute($query)){
                $JSON['status'] .= 'erro';
                $JSON['mensagem'] .= "Erro ao inserir estagiário id={$_POST['estag'.$i]}, origem={$_POST['origem'.$i]}, obs='{$_POST['obs'.$i]}'<br>";
            }
        }
    }
    else{
        for($i = 0; $i < $_POST['total_estag']; $i++) {
            $origem = ($_POST['origem'.$i] != '') ? $_POST['origem'.$i] : 'null';
            $query = "  UPDATE      estagiario_relatorio_pagamento
                        SET         id_origem = $origem,
                                    observacao = '{$_POST['obs'.$i]}',
                                    ferias = '{$_POST['ferias'.$i]}',
                                    vale_transporte = '{$_POST['vale_transporte'.$i]}',
                                    remuneracao = '{$_POST['remuneracao'.$i]}'
                        WHERE       id_relatorios_pagamento = {$id_relat} AND id_estagiario = {$_POST['estag'.$i]};";
            if(!DB::execute($query)){
                $JSON['status'] .= 'erro';
                $JSON['mensagem'] .= "Erro ao atualizar estagiário id={$_POST['estag'.$i]}, origem=$origem, obs='{$_POST['obs'.$i]}'<br>";
            }
        }
        $JSON['status'] = 'alteracao';
    }
}

echo json_encode($JSON);

?>

