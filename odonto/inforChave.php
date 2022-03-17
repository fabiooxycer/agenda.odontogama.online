<?php

	$licencaCliente = $_GET['licenca'];

	if($licencaCliente == "")
	{
		echo "semLicenca";
		exit;
	}

	$mensalida = 49.99;

	mysql_connect("localhost", "root", "");
	mysql_select_db("clientes");

	$query = mysql_query("SELECT * FROM clientes WHERE chave='$licencaCliente'");

	$licencaServer = mysql_fetch_array($query);

	$total = $licencaServer[meses_atraso]*$mensalida;
	$chave = substr($licencaServer[chave], 14, 23);

	if($licencaServer[meses_atraso] < 2) 
	{
		$mesTotal = $licencaServer[meses_atraso]." mês";
	}else{
		$mesTotal = $licencaServer[meses_atraso]." meses";
	}

	echo '
		<div class="panel-heading">
			<b>Pagamento não confirmado</b>
		</div>
		<div class="panel-body">
			<p><b>Seu acesso ao sistema foi cancelado devido o não pagamento da mensalidade.</b></p>
			<p>Por favor, efetue o pagamento ou entre em contato com o administrador do sistema.</p>
		<ul>
			<li><b>Clínica:</b> '.$licencaServer[clinica].'</li>
			<li><b>Proprietario:</b> '.$licencaServer[proprietario].'</li>
			<li><b>Chave de acesso:</b> XXXX-XXXX-XXXX'.$chave.'</li>
			<li><b>Último vencimento:</b> '.$licencaServer[vencimento].'</li>
			<li><b>Total de meses em atraso:</b> '.$mesTotal.'</li>
			<li><b>Total a pagar:</b> R$ '.$total.'</li>
		</ul>
	';

?>