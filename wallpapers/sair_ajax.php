<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=ISO-8859-1", true);
	/*if(!checklog()) {
        echo "<script>window.location.reload();</script>";
        die();
	}*/
	session_unset();
	session_destroy();

	echo "<script>window.location.reload();</script>";

?>
