<?php



class registerDB {



	private $conn;

 
	function __construct() { 
		$configs = require('./config.php');

		// Create connection
		$this-> conn = new mysqli($configs['DBHost'], $configs['DBUsername'], $configs['DBPassword'], $configs['DBName']);
		// Check connection
		if ($this-> conn->connect_error) {
		  	die("Connection failed: " . $this -> conn->connect_error);
			#return -1;
		}

		
	}
	
	function __destruct() {

		$this -> conn->close();


	}


	function getLaboratorios () {

		$sql = "select * from laboratorio";
		$result = $this -> conn->query($sql);
		
		if ($result->num_rows > 0) {			
			$salida = $result->fetch_all(MYSQLI_ASSOC);
		}else {
		  $salida = -1;
		}

		return $salida;

	}



	function insertUser ( $login, $mail, $tipo, $fullName ){
		
		$datetime = date_create()->format('Y-m-d H:i:s');
		//si el usuario ya existe actulizamos el login, por ejemplo, para forzar actualizacion del campo las_login (el datetime con el último acceso.)
		$sql = sprintf ("insert into usuario (login, mail, nombre, status) VALUES('%s', '%s', '%s', '%s') 
						ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), session_date ='%s'", $login, $mail, $fullName,$tipo, $datetime);
		
		if ($this -> conn->query($sql) ==TRUE) {
			 $salida = $this -> conn->insert_id;
		}else {
		  $salida = -1;
		}

		return $salida;


	}

	function  getConexiones ($userId){


		$sql = "select reg_date,origen,horas,host.nombre,conexion.origen, ip, laboratorio.nombre from conexion  
					INNER JOIN host ON conexion.
						id_host=host.id 
					INNER JOIN laboratorio ON host.id_laboratorio=laboratorio.id WHERE conexion.id_usuario=".$userId ." ORDER BY reg_date DESC";

		$result = $this -> conn->query($sql);
		
		if ($result->num_rows > 0) {			
			$salida = $result->fetch_all(MYSQLI_ASSOC);
		}else {
		  $salida = -1;
		}

		return $salida;



	}


	function getAllConexionesFecha ($startDate, $endDate) {
		$sql = "SELECT login, mail, usuario.nombre as username, status, session_date, create_date, reg_date,origen,horas,
				host.nombre,conexion.origen, ip, laboratorio.nombre as labname FROM conexion 
					INNER JOIN host ON conexion.
						id_host=host.id 
					INNER JOIN laboratorio ON host.id_laboratorio=laboratorio.id 
					INNER JOIN usuario ON usuario.id=conexion.id_usuario 
					WHERE date(reg_date) BETWEEN '".$startDate ."' AND '" . $endDate . "' ORDER BY reg_date DESC";


		//print $sql;

		$result = $this -> conn->query($sql);
		
		if ($result->num_rows >= 0) {			
			$salida = $result->fetch_all(MYSQLI_ASSOC);
		}else {
		  $salida = -1;
		}

		return $salida;
	}


	function getHostsQR (){

		$sql = 'SELECT host.id, host.nombre, laboratorio.nombre as labnombre FROM host INNER JOIN laboratorio ON host.id_laboratorio = laboratorio.id';

		$result = $this -> conn->query($sql);
		
		if ($result->num_rows > 0) {			
			$salida = $result->fetch_all(MYSQLI_ASSOC);
		}else {
		  $salida = -1;
		}

		return $salida;


	}		

	function saveConexion ($host, $horas, $ip_address, $id_usuario ){

		$sql = sprintf ("insert into conexion (id_host, id_usuario, origen, horas) VALUES(%s, %s, '%s', %s)", $host, $id_usuario, $ip_address, $horas );

		if ($this -> conn->query($sql) ==TRUE) {
			 $salida = 1;
		}else {
		  $salida = -1;
		}

		return $salida;


	}


