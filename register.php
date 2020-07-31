<?php
			session_start();
			// If the user is not logged in redirect to the login page...
			if (!isset($_SESSION['loggedin'])) {
				header('Location: index.php');
				exit;
			}
			//en caso de forzar con javascript el no aceptar las condiciones se le saca fuera:
			if ($_POST['agree']<>'on'){
				header('Location: index.php');
				exit;		
			}
?>


<!DOCTYPE html>
<html>
	<link href="vendor/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> 
	  <link href="assets/app.css" rel="stylesheet" id="app-css">




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


	  <div class="container">
	    <div class="row">
	      <div class="col-lg-12 text-center">
	        <h1 class="mt-5">Introduzca la contraseña:</h1>
	      </div>
	    </div>
	  </div>


	   <div class="container">
			 <div class="row">
			      <div class="col-md-6 mb-4">
			          <div class="list-group-flush">			          
			              <div class="list-group-item">
			                <p ><i class="fa fa-user fa-2x mr-4 white-text rounded blue" aria-hidden="true"></i><?php echo ($_SESSION['name']); ?></p>
			              </div>
					      	<div class="list-group-item">
					      		<form action="confirm.php" class="form-horizontal" method="post" oninput='up2.setCustomValidity(up2.value != up.value ? "Es mejor si las contraseñas son iguales." : "")'>
			                		<p ><i class="fa fa-asterisk fa-2x mr-4 white-text rounded red" aria-hidden="true"></i>
			                			<input type="password" id="password" required name=up class="first" placeholder="Contraseña" autofocus></p>
			                		<p> <i style="margin-left:98px;"></i>
			                			<input type="password" class="first" name=up2 placeholder="Repita contraseña"></p>
			                		<button class="btn btn-lg btn-primary btn-block" type="submit">continuar</button>
			                	</form>
			              	</div>					       

			            </div>
			      
			      </div>
			  </div>






	    </div> <!-- /container -->
	    
	    <!-- get the function -->
  		<script src="vendor/js/jquery.min.js"></script>



	</body>
</html>

