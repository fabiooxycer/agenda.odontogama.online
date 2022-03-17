<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
	header("Content-type: image/jpeg", true);

    $sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	if(!checklog()) {
		die($frase_log);
	}
	$sql = "SELECT * FROM `pacientes` WHERE `codigo` = '".$_GET['codigo']."'";
	if($_GET['padrao'] == 'no_photo') {
        $sql = "SELECT `foto` FROM `foto_padrao`";
	}
	$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($query);

	echo $row['foto'];
?>
