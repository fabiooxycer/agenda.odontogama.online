<?php

	include "lib/config.inc.php";

	$motivo = $_GET['motivo'];

	switch($motivo)
	{
		case "faltaPagamento":
			$nMotivo = "Falta da pagamento";
			mysql_connect($server, $user, $pass) or die(mysql_error());

    		mysql_select_db($bd)or die(mysql_error());

    		$licenca = mysql_query("SELECT chave FROM dados_clinica")or die(mysql_error());

    		$getLicenca = mysql_fetch_row($licenca);

    		$cURL = curl_init("http://odontosystem.ddns.net/odonto/inforChave.php?licenca=".$getLicenca[0]);
    
    		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true );
    
    		$resultado = curl_exec($cURL);
			break;

		case "duplicidade":
			$nMotivo = "Duplicidade de licença";
			break;

		default:
			$nMotivo = "Desconhecido";
			break;
	}

?>

<html>
	<head>
		<title>Inserir Licença</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

		<style type="text/css">
			#centro{
				max-width: 500px;
				margin: 0 auto;
				margin-top: 30px;

			}
		</style>
	</head>

	<body>

		
					<div id="centro">
						<div class="panel panel-primary">
							
							
								<?php
									if($motivo == "faltaPagamento") echo $resultado;
								?>
							</div>
						</div>
					</div>
				

	</body>
</html>