<?php
  // We need to use sessions, so you should always start sessions using the below code.
  session_start();
  // If the user is not logged in redirect to the login page...
  if (!isset($_SESSION['loggedin']) and ($_SESSION['tipo'] == 'personal')) {
  	header('Location: index.php');
  	exit;
  }

	$actual_link =  echo '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

	// $_SERVER['HTTP_HOST']. '/index.php'; 
    


	if (isset($_GET['url'])) {
		$cleanHostId = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['url']);
		$link = $actual_link . '?hostId='. $cleanHostId;
	}else{
		$link = 'Data not send';
	}



	include('./libs/phpqrcode/qrlib.php');

	//echo $link;

	// outputs image directly into browser, as PNG stream
	QRcode::png($link);

?>