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
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
        $obs = $_GET['obs'];
    } else {
        $obs = utf8_decode($_GET['obs']);
    }
	$sql = "UPDATE agenda_obs SET obs = '".$obs."' WHERE data = '".$_GET['data']."' AND codigo_dentista = '".$_GET['codigo_dentista']."'";
	$query = mysqli_query($conn, $sql) or die('Line 44: '.mysqli_error($conn));
?>
