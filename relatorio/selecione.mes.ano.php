<table>
    <tr>
        <td style="font-size:10pt;" width'10%'><span><b>&nbsp;&nbsp;Selecione Mês/Ano</b></span>
            &nbsp;&nbsp;
            <input name="periodo" id="periodo" type="text" size="10"  maxlength="7" value="">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <?php 
                echo "<input type='button' onClick='loadPeriodo(\"periodo\");' value='OK'>";
            ?>
        </td>
    </tr>
</table>  	

<script>

    $(document).ready(function() {
        $('input#periodo').mask('19/9999');

    });

</script>

