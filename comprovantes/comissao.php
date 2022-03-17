<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
if(!checklog()) {
	echo 'Atenção, sua sessão no sistema foi finalizada. realize novamente o login.';
	die();
}

if(!verifica_nivel('pacientes', 'L')) {
	echo $LANG['general']['you_tried_to_access_a_restricted_area'];
	die();
}

$id = $_GET['id'];

$os = new os();

$dadosComissao = $os->carregarComissao("comissao", $id, "", "");
$dadosOrdem = $os->carregar($dadosComissao[0]["id_ordem"]);

$moeda = new moeda();
$dados = new dados();


$dentistas = new TDentistas();
$pacientes = new TPacientes();

$dentistas->loadDentista($dadosComissao[0]["id_dentista"]);
$pacientes->loadPaciente($dadosComissao[0]["id_paciente"]);


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>Comprovante de comissão</title>

	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/responsivo.css">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/jquery-ui.css" />

	<script type="text/javascript" src="../js/jquery.js"></script>

	<style type="text/css">

		body{
			padding: 10px;
		}
		
		#titulo{
			font-size: 13pt;
			margin: 0 auto;
			text-align: center;
			padding: 5px;
			/*border-bottom: 1px dashed silver;*/
			margin-bottom: 15px;
		}

		#corpo{
			margin-top: 10px;
		}

		#via{
			font-size: 8pt;
		}

	</style>

	<script type="text/javascript">

		window.print();
		
	</script>

</head>

<body>

	<div style="float: left; width: 40%; height: 10px;">

		<div id="titulo">
			<span>RECIBO DE PAGAMENTO</span>
		</div>

		<table class="table">
			<tr>
				<td><b>DATA DE PAGAMENTO:</b></td>
				<td><?php echo date("d/m/Y H:i"); ?></td>
			</tr>
			<tr>
				<td><b>NÚMERO DA O.S:</b></td>
				<td><?php echo $dadosComissao[0]["id_ordem"]; ?></td>
			</tr>
			<tr>
				<td><b>VALOR DA O.S:</b></td>
				<td>R$ <?php echo $moeda->formatar($dadosOrdem[0]["total"]); ?></td>
			</tr>
			<tr>
				<td><b>DENTISTA:</b></td>
				<td><?php echo mb_convert_case(utf8_encode($dentistas->RetornaDados('nome')), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>PACIENTE:</b></td>
				<td><?php echo mb_convert_case(utf8_encode($pacientes->RetornaDados('nome')), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>COMISSÃO (%):</b></td>
				<td><?php echo $dentistas->RetornaDados('comissao'); ?>%</td>
			</tr>
			<tr>
				<td><b>VALOR PAGO:</b></td>
				<td>R$ <?php echo $moeda->formatar(round($dadosComissao[0][valor], 2)); ?></td>
			</tr>
		</table>

		<center>
			<span>___________________________________________________.</span><br>
			<b>
				<span>JOINVILLE, <?php echo date("d")." DE ".$dados->obterMes(date("n"))." DE ".date("Y"); ?> </span>
			</b>
		</center>

	</div>

	<div style="float: right; width: 40%; height: 10px;">

		<div id="titulo">
			<span>RECIBO DE PAGAMENTO</span>
		</div>

		<table class="table">
			<tr>
				<td><b>DATA DE PAGAMENTO:</b></td>
				<td><?php echo date("d/m/Y H:i"); ?></td>
			</tr>
			<tr>
				<td><b>NÚMERO DA O.S:</b></td>
				<td><?php echo $dadosComissao[0]["id_ordem"]; ?></td>
			</tr>
			<tr>
				<td><b>VALOR DA O.S:</b></td>
				<td>R$ <?php echo $moeda->formatar($dadosOrdem[0]["total"]); ?></td>
			</tr>
			<tr>
				<td><b>DENTISTA:</b></td>
				<td><?php echo mb_convert_case(utf8_encode($dentistas->RetornaDados('nome')), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>PACIENTE:</b></td>
				<td><?php echo mb_convert_case(utf8_encode($pacientes->RetornaDados('nome')), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>COMISSÃO (%):</b></td>
				<td><?php echo $dentistas->RetornaDados('comissao'); ?>%</td>
			</tr>
			<tr>
				<td><b>VALOR PAGO:</b></td>
				<td>R$ <?php echo $moeda->formatar(round($dadosComissao[0][valor], 2)); ?></td>
			</tr>
		</table>

		<center>
			<span>___________________________________________________.</span><br>
			<b>
				<span>JOINVILLE, <?php echo date("d")." DE ".$dados->obterMes(date("n"))." DE ".date("Y"); ?> </span>
			</b>
		</center>

	</div>

	
