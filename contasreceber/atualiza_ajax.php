<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=ISO-8859-1", true);
	
	/*if(!checklog()) {
		die($frase_log);
	}*/

	$conta = new TContas('clinica', 'receber');
	$conta->LoadConta($_GET['codigo']);

	if(isset($_GET['datapagamento']))
	{
		$conta->SetDados('datapagamento', converte_data($_GET[datapagamento], 1));
		$conta->Salvar();
	}


	$sistema = new sistema(); 
	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	$codigo = mysqli_real_escape_string($conn, $_GET['codigo']);
	//$data = mysqli_real_escape_string($conn, $_GET['datapagamento']);

	$infor = array();

	$dados = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM contasreceber WHERE codigo='$codigo'"))or die(mysqli_error($conn));

	$infor = $dados;

	$os = new os();


	if(isset($_GET['datavencimento']))
	{
		$novaData = new DateTime(str_replace("/", "-", $_GET["datavencimento"]));
		$novaData = $novaData->format("Y-m-d");

		mysqli_query($conn, "UPDATE parcelas_ordem SET data='$novaData' WHERE id_ordem='$dados[ordem]' AND data='$dados[datavencimento]'")or die(mysqli_error($conn));
		
		$conta->SetDados('datavencimento', converte_data($_GET[datavencimento], 1));
		$conta->salvar();
		
		exit;
	}

	$forma_pagamento = mysqli_real_escape_string($conn, $_GET["formaPagamento"]);

	if($infor["ordem"] != "" && $infor["comissao"] == "s") $os->calcularComissao($infor["ordem"], "entrada", $infor["valor"]);

	$verificarUltima = mysqli_fetch_row(mysqli_query($conn, "SELECT count(id) FROM parcelas_ordem WHERE id_ordem='$infor[ordem]' and status='0'"));

	if($verificarUltima[0] == 1) mysqli_query($conn, "UPDATE tb_ordens SET status='1' WHERE id='$infor[ordem]'");

	mysqli_query($conn, "UPDATE contasreceber SET status='1', forma_pagamento='$forma_pagamento' WHERE codigo='$codigo'")or die(mysqli_error($conn)); // remove do contas a receber.
	mysqli_query($conn, "UPDATE parcelas_ordem SET status='1' WHERE id_ordem='$infor[ordem]' AND data='$infor[datavencimento]'");
	mysqli_query($conn, "INSERT INTO caixa (data, dc, valor, descricao, modo_pagamento) VALUES ('$infor[datapagamento]', '+', '$infor[valor]', '$infor[descricao]', '$forma_pagamento')")or die(mysqli_error($conn)); // adiciona no fluxo de caixa


?>
