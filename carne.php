<?php

include "lib/config.inc.php";
include "lib/func.inc.php";
include "lib/classes.inc.php";
require_once 'lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
if(!checklog()) {
	echo 'Atenção, sua sessão no sistema foi finalizada. realize novamente o login.';
	die();
}

$os = new OS();
$moeda = new moeda();
$dados = new dados();

$detalhes = $os->getParcelasDetalhes($_GET["os"]);

$clinica = new TClinica();
$dadosClinica = $clinica->LoadInfo();
$barras = new barras();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>Comprovante de comissão</title>

	<link rel="stylesheet" href="css/bootstrap.css">
	
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/jquery-ui.css" />

	<script type="text/javascript" src="js/jquery.js"></script>

	<script type="text/javascript">
		
	window.print();

	</script>

	<style type="text/css">
		
	.recortar{
		width: 100%;
		border-bottom: 1px dashed #000;
		margin-bottom: 15px;
	}

	.table tr td{
		padding: 7px !important;
	}

	</style>

</head>

<body>

	<?php

	for($i=0; $i < count($detalhes); $i++)
	{

		$nDocumento = explode(" ", $detalhes[$i]["descricao"]);

		$codigo = $detalhes[$i]["codigo"];

		for($a=0; strlen($codigo)-12; $a++)
		{
			$codigo = "0".$codigo;
		}

	?>
	
	<table class="table table-bordered" style="font-size: 8pt !important; margin-bottom: 15px;">

		<tbody>
			<tr>
				<td>
					Parcela<br>
					<b><?php echo $nDocumento[1]; ?></b>
				</td>
				<td>
					Vencimento<br>
					<b><?php echo date("d/m/Y", strtotime($detalhes[$i]["datavencimento"])); ?></b>
				</td>
				<td colspan="4">
					Local de pagamento<br>
					<b>REALIZAR O PAGAMENTO APENAS NO ESTABELECIMENTO EMISSOR</b>
				</td>
				<td>
					Vencimento<br>
					<b><?php echo date("d/m/Y", strtotime($detalhes[$i]["datavencimento"])); ?></b>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="width: 160px;">
					(=) Valor do documento<br>
					<b>R$ <?php echo $moeda->formatar($detalhes[$i]["valor"]); ?></b>
				</td>
				<td colspan="4">
					Cedente<br>
					<b><?php echo mb_convert_case($dadosClinica["fantasia"], MB_CASE_UPPER, "UTF-8"); ?></b>
				</td>
				<td>
					(=) Valor do documento<br>
					<b>R$ <?php echo $moeda->formatar($detalhes[$i]["valor"]); ?></b>
				</td>
			<tr>
				<td colspan="2">
					(-) Descontos
				</td>
				<td>
					Data do Doc.
				</td>
				<td>
					Número do Doc.
				</td>
				<td>
					Espécie do Doc.
				</td>
				<td>
					Data de Proces.
				</td>
				<td>
					(-) Descontos
				</td>
			</tr>

			<tr>
				<td colspan="2">
					(-) Outras deduções
				</td>

				<td colspan="4" rowspan="4">
				OBRIGADO PELA PREFERÊNCIA!<br>
				DETALHES: <?php echo $detalhes[$i]["descricao"]; ?>
				</td>
				<td colspan="2">
					(-) Outras deduções
				</td>
			</tr>

			<tr>
				<td colspan="2">
					(+) Mora / Multa
				</td>
				
				<td colspan="2">
					(+) Mora / Multa
				</td>
			</tr>
			<tr>
				<td colspan="2">
					(+) Outros Acréscimos
				</td>
				
				<td colspan="2">
					(+) Outros Acréscimos
				</td>
			</tr>
			<tr>
				<td colspan="2">
					(=) Valor Cobrado
				</td>
				
				<td colspan="2">
					(=) Valor Cobrado
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Número do Documento<br>
					<b>1-<?php echo $nDocumento[1]; ?></b>
				</td>
				<td colspan="4">
				SACADO <b><?php echo mb_convert_case($detalhes[$i]["nome"], MB_CASE_UPPER, "UTF-8"); ?></b><br>
				CPF <b><?php echo $dados->formatarCPF($detalhes[$i]["cpf"]); ?></b>
				</td>
				<td colspan="2">
					<?php 

					echo "<center>";
					$barras->geraCodigoBarra($codigo);
					echo "<br><b>".$codigo."</b></center>";

					?>
				</td>
			</tr>
		</tbody>

	</table>

	<div class="recortar"></div>

	<?php
	}
	?>

</body>
</html>