<?php

ob_start();

require_once("sessions.php");
require_once("classes/LDAP.php");
require_once("classes/DB.php");
require_once("classes/Register.php");
require_once("classes/BaseCatracas.php");
require_once("classes/DIMEP.php");
require_once("classes/MADIS.php");
include("functions/functions.database.php");//temporario
DB::connect();


$query = "SELECT * FROM users WHERE login = '{$_POST['login']}'";
$result = sql_executa($query);

if(($_POST['login'] == $_POST['senha']) && ($_POST['login'] == 'contrato') && sql_num_rows($result) > 0){
	session_start();	
	
    $campo = sql_fetch_array($result);	
    $_SESSION['USUARIO'] = $campo['nome'];
    $_SESSION['USERID'] = $campo['id'];
    $_SESSION['USERLOGIN'] = $campo['login'];
	$_SESSION['USERNIVEL'] = $campo['nivel'];

	header("Location: ./index.php?login=contrato");
 	exit();
}
// Login com LDAP. Utiliza o login do email + senha do email da Embrapa
else if(LDAP::authenticate($_POST['login'], $_POST['senha'])) {	
    $query = "SELECT    id,
                        nome,
                        login,
                        ultimo_acesso,
                        nivel
              FROM      users
              WHERE     login='" . addslashes($_POST['login']) . "';";

    //$usuario = Register::filter('usuarios', array('conditions' => array('login' => addslashes($_POST['login']))));
    $usuarios = DB::fetch_all($query);

    setlocale(LC_ALL, 'pt_BR.utf8');

    if(sizeof($usuarios) == 1) {
        $user = $usuarios[0];
        $_SESSION['USUARIO'] = $user['nome'];
        $_SESSION['USERID'] = $user['id'];
        $_SESSION['ULTIMO_ACESSO'] = $user['ultimo_acesso'];
        //$_SESSION['PERMISSOES'] = DB::fetch_all("SELECT * FROM permissoes WHERE id_usuario = " . $usuario->id);
        $_SESSION['USERNIVEL'] = $user['nivel'];

        $user['ultimo_acesso'] = strftime('%d de %B de %Y, às %T');
        $query = "UPDATE    users
                  SET       ultimo_acesso = '{$user['ultimo_acesso']}'
                  WHERE     login = '{$user['login']}';";
        DB::execute($query);

       header("Location: ./index.php");
	exit();
    } else {
	echo "<script>alert('else1');</script>";
        header("Location: ./login.php?login=nopass");
	exit();
    }
} else {
    header("Location: ./login.php?login=nopass");
    exit();
}
 
ob_flush();
    header("Location: ./login.php?login=nopass");
?>