	function getHosts () {

		$sql = "select * from host order by id_laboratorio ASC";
		$result = $this -> conn->query($sql);
		
		if ($result->num_rows > 0) {			
			$salida = $result->fetch_all(MYSQLI_ASSOC);

			$hosts = array();
			foreach ($salida as $host ) {
				$hosts[$host['id']] = ['nombre' => $host['nombre'],'ip' => $host['ip'], 'mac' => $host['mac'],'id_laboratorio' => $host['id_laboratorio']];
				//$hosts[$host['id']] = 'hola';
			}

			return $hosts;			

		}else {
		  $salida = -1;
		}

		return $salida;

	}


}



/*
class GuacaDB {


	private $conn;

 
	function __construct() { 
		$configs = require('./config.php');

		// Create connection
		$this-> conn = new mysqli($configs['DBHost'], $configs['DBUsername'], $configs['DBPassword'], $configs['DBName']);
		// Check connection
		if ($this-> conn->connect_error) {
		  	#die("Connection failed: " . $this -> conn->connect_error);
			return -1;
		}

		
	}
	
	function __destruct() {

		$this -> conn->close();


	}


	function addUserEntity ($user){

		$sql = "INSERT INTO guacamole_entity (name, type) VALUES ('". $user . "','USER');";
		
		if ($this -> conn->query($sql) ==FALSE) {
			$salida = -1;
		}else{
			$salida = $this -> conn->insert_id;
		}

		return $salida;


	}

	function addUser ($userid, $mypassword, $email, $fullname, $role) {

		$error = 0;

		$last = $userid;
		$date = date('Y-m-d h:i:s', time());

		$sql = 'SET @salt = UNHEX(SHA2(UUID(), 256))';
		$this -> conn->query($sql);
		
		
		

		$sql = "INSERT INTO guacamole_user (entity_id, password_date, password_salt, password_hash)
				 VALUES (" . $last .",'". $date."', @salt, UNHEX(SHA2(CONCAT('". $mypassword ."', HEX(@salt)), 256)))";

		//En caso de clave duplicada es que el usuario quiere cambiar la contraseña, pero aún así actulizamos el resto de campos por si ha cambiado su situación o su email:
		$sql = $sql . " ON DUPLICATE KEY UPDATE password_date='". $date . "', password_salt=@salt, password_hash=UNHEX(SHA2(CONCAT('". $mypassword ."', HEX(@salt)), 256))";


		if ($this -> conn->query($sql) ==TRUE) {
			$sql = "UPDATE guacamole_user SET email_address='" . $email .  "', full_name='" . $fullname. "', organizational_role='" . $role. "' WHERE entity_id =" . $last;

			if ($this -> conn->query($sql) ==TRUE) {
				$error = '';
			}else {
				$error = $this -> conn->error;	
			}

		}else {
			$error = $this -> conn->error;	
		}

		
		return $error;


	}

	function userExists ($user) {

		$sql = "select entity_id from guacamole_entity where name='". $user ."'";
		$result = $this -> conn->query($sql);

		
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$salida = $row['entity_id'];
		}else {
		  $salida = -1;
		}

		return $salida;

	}

}
*/

/*	function addUser ($user, $mypassword) {

		//$salt_hex = strtoupper(hash('sha256', $random_string));
		//$hash_hex = hash('sha256', $password . $salt_hex);
		$sql = 'SET @salt = UNHEX(SHA2(UUID(), 256))';

		$error = 0;
		if ($this -> conn->query($sql) === TRUE) {
		  	
			$sql = "INSERT INTO guacamole_entity (name, type) VALUES ('". $user . "','USER')";
		  	echo $sql;			
		  	print ('.....');
		  	if ($this -> conn->query($sql) === TRUE) {
		  		print ('insertar');
				$last = $this -> conn->insert_id;
		  		$sql = "INSERT INTO guacamole_user (entity_id, password_salt, password_hash) VALUES ('" . $last ."',@salt, UNHEX(SHA2(CONCAT('". $mypassword ."', HEX(@salt)), 256)))";
		  		print $sql;
				
				print ('------');
			}else{
				$error = -1;
		  		print ($this -> conn->connect_error);
			}
		} else {
		  $error = -2;
		  print ($this -> conn->connect_error);
		}

		return $error;

	}

*/

