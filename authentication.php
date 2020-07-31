<?php
	session_start();
	
	require './class/Ldap.php';

	if ( !isset($_POST['username'], $_POST['password']) ) {
		// Could not get the data that should have been sent.
		header('Location: index.php?error=1');
	}




	$ldap = new LdapUC3M ($_POST['username'], $_POST['password'] );

	$authResult = $ldap -> auth();


	if ($authResult == 1) {
		header('Location: index.php?error=1');
	}
	if ($authResult == 2 OR $authResult == 3) {
		header('Location: index.php?error=2');
	}

	if ($authResult == 0) {
		
		$user = $ldap -> getUserData();

		$id = session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		$_SESSION['mail'] = $user -> getMail ();
		$_SESSION['tipo'] = $user -> getTipo ();
		$_SESSION['fullName'] = $user -> getFullName ();
		

		header('Location: home.php');
	}


?>