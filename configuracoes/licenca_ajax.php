<?php

	include ("../lib/config.inc.php");
	include ("../odonto/validar.inc.php");

	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	$licenca = mysqli_fetch_array(mysqli_query($conn, "SELECT chave FROM dados_clinica WHERE id='0'"));

	$validar = new validar();

	$validade = $validar->getVencimento($licenca[chave]);

	//$diasRestantes = date("Y-m-d", strtotime($validade))-date("Y-m-d");

	$validade = date("d/m/Y", strtotime($validade));


?>

<div class="panel panel-default">
	<div class="panel-heading"><b><span class="glyphicon glyphicon-lock"></span> Informações da licença</b></div>
	<div class="panel-body">

		<b>Computadores licenciados:</b> <?php echo $validar->getComputadores($licenca[chave]); ?><br><br>
		<b>Validade:</b> <?php echo $validade." ".$dias." ".$diasRestantes;?><br><br>
		<b>Licença: </b> <?php echo $licenca[chave]; ?>

	</div>
</div>