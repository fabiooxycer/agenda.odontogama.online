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
	if(isset($_GET['descricao'])) {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $descricao = $_GET['descricao'];
        } else {
            $descricao = utf8_decode($_GET['descricao']);
        }
        mysqli_query($conn, "REPLACE INTO odontograma (codigo_paciente, dente, descricao) VALUES ('".$_GET['codigo_paciente']."', '".$_GET['dente']."', '".$descricao."')");
	}
?>
