<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	if(!checklog()) {
		die($frase_log);
	}
	if(isset($_GET['codigo'])) {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $status = htmlspecialchars($_GET['status'], ENT_QUOTES);
        } else {
            $status = utf8_decode($_GET['status']);
        }
        mysqli_query($conn, "UPDATE laboratorios_procedimentos_status SET status = '".$status."' WHERE codigo = ".$_GET['codigo']);
	}
?>
