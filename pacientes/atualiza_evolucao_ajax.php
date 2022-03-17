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
            $procexecutado = $_GET['procexecutado'];
            $procprevisto = $_GET['procprevisto'];
            $data = converte_data($_GET['data'], 1);
        } else {
            $procexecutado = utf8_decode($_GET['procexecutado']);
            $procprevisto = utf8_decode($_GET['procprevisto']);
            $data = utf8_decode(converte_data($_GET['data'], 1));
        }
        mysqli_query($conn, "UPDATE evolucao SET procexecutado = '".$procexecutado."', procprevisto = '".$procprevisto."', data = '".$data."' WHERE codigo = ".$_GET['codigo']);
	}
?>
