<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}


require './class/Database.php';

$db = new registerDB ();

$_SESSION['userId'] = $db -> insertUser ($_SESSION['name'], $_SESSION['mail'], $_SESSION['tipo'], $_SESSION['fullName']);


if ( ( isset($_POST['sendForm']))  or ( isset($_SESSION['hostId']) )) {

    //si le damos varias veces el botón de enviar ignoramos las siguientes peticiones,
    if ($_SESSION['Enviado'] ){
      echo '
            <div class="alert alert-danger" role="info">
                Datos ya grabados en esta sesión, no vuelva a enviarlos.
            </div>
            ';
    }else{


          $salir = False;
          reset($_POST);
          while(list($var, $val) = each($_POST)) {
              if ((strpos($var, 'hostLab_') !== false) and ( $val !=='' )) {              
                $host = $val; 
              }
          }

          if (isset($_SESSION['hostId'])) {
            $host = $_SESSION['hostId'];
            $horas = 1;

          }else {
            $host = $_POST['hostLab_'.  (htmlspecialchars($_COOKIE["labVisible"]))];
            $horas = $_POST['horas'];
          }



          $ip_address = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR') ?: getenv('HTTP_X_FORWARDED') ?: getenv('HTTP_FORWARDED_FOR') ?: getenv('HTTP_FORWARDED') ?: getenv('REMOTE_ADDR');




          $result = $db -> saveConexion ($host, $horas, $ip_address, $_SESSION['userId'] );

          if ($result == -1) {
            echo '
                    <div class="alert alert-danger" role="info">
                        Error grabando datos!!
                    </div>
                    ';
            if (isset($_SESSION['hostId'])) {
              echo '
                      <div class="alert alert-danger" role="info">
                          ¿Existe el host?
                      </div>
                      ';                
              }


          }else{
            echo '
                    <div class="alert alert-info" role="info">
                        Datos grabados correctamente
                    </div>
                    ';
            $_SESSION['Enviado'] = True;
          }
        
    }
  }



?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>register user</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/css/bootstrap.min.css" rel="stylesheet">
  
  <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> 

  <link href="vendor/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="assets/app.css" rel="stylesheet" id="app-css">

  <link rel="stylesheet" href="vendor/css/bootstrap-select.min.css">



</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
      <a class="navbar-brand" href="#">Datos del usuario</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">

          <?php
              if ($_SESSION['tipo'] == 'personal') {
                echo '
                      <li class="nav-item">
                        <a class="nav-link" href="qrs.php">ver códigos</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="admin.php">ver listados</a>
                      </li>

                ';

              }
          ?>

          <li class="nav-item">
            <a class="nav-link" href="logout.php">salir</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">Bienvenido <?php echo ($_SESSION['fullName']); ?></h1>
      </div>
    </div>
  </div>




  <?php


  $laboratorios = $db -> getLaboratorios();


  if ($laboratorios == -1 ) {
      echo '
        <div class="alert alert-danger" role="alert">
            No se han encontrado laboratorios en la base de datos, hable con su técnico de guardia más cercano.
        </div>
        ';
        exit();
  }


  $hosts = $db -> getHosts();



  if ($hosts == -1 ) {
      echo '
        <div class="alert alert-danger" role="alert">
            No se han encontrado equipos en la base de datos, hable con su técnico de guardia más cercano.
        </div>
        ';
        exit();
  }





  $labHost = array();


  /*creamos un array asociativo: [labid] = listadoMaquinas, en la posición 1 tenemos el listado de todas las maquinas del laboratorio 1*/
  foreach ($laboratorios as $lab) {

      $labId = $lab['id'];

      $items = array_filter($hosts, function ($host) use($labId ) {
          return $host['id_laboratorio'] == $labId;
      });  
      $labHost[ $lab['id']] =$items;

  }
 
  ?>

 <div class="container">


              <div id="accordion">
                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                      <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Registrar nueva sesión:
                      </button>
                    </h5>
                  </div>

                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                      
                        <form action="home.php" method="post">
                              <div class="form-check form-check-inline">
                                    <div class="form-check form-check-inline">                                             
                                        <select class="selectpicker" data-live-search="true" name="laboratorio" id="laboratorio" title="laboratorio">
                                              <?php
                                                  foreach ($laboratorios as $lab) {
                                                    echo ('<option value="' . $lab['id'] . '" data-tokens="' . $lab['id'] . '">'. $lab['nombre'] . '</option>');
                                                  }                                                  
                                              ?>
                                        </select>

                                        <?php
                                             foreach ($laboratorios as $lab) {
                                                    $primera = True;
                                                    echo ('<div class="listaHost" id="hostLab_'. $lab['id'] .'" style="display:none;"> <select  name="hostLab_'. $lab['id'] .'"  class="selectpicker" data-live-search="true" title="máquina">');
                                                    foreach ($labHost[$lab['id']] as $idhost => $host) {
                                                      if ($primera == True){
                                                        echo ('<option selected value="' . $idhost . '"   data-tokens="' . $idhost . '">'. $host['nombre'] . '</option>');
                                                      }else {
                                                        echo ('<option value="' . $idhost . '"   data-tokens="' . $idhost . '">'. $host['nombre'] . '</option>');
                                                      }
                                                    }
                                                    echo ('</select></div>');
                                                    
                                              }  
                                        ?>    
                                        <label for="inputPassword" class="col-sm-2 col-form-label">duración:</label>    
                                        <input type="number" min="0" data-bind="value:replyNumber"  value="1" name="horas" style="width: 70px;" placeholder="1" class="form-control" id="horas" >

                                        <input id="sendForm" name="sendForm" type="hidden" value="1">

                                        <input style="margin:10px;" type="submit" class="btn btn-primary mb-2" value="enviar" />                                      
                              </div>
                        </form>


                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                      <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Ver sesiones anteriores:
                      </button>
                    </h5>
                  </div>
                  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <table id="listConexiones" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>puesto</th>
                                        <th>dirección ip</th>
                                        <th>fecha</th>
                                        <th>origen</th>
                                        <th>horas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                      $conexiones = $db -> getConexiones ($_SESSION['userId']);
                                      foreach ($conexiones as $con) {
                                          echo ('<tr><td>'. $con['nombre'] .'</td><td>'.$con['ip'] . '</td><td>'. $con['reg_date'] .'</td><td>'.$con['origen'].'</td><td>'.$con['horas'] .'</td></tr>');
                                      } 
                                  ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>puesto</th>
                                        <th>dirección ip</th>
                                        <th>fecha</th>
                                        <th>origen</th>
                                        <th>horas</th>                                      
                                    </tr>
                                </tfoot>
                            </table>
                    </div>
                  </div>
                </div>                
              </div>

  </div>


 

