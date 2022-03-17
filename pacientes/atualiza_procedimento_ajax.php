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
            $procedimento = htmlspecialchars($_GET['procedimento'], ENT_QUOTES);
        } else {
            $procedimento = utf8_decode($_GET['procedimento']);
        }
        mysqli_query($conn, "UPDATE laboratorios_procedimentos SET procedimento = '".$procedimento."' WHERE codigo = ".$_GET['codigo']);
	}
?>
