<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	/*if(!checklog()) {
		die($frase_log);
	}*/

	$conta = new TContas('clinica');
	$conta->LoadConta($_GET[codigo]);
	$conta->SetDados('datapagamento', converte_data($_GET[datapagamento], 1));
	$conta->Salvar();

	$sistema = new sistema(); 
	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	$codigo = mysqli_real_escape_string($conn, $_GET['codigo']);
	//$data = mysqli_real_escape_string($conn, $_GET['datapagamento']);

	$infor = array();

	$dados = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM contaspagar WHERE codigo='$codigo'"));

	$infor = $dados;

	$forma_pagamento = mysqli_real_escape_string($conn, $_GET["formaPagamento"]);

	$data = new DateTime(str_replace("/", "-", $_GET[datapagamento]));
	$datapagamento = $data->format("Y-m-d");
	

	mysqli_query($conn, "UPDATE contaspagar SET status='1', forma_pagamento='$forma_pagamento' WHERE codigo='$codigo'"); // remove do contas a receber.
	mysqli_query($conn, "INSERT INTO caixa (data, dc, valor, descricao, modo_pagamento) VALUES ('$datapagamento', '-', '$infor[valor]', '$infor[descricao]', '$forma_pagamento')"); // adiciona no fluxo de caixa
?>
