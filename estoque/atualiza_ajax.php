<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	$conta = new TEstoque('clinica');
	$conta->LoadConta($_GET[codigo]);
	$conta->SetDados('quantidade', $_GET[estoque]);
	$conta->Salvar();
?>
