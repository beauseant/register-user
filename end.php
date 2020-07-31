<?php
	session_start();
	session_destroy();
	// Redirect to the login page:
	header('Location: http://develop.tsc.uc3m.es:8080/guacamole');
?>