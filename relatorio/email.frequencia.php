<?php

extract($_POST);

$emails = explode('|',$emails);
$nomes = explode('|',$nomes);

$msg_email = utf8_decode("Favor entregar o relatório de frequência referente ao mês de {$mes}.<br><br>
    
    SisGest - Sistema de Gestão de Estagiários");

$erro = false;

for($i = 0; $i < sizeof($emails); $i++){
    if($emails[$i] != '') {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: SisGest - RH <cnptia.sgp@embrapa.br>"."\r\n";
        //$headers .= "From: SisGest - RH <{$emails[$i]}>"."\r\n";
        $headers .= "Reply-To: cnptia.sgp@embrapa.br\r\n";
        $headers .= "Return-Path: cnptia.sgp@embrapa.br\r\n";

        if(mail("{$emails[$i]}","Entregar Relatório de Frequência", $msg_email, $headers)){
            $bgcolor = "#EFFFF4";
            $fontcolor = "#296F3E";
        }
        else
            $JSON['mensagem'] .= "Erro ao enviar email para {$nomes[$i]}\n";
    }
}

if($erro == false)
    $JSON['mensagem'] .= "Um email foi enviado para todos os estagiários que não entregaram o relatório de frequência.";
else
    $JSON['mensagem'] = "Um email foi enviado para todos os estagiários que não entregaram o relatório de frequência.";

echo json_encode($JSON);

?>

