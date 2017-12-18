<?php
//import("classes/Log.php");

class LDAP {
	
    public static function authenticate($user, $password, $host = "ldaps://ldap.cnptia.embrapa.br") {	
	 $conn = @ldap_connect($host);
        
	if(false === $conn)
            die("Problema ao tentar estabelecer conexão com o servidor");

        if(false === @ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3))
            die("Erro ao definir a versão do protocolo LDAPv3");

	$basedn = array("ou=People,dc=cnptia,dc=embrapa,dc=br");
	$dn = LDAP::ObterUsuarioLDAP($host, $basedn, $user);

        if(false === @ldap_bind($conn, $dn, $password))
            return false;

        ldap_unbind($conn);

        return true;

        //original
        //$conn = ldap_connect($host);
			//
        //if(false === $conn) {
            //if(class_exists('Log'))
                //Log::error("Problema na conexão");
            //die("Problema ao tentar estabelecer conexão com o servidor");
        //}
		//
        //if(false === @ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3)) {
            //if(class_exists('Log'))
                //Log::error("Falha ao definir versão do protocolo LDAPv3");
            //die("Erro ao definir a versão do protocolo LDAPv3");
        //}
		//
        //$dn = "uid={$user},ou=People,dc=cnptia,dc=embrapa,dc=br";
//
        //if(false === @ldap_bind($conn, $dn, $password)) {
            //if(class_exists('Log'))
                //Log::access("[Falha] User: {$user} / Password: {$password}");
            //return false;
        //}
		//
        //ldap_unbind($conn);
//
        //if(class_exists('Log'))
            //Log::access("[OK] User: {$user}");
//
        //return true;
    }

    public static function ObterUsuarioLDAP($host, $basedn, $user) {
	$filter="(&(objectClass=inetOrgPerson)(uid=".$user."))";
	$attributes=array("dn");

	$ldapconn = ldap_connect($host,389);
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_bind($ldapconn); // Conecta de forma anonima
	$search = ldap_search(array($ldapconn), $basedn, $filter, $attributes);
	if (count($search) > 0)
	{
		$usr1=ldap_get_entries($ldapconn,$search[0]);
		if ($usr1["count"] == 1)
		{
			$usr2=$usr1[0];
			return $usr2["dn"];
		}
		else
			return false;
	}
	else
		return false;
    }

}

?>
