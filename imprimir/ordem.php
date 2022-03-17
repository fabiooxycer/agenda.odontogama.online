<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
if(!checklog()) {
	echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
	die();
}

$os = new os();
$moeda = new moeda();

$id = $_GET["id"];

$dados = $os->carregar($id);


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<link rel="stylesheet" href="../css/bootstrap.css">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/jquery-ui.css" />

	<style type="text/css">

		#sem tr td{
			border-top: 1px solid transparent;
		}

		.panel-default > .panel-heading {
			background: #f5f5f5 !important;
		}

		#linha {
			height: 10px;
			border-bottom: 1px solid silver;
			margin-bottom: 10px;
		}

	</style>

	<script type="text/javascript">
		window.print();
	</script>



</head>

<body>

	<div class="panel panel-default">
		<div class='panel-body' style="text-align: center;">
			<h4>ORDEM DE SERVIÇO</h4>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Dados Cadastrais</b></div>
		<div class="panel-body">

			<table class="table" id="sem">
				<tr>
					<td>
						<b>Paciente</b><br>
						<?php echo mb_convert_case($_GET['paciente'], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Dentista</b><br>
						<?php echo mb_convert_case($_GET['dentista'], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Data da OS</b><br>
						<?php echo date("d/m/Y"); ?>
					</td>
					<td>
						<b>Forma de pagamento</b><br>
						(&nbsp;&nbsp;) Cheque, (&nbsp;&nbsp;) Á vista, (&nbsp;&nbsp;) Parcelado, (&nbsp;&nbsp;) Cartão
					</td>
				</tr>
			</table>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Observações</b></div>
		<div class="panel-body">
			<?php
			for($i = 0; $i < 5; $i++)
			{
				echo "<div id=\"linha\"></div>";
			}
			?>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Procedimentos</b></div>
		<div class="panel-body">

			<table class="table table-bordered" id="sem">
				<thead>
					<th width="88%">Procedimento</th>
					<th>Valor (R$)</th>
				</thead>
				<tbody>
					<?php

					for($i = 0; $i < 10; $i++)
					{
						echo "
						<tr>
							<td><br></td>
							<td><br></td>
						</tr>
						";
					}
					
					?>
				</tbody>
			</table>

		</div>
	</div>
	
	<?php

	for($i = 0; $i < 6; $i++)
	{
		echo "<br>";	
	}

	?>

	<div class="panel panel-default">
		<div class='panel-body' style="text-align: center;">
			<h4>ODONTOGRAMA</h4>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Dados Cadastrais</b></div>
		<div class="panel-body">

			<table class="table" id="sem">
				<tr>
					<td>
						<b>Paciente</b><br>
						<?php echo mb_convert_case($_GET['paciente'], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Dentista</b><br>
						<?php echo mb_convert_case($_GET['dentista'], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Data do odontograma</b><br>
						<?php echo date("d/m/Y"); ?>
					</td>
				</tr>
			</table>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-body">
			<center>
				<img src="../imagens/odontograma.png" style="width: 100%;">
			</center>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Procedimentos</b></div>
		<div class="panel-body">

			<table class="table table-bordered" id="sem">
				<thead>
					<th width="12%">Dente</th>
					<th>Procedimento</th>
				</thead>
				<tbody>
					<?php

					for($i = 0; $i < 10; $i++)
					{
						echo "
						<tr>
							<td><br></td>
							<td><br></td>
						</tr>
						";
					}
					
					?>
				</tbody>
			</table>

		</div>
	</div>

	<!--
	<div class="panel panel-default">
		<div class="panel-heading"><b>Dados Cadastrais</b></div>
		<div class="panel-body">

			<table class="table" id="sem">
				<tr>
					<td>
						<b>Paciente</b><br>
						MICHAEL DOUGLAS DOS SANTOS
						<?php echo mb_convert_case($dados[0]["nomePaciente"], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Dentista</b><br>
						LEANDRO DA SILVA JUNIOR
						<?php echo mb_convert_case($dados[0]["nomeDentista"], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Data do odontograma</b><br>
						<?php echo date("d/m/Y"); ?>
					</td>
				</tr>
			</table>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-body">

			<table class="table table-bordered" id="sem">
				<thead>
					<th width="12%">Dente</th>
					<th>Procedimento</th>
				</thead>
				<tbody>
					<?php

					for($i = 0; $i < 21; $i++)
					{
						echo "
						<tr>
							<td><br></td>
							<td><br></td>
						</tr>
						";
					}
					
					?>
				</tbody>
			</table>

		</div>
	</div>
	-->