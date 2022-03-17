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

$conta = new TContas('clinica', 'receber');

$conta->LoadConta($id);

$moeda = new moeda();
$dados = new dados();

$dentistas = new TDentistas();
$pacientes = new TPacientes();

$dentistas->loadDentista($conta->RetornaDados('dentista'));
$pacientes->loadPaciente($conta->RetornaDados('paciente'));


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>Recibo de pagamento</title>

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
				<td><b>PACIENTE:</b></td>
				<td><?php echo mb_convert_case($pacientes->RetornaDados('nome'), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>CPF:</b></td>
				<td><?php echo $dados->formatarCPF($pacientes->RetornaDados("cpf")); ?></td>
			</tr>
			<tr>
				<td><b>DATA DE PAGAMENTO:</b></td>
				<td><?php echo date("d/m/Y H:i"); ?></td>
			</tr>
			<tr>
				<td><b>VENCIMENTO:</b></td>
				<td><?php echo date("d/m/Y", strtotime($conta->RetornaDados('datavencimento'))); ?></td>
			</tr>
			<tr>
				<td><b>VALOR:</b></td>
				<td>R$ <?php echo $moeda->formatar($conta->RetornaDados('valor')); ?></td>
			</tr>
			<tr>
				<td><b>OS:</b></td>
				<td><?php echo $conta->RetornaDados('ordem'); ?></td>
			</tr>
			<tr>
				<td><b>DESCRIÇÃO:</b></td>
				<td><?php echo mb_convert_case($conta->RetornaDados('descricao'), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>DENTISTA:</b></td>
				<td><?php echo mb_convert_case($dentistas->RetornaDados('nome'), MB_CASE_UPPER, "UTF-8"); ?></td>
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
				<td><b>PACIENTE:</b></td>
				<td><?php echo mb_convert_case($pacientes->RetornaDados('nome'), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>CPF:</b></td>
				<td><?php echo $dados->formatarCPF($pacientes->RetornaDados("cpf")); ?></td>
			</tr>
			<tr>
				<td><b>DATA DE PAGAMENTO:</b></td>
				<td><?php echo date("d/m/Y H:i"); ?></td>
			</tr>
			<tr>
				<td><b>VENCIMENTO:</b></td>
				<td><?php echo date("d/m/Y", strtotime($conta->RetornaDados('datavencimento'))); ?></td>
			</tr>
			<tr>
				<td><b>VALOR:</b></td>
				<td>R$ <?php echo $moeda->formatar($conta->RetornaDados('valor')); ?></td>
			</tr>
			<tr>
				<td><b>OS:</b></td>
				<td><?php echo $conta->RetornaDados('ordem'); ?></td>
			</tr>
			<tr>
				<td><b>DESCRIÇÃO:</b></td>
				<td><?php echo mb_convert_case($conta->RetornaDados('descricao'), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			<tr>
				<td><b>DENTISTA:</b></td>
				<td><?php echo mb_convert_case($dentistas->RetornaDados('nome'), MB_CASE_UPPER, "UTF-8"); ?></td>
			</tr>
			
		</table>

		<center>
			<span>___________________________________________________.</span><br>
			<b>
				<span>JOINVILLE, <?php echo date("d")." DE ".$dados->obterMes(date("n"))." DE ".date("Y"); ?> </span>
			</b>
		</center>

	</div>

