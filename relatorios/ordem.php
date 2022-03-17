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

	</style>

	<script type="text/javascript">
		window.print();
	</script>



</head>

<body>

	<div class="panel panel-default">
		<div class='panel-body' style="text-align: center;">
			<h4>Ordem de serviço Nº #<?php echo $id; ?></h4>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Dados Cadastrais</b></div>
		<div class="panel-body">

			<table class="table" id="sem">
				<tr>
					<td>
						<b>Paciente</b><br>
						<?php echo mb_convert_case($dados[0]["nomePaciente"], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Dentista</b><br>
						<?php echo mb_convert_case($dados[0]["nomeDentista"], MB_CASE_UPPER, "UTF-8"); ?>
					</td>
					<td>
						<b>Data da OS</b><br>
						<?php echo date("d/m/Y", strtotime($dados[0]["data"])); ?>
					</td>
					<td>
						<b>Valor total</b><br>
						R$ <?php echo $moeda->formatar($dados[0]["total"]); ?>
					</td>
				</tr>
				<tr>
					<td>
						<b>Status</b><br>
						<?php echo $os->getStatus($dados[0]["status"]); ?>
					</td>
				</tr>
			</table>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><b>Procedimentos</b></div>
		<div class="panel-body">

			<table class="table tabler-bordered" id="sem">
				<thead>
					<th>CÓD</th>
					<th>Procedimento</th>
					<th>Valor</th>
				</thead>
				<tbody>

				<?php

				$quantidade = count($dados[0]["procedimentos"]);

				for($i = 0; $i < $quantidade; $i++)
				{
					echo "<tr>";
					echo "<td>".$dados[0]["procedimentos"][$i]["tag"]."</td>";
					echo "<td>".$dados[0]["procedimentos"][$i]["procedimento"]."</td>";
					echo "<td> R$ ".$moeda->formatar($dados[0]["procedimentos"][$i]["valor"])."</td>";
					echo "</tr>";
				}

				?>

				</tbody>
			</table>

		</div>
	</div>