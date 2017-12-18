<?php 
include("sessions.php");
if ($_GET["log"]=="logout") session_unset();
allow_root();
?>
<?php include("inc/header.root.php"); ?>

<body>
    <div align="center">
        <table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
            <tr>
                <td colspan="2">

                    <?php 
                        $short = true;
                        include("inc/topo.root.php");
                    ?>

                </td>
            </tr>  
            <tr>
                <td width="20" height="300" align=left valign=top>&nbsp;</td>
                <td width=552 height="300" valign=top style="padding-top:20px; padding-left:20px;">
                    <p>Olá, <?php echo $_SESSION["USUARIO"]; ?></p>                    
                        <?php
                            if($_SESSION["USUARIO"] != 'contrato')
                                echo "<p>Seu último acesso foi em: {$_SESSION['ULTIMO_ACESSO']}.</p>";
                        ?> 
                    <p>Bem vindo ao Sisgest!</p>
                    <p>Utilize o menu acima para navegar pelas funcionalidades do sistema.</p>
                    <p>&nbsp;</p>

                    <?php if ($_GET["erro"]=="grant") echo "<p aling='center'>Você não tem privilégios para acessar essa área!</p>"?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?php include("inc/copyright.php"); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>

