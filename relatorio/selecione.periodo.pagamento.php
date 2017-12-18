<table>
    <tr>
        <td style="font-size:10pt;" width'10%'><span><b>&nbsp;&nbsp;Selecione Mês/Ano</b></span>
                &nbsp;&nbsp;
            <input name="periodo_pagto" id="periodo_pagto" type="text" size="10"  maxlength="7" value="">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <input type="button" onClick="loadPagamentoEstagiarios('periodo_pagto');" value="OK">	
        </td>
    </tr>
</table>  	

<script>

    $(document).ready(function() {
        $('input#periodo_pagto').mask('19/9999');

    });

</script>