</div>

  <!-- Bootstrap core JavaScript -->
  <!-- Menus de seleccion chulos:  https://developer.snapappointments.com/bootstrap-select/examples/ -->
  <script src="vendor/js/jquery.min.js"></script>
  <script src="vendor/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="vendor/js/bootstrap.min.js"></script>
  <script src="vendor/js/jquery.dataTables.min.js"></script>
  <script src="vendor/js/bootstrap-select.min.js"></script>

  <script>
          $(document).ready(function() {
              $('#listConexiones').DataTable( {
                  "order": [[ 2, "desc" ]]
              } );
              $("#laboratorio").val(1);
              $("#laboratorio").change();
              

          } );


          //Al cambiar la lista de laboratorios cambia la lista de maquinas asociadas al mismo, por lo que ocultamos la anterior y mostramos la actual:
          $('#laboratorio').change(function() {
              // $(this).val() will work here
              //ocultamos todos los listados de maquinas de todos los laboratorios:
              $('.listaHost').css('display','none');

              //mostramos la lista de hosts del laboratorio actual:
              $('#hostLab_'+ $(this).val()).css('display', 'inline');
              document.cookie = "labVisible=" + $(this).val();

          });

          /*$(function() {
              $('#agree').click(function() {
                  if ($(this).is(':checked')) {
                      $('#enviar').removeAttr('disabled');
                  } else {
                      $('#enviar').attr('disabled', 'disabled');
                  }
              });
          });*/
  </script>



</body>

</html>


<?php
  /*

    <div class="row">

      <div class="col-md-6 mb-4">
      
          <div class="list-group-flush">

              <div class="list-group-item">
                <p ><i class="fa fa-cube fa-2x mr-4 mr-4  green white-text rounded" aria-hidden="true"></i><?php echo ($_SESSION['fullName']); ?></p>
              </div>
              <div class="list-group-item">
                <p ><i class="fa fa-user fa-2x mr-4 white-text rounded blue" aria-hidden="true"></i><?php echo ($_SESSION['name']); ?></p>
              </div>
              <div class="list-group-item">
                <p > <i class="fa fa-envelope-open fa-2x mr-4 mr-4  white-text rounded red" aria-hidden="true"></i><?php echo ($_SESSION['mail']); ?></p>
              </div>
              <div class="list-group-item">
                <p ><i class="fa fa-id-card fa-2x mr-4 mr-4  purple white-text rounded" aria-hidden="true"></i><?php echo ($_SESSION['tipo']); ?></p>
              </div>


            </div>
      
      </div>

      */
?>