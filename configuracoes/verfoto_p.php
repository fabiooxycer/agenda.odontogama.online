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

	$sql = "SELECT `logomarca` FROM `dados_clinica`";
	$query = mysqli_query($conn, $sql) or die(mysqli_error());
	$row = mysqli_fetch_array($query);
    header("Content-type: image/jpeg", true);
    echo $row['logomarca'];
?>
