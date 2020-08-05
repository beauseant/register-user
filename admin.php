<?php
  // We need to use sessions, so you should always start sessions using the below code.
  session_start();
  // If the user is not logged in redirect to the login page...
  if (!isset($_SESSION['loggedin']) and ($_SESSION['tipo'] == 'personal')) {
  	header('Location: index.php');
  	exit;
  }


  require './class/Database.php';

  $db = new registerDB ();



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
                      <?php   include 'includes/header.php'; ?>
        </ul>
      </div>
    </div>
  </nav>


<div class="container">
            <div class="row">
              <div class="col-lg-12 text-center">
                <h1 class="mt-5"></h1>
              </div>
            </div>
  </div>

  <!-- Page Content -->
  <div class="container">
    
          <div class="container">
            <div class="row">
              <div class="col-4">
                  <span style="padding-top: 30px;">Introduzca el rango de fechas:</span>
              </div>

                <div class="col-8">
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                          <i class="fa fa-calendar"></i>&nbsp;
                          <span></span> <i class="fa fa-caret-down"></i>
                    </div>

                    <form id="selDates" class="form-inline" action="./admin.php"  method="post">
                    </form>
                </div>
          </div>
        </div>
  </div>

  <div class="container">
            <div class="row">
              <div class="col-lg-12 text-center">
                <h3 class="mt-5"><?php echo ('Mostrando datos desde: ' . htmlspecialchars($_COOKIE["startDate"]) . ' a '. htmlspecialchars($_COOKIE["endDate"])) ?></h3>
              </div>
            </div>
  </div>

  <div class="container">

      <table id="listConexiones"  class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>login</th>
                    <th>tipo</th>
                    <th>última conexión</th>
                    <th>origen</th>
                    <th>nombre</th>
                    <th>fecha</th>
                </tr>
            </thead>
            <tbody>
              <?php

                    $conexiones = $db->getAllConexionesFecha (htmlspecialchars($_COOKIE["startDate"]), htmlspecialchars($_COOKIE["endDate"]));


                    if ($conexiones == -1) {
                        echo '
                              <div class="alert alert-danger" role="info">
                                  Error cargando hosts
                              </div>
                              ';
                        exit();
                    }



                  foreach ($conexiones as $con) {
                      $data = sprintf ('%s (%s), cuenta creada en %s. Última conexión:%s', $con['username'], $con['mail'], $con['create_date'], $con['reg_date']);
                      echo ('<tr><td><button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="top" title="' . $data . '">'. $con['login'].'
  
</button></td><td>'.$con['status'] . '</td><td>'. $con['session_date'] .'</td><td>'.$con['origen'].'</td><td>'.$con['nombre'] .'</td><td>'.$con['reg_date'] .'</td>  </tr>');
                  } 
              ?>

            </tbody>
            <tfoot>
                <tr>
                     <th>login</th>
                    <th>tipo</th>
                    <th>última conexión</th>
                    <th>origen</th>
                    <th>nombre</th>
                    <th>fecha</th>                                
                </tr>
            </tfoot>
        </table>

    </div>



  <!-- Bootstrap core JavaScript -->
  <!-- Menus de seleccion chulos:  https://developer.snapappointments.com/bootstrap-select/examples/ -->
  <script src="vendor/js/jquery.min.js"></script>
  <script src="vendor/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="vendor/js/bootstrap.min.js"></script>
  <script src="vendor/js/jquery.dataTables.min.js"></script>
  <script src="vendor/js/bootstrap-select.min.js"></script>


  <script type="text/javascript" src="vendor/js/moment.min.js"></script>
  <script type="text/javascript" src="vendor/js/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="vendor/css/daterangepicker.css" />


  <script>
          $(function() {

              var start = moment().subtract(29, 'days');
              var end = moment();

              function cb(start, end) {
                  $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
              }

              $('#reportrange').daterangepicker({
                  startDate: start,
                  endDate: end,
                  ranges: {
                     'hoy': [moment(), moment()],
                     'ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                     'últimos 7 días': [moment().subtract(6, 'days'), moment()],
                     'últimos 30 días': [moment().subtract(29, 'days'), moment()],
                     'este mes': [moment().startOf('month'), moment().endOf('month')],
                     'mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  }
              }, cb);

              cb(start, end);

          });

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');

            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
            
          document.cookie = "startDate=" + startDate;
          document.cookie = "endDate=" + endDate;

            $('form#selDates').submit();

        });


        $(document).ready(function() {

          let startDate = moment($('#reportrange').data('daterangepicker').startDate).format('YYYY-MM-DD');
          let endDate = moment($('#reportrange').data('daterangepicker').endDate).format('YYYY-MM-DD');


          document.cookie = "startDate=" + startDate;
          document.cookie = "endDate=" + endDate;

          console.log(startDate);

          $('#listConexiones').DataTable( {
                  "order": [[ 2, "desc" ]]
              } );

        } );

        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

  </script>


</body>

</html>

