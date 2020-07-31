
<!DOCTYPE html>
<html>
	<link href="vendor/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="assets/app.css" rel="stylesheet" id="app-css">
	<!------ Include the above in your HEAD tag ---------->

	<head>
  		<title>login window</title>
	</head>

	<body>

			<?php
				if ($_GET["error"] == 1) {
					echo '
						<div class="alert alert-danger" role="alert">
  							Imposible conectar con el LDAP..
						</div>
						';

				}
				if ($_GET["error"] == 2 or $_GET["error"] == 3 ) {

					echo '
						<div class="alert alert-danger" role="alert">
  							Usuario y/o contraseña no válida.
						</div>
						';
				}


				$infoData = require('./info_config.php');


			?>

			<div class="container login-container">
			            <div class="row">                
			                <div class="col-md-6 login-form-0">
			                    <div class="login-logo">
			                        <img src="img/P7270061.jpg" alt=""/>
			                    </div>
			                    <h3>Bienvenido</h3>
			                    	<form action="authentication.php" method="post">
					                        <div class="form-group">
					                            <input name="username" type="text" class="form-control" placeholder="Usuario *" value="" />
					                        </div>
					                        <div class="form-group">
					                            <input  name="password"  type="password" class="form-control" placeholder="Contraseña *" value="" />
					                        </div>
					                        <div class="form-group">
					                            <input type="submit" class="btnSubmit" value="entrar" />
					                        </div>                       
					                </form>
					                <p style="position:absolute;bottom:0;right: 10px;">
					                		<a style="color:white;text-decoration: underline;" href="#myModal" data-toggle="modal" data-target="#myModal">información
					                		</a>
					                </p>
			                </div>
			            </div>

						<!-- Modal -->
						  <div class="modal fade" id="myModal" role="dialog">
						    <div class="modal-dialog">
						    
						      <!-- Modal content-->
						      <div class="modal-content">
						      	<div class="modal-header">
          								<p class="modal-title"><?php echo $infoData['titulo'];?></p>
        						</div>
						        <div class="modal-body">

									<div class="container">
									  <div class="row">
									    <div class="col-8">
									      <p><?php echo $infoData['texto1'];?></p><br>
									      <p><?php echo $infoData['texto2'];?></p>
									    </div>
									    <div class="col-2">
									    	<div class="modal-logo">
											</div>
									    </div>

									  </div>
									</div>
						        </div>
						        <div class="modal-footer">
						          <button type="button"  class="btn btn-link" data-dismiss="modal">cerrar</button>
						        </div>
						      </div>						      
						    </div>
						  </div>





			</div>


	</body>
</html>
	<script src="vendor/js/jquery.min.js"></script>
	<script src="vendor/js/bootstrap.min.js"></script>
