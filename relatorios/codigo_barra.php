<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

	if(!checklog()) {
		die($frase_log);
	}
    CodigoBarras($_GET['codigo']);
?>
