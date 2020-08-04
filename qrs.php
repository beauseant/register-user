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
            <?php   include 'includes/header.php'; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">
        <ul>
          <?php


              foreach ($hosts as $host) {
                //$code = base64_encode( QRcode::png($link) );
                echo ('<li>'. $host['id'] . ',' .$host['nombre'] . ','. $host['labnombre'] .', <img src="outputqr.php?url='. $host['id'].'" /></li>');
                //echo ('outputqr.php?url='. $host['id']);
              }                                                  
          ?>
        </ul>


  </div>





  <!-- Bootstrap core JavaScript -->
  <!-- Menus de seleccion chulos:  https://developer.snapappointments.com/bootstrap-select/examples/ -->
  <script src="vendor/js/jquery.min.js"></script>
  <script src="vendor/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="vendor/js/bootstrap.min.js"></script>
  <script src="vendor/js/jquery.dataTables.min.js"></script>
  <script src="vendor/js/bootstrap-select.min.js"></script>

</body>

</html>

