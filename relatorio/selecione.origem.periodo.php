<table>
    <tr>
        <td style="font-size:10pt;" width'10%'><span><b>&nbsp;&nbsp;Selecione Origem</b></span>
            &nbsp;&nbsp;
            <select id='select_origem'>
                <option name='todos' value='0'>Todos</option>
                <?php
                    require_once("../classes/DB.php");
                    $query = "  SELECT   id,
                                         origem
                                FROM     origens_recursos
                                ORDER BY origem ASC;";
                    $origens = DB::fetch_all($query);

                    foreach($origens as $origem)
                        echo "<option name='{$origem['origem']}' value='{$origem['id']}'>{$origem['origem']}</option>\n";
                ?>
            </select>

        </td>

        <td style="font-size:10pt;" width'10%'><span><b>&nbsp;&nbsp;Selecione Mês/Ano</b></span>
            &nbsp;&nbsp;
            <input name="periodo" id="periodo" type="text" size="10"  maxlength="7" value="">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <input type='button' id='button_ok' value='OK'>
        </td>
    </tr>
</table>  	

<script>

    $(document).ready(function() {
        $('input#periodo').mask('19/9999');

        $('input#button_ok').click(function() {
            periodo = $('input#periodo').val();
            if(!valida_mes_ano(periodo))
                alert("Data inválida");
            else {
                link = 'cracha.lista.php?periodo='.concat(periodo).concat('&origem=').concat($('#select_origem').val());
				ajax.loadDiv('divManip',link);
                //window.open(link);
            }
        });

    });

</script>

