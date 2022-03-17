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
    if(isset($_GET['procedimento'])) {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $procedimento = $_GET['procedimento'];
        } else {
            $procedimento = utf8_decode($_GET['procedimento']);
        }
        mysqli_query($conn, "UPDATE honorarios SET procedimento = '".$procedimento."' WHERE codigo = '".$_GET['codigo']."'");
    } elseif(isset($_GET['valor'])) {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $valor = $_GET['valor'];
        } else {
            $valor = utf8_decode($_GET['valor']);
        }
        mysqli_query($conn, "REPLACE INTO honorarios_convenios VALUES (".$_GET['codigo_convenio'].", '".$_GET['codigo']."', ".$valor.")");
    }
?>
