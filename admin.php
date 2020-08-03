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

  $hosts = $db->getHostsQR ();


  if ($hosts == -1) {
      echo '
            <div class="alert alert-danger" role="info">
                Error cargando hosts
            </div>
            ';
      exit();
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



  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">Seleccione un rango de fechas:</h1>
      </div>
    </div>
  </div>


  <!-- Page Content -->
  <div class="container">

    
          <div class="container">
            <div class="row">
              <div class="col-sm">
                  <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:50%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                  </div>

                  <form id="selDates" class="form-inline" action="./admin.php">
                  </form>
              </div>
          </div>
        </div>


          <?php

              echo ('MOSTRANDO DATOS DE' . htmlspecialchars($_COOKIE["startDate"]) . ' A '. htmlspecialchars($_COOKIE["endDate"]))
          ?>


  </div>





  <!-- Bootstrap core JavaScript -->
  <!-- Menus de seleccion chulos:  https://developer.snapappointments.com/bootstrap-select/examples/ -->
  <script src="vendor/js/jquery.min.js"></script>
  <script src="vendor/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="vendor/js/bootstrap.min.js"></script>
  <script src="vendor/js/jquery.dataTables.min.js"></script>
  <script src="vendor/js/bootstrap-select.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


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
                     'Today': [moment(), moment()],
                     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  }
              }, cb);

              cb(start, end);

          });

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('DD-MM-YYYY');
            var endDate = picker.startDate.format('DD-MM-YYYY');

            console.log(picker.startDate.format('DD-MM-YYYY'));
            console.log(picker.endDate.format('DD-MM-YYYY'));
            
          document.cookie = "startDate=" + startDate;
          document.cookie = "endDate=" + endDate;

            $('form#selDates').submit();

        });


        $(document).ready(function() {

          let startDate = moment($('#reportrange').data('daterangepicker').startDate).format('DD-MM-YYYY');
          let endDate = moment($('#reportrange').data('daterangepicker').endDate).format('DD-MM-YYYY');


          document.cookie = "startDate=" + startDate;
          document.cookie = "endDate=" + endDate;

          console.log(startDate);




        } );



  </script>


</body>

</html>

