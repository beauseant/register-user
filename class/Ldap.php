<?php

class Person {

  private $tipo;
  private $login;
  private $mail;
  private $fullName;

 
  function __construct($tipo, $login, $mail, $fullName, $allData) { 

      $this -> tipo = $tipo;
      $this -> login = $login;
      $this -> mail = $mail;
      $this -> allData = $allData;
      $this -> fullName = $fullName;

  }

  function getMail (){
    return $this -> mail;
  }

  function getTipo () {
    return $this ->tipo;

  }

  function getFullName () {
    return $this ->fullName;

  }

}

class LdapUC3M {
  // Properties
  private $username;
  private $password;
  private $userData;
  private $configs;

  function __construct($username, $password) {
    $this -> username = $username;
    $this -> password = $password;
    
    $this -> configs = require('./config.php');

  }



  function auth () {  


    $username = $this -> username;
    $password = $this -> password;

    

    $ds=ldap_connect($this -> configs['ldapHost']) or die("Could not connect to ldap");

    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);


    if ($ds) {

      $tipo = 'personal';
      //primero miramos si es Personal de la universidad:
      $sr=ldap_search($ds,"ou=Personal,ou=Gente,o=Universidad Carlos III,c=ES", "(&(uid=".$username.")(objectclass=inetOrgPerson))");
      
      if (ldap_errno ( $ds ) == -1 ) {
        return 1;
      }
      $info = ldap_get_entries($ds, $sr);

      // En caso de no encontrarlo como personal, se busca como alumno:
      if ($info['count'] == 0) {
        $sr=ldap_search($ds,"ou=Alumnos,ou=Gente,o=Universidad Carlos III,c=ES", "(&(uid=".$username.")(objectclass=inetOrgPerson))");
        $info = ldap_get_entries($ds, $sr);
        $tipo = 'alumno';
      }

      if ($info['count'] > 0) {

        
        //print("<pre>".print_r($info,true)."</pre>");
        $fullName =  ucwords(strtolower(($info[0]['cn'][0])));
        $this -> userData = new Person ($tipo, $username, $info[0]["mail"][0],$fullName,  $info);


        $result=@ldap_bind($ds,$info[0]["dn"],$password);

        if ($result) {
            /*Usuario validado*/
            return 0;
        }else {
            /*Error en el bind (contrasena no valida)*/
            return 3;
        }

      }else {
          //Ese usuario no existe en el ldap:
          return 2;
      }


    }else{
        //fallo en servidor
        return 1;
    }
  
  }


  function getUserData () {

      return $this -> userData;
  }

}
?>