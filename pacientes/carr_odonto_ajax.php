<?php

	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=UTF-8", true);
	if(!checklog()) {
		die($frase_log);
	}

	$sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	$dente = mysqli_real_escape_string($conn, $_GET['dente']);
	$paciente = mysqli_real_escape_string($conn, $_GET['paciente']);

	$query = mysqli_query($conn, "SELECT * FROM odontograma WHERE idPaciente='$paciente' AND dente='$dente'");

	$qt = "";

	while($resul = mysqli_fetch_array($query))
	{
		$qt = "1";
		echo "
			<tr class='success'>
				<td style='line-height:33px;'>
	                ".utf8_encode($resul[descricao])."
	            </td>
	            <td align='right'>
	            	<button class='btn btn-danger glyphicon glyphicon-trash' onClick=\"apagar('$resul[id]');\"></button>
	            </td>
	        </tr>
		";
	}

	if($qt == "")
	{
		echo "<tr><td class='danger'>Nenhum tratamento para este dente.</td></tr>";
	}

?>