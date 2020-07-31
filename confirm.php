<?php
			session_start();
			// If the user is not logged in redirect to the login page...
			if (!isset($_SESSION['loggedin'])) {
				header('Location: index.php');
				exit;
			}
			//en caso de forzar con javascript contraseñas diferentes se le saca fuera.
			if ($_POST['up']<>$_POST['up2']){
				header('Location: index.php');
				exit;		
			}
?>


<!DOCTYPE html>
<html>
	<link href="vendor/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> 
	  <link href="assets/app.css" rel="stylesheet" id="app-css">


	<!------ Include the above in your HEAD tag ---------->

	<head>
  		<title>login window</title>
	</head>

	<body>


	  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
	    <div class="container">
	      <a class="navbar-brand" href="#"></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="navbar-toggler-icon"></span>
	      </button>
	      <div class="collapse navbar-collapse" id="navbarResponsive">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item">
	            <a class="nav-link" href="logout.php">Logout</a>
	          </li>
	        </ul>
	      </div>
	    </div>
	  </nav>


		<?php

			require './class/Database.php';

			$db = new GuacaDB ();

			if ($db == -1) {
					print ('
						<div class="alert alert-danger" role="alert">
		  					Error accediendo a la base de datos.
						</div>
						'
					);
					exit();
			}

			$user 		= $_SESSION['name'];
			$password 	= $_POST['up'];
			$email 		= $_SESSION['mail'];
			$rol		= $_SESSION['tipo'];
			$fullname	= $_SESSION['fullName'];


			$userid = $db -> userExists ($user);



			if ( $userid >= 0) {
				print ('
					<div class="alert alert-secondary" role="alert">
	  					El usuario ya existe, sólo se ha cambiado la contraseña.
					</div>
					'					
				);
				
				$resultado = $db -> addUser ($userid , $password, $email, $fullname, $rol);

				if ($resultado <>''){

					print ('
						<div class="alert alert-danger" role="alert">
		  					Error cambiando la contraseña: ' . $resultado . 
						'</div>
						'
					);
				}
			}else {
				$id = $db -> addUserEntity ($user);

				if ($id >=0) {
					$resultado = $db -> addUser ($id , $password , $email, $fullname, $rol);
				}else{
					$resultado = 1;
				}

				if ($resultado <>''){
					print ('
						<div class="alert alert-danger" role="alert">
		  					Error creando el usuario en la base de datos.
						</div>'
						
					);
					exit();
				}else {
					print ('
					<div class="alert alert-secondary" role="alert">
	  					Se ha creado un usuario nuevo.
					</div>
					'
				);

				}
			}
		?>






	  <div class="container">
	    <div class="row">

				<div class="card">
				  <div class="card-header">
				    Hemos terminado
				  </div>
				  <div class="card-body">
				    <h5 class="card-title">Ya se ha creado su cuenta, podrá acceder a las máquinas virtuales desde el siguiente enlace.</h5>
				    <p class="card-text">En caso de que tenga alguna duda puede escribirnos a sisifo@tsc.uc3m.es.</p>
				    <a href="end.php" class="btn btn-primary">Ir</a>
				  </div>
				</div>


	    </div> <!-- /container -->
	    

	</body>
</html>

