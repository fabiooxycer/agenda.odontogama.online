<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);

if(!checklog()) {
	//die($frase_log);
}

$moeda = new moeda();

$sistema = new sistema();
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

//$movimentacao = mysqli_real_escape_string($conn, $_GET["fluxo"]);
$dentista = mysqli_real_escape_string($conn, $_GET["dentista"]);
$tipo = mysqli_real_escape_string($conn, $_GET["tipo"]);
$dataInicial = mysqli_real_escape_string($conn, $_GET["dataInicial"]);
$dataFinal = mysqli_real_escape_string($conn, $_GET["dataFinal"]);

$tip[0] = "A pagar";
$tip[1] = "Já pagas";

# FORMAS DA PAGAMENTO
/*$pag[1] = "Dinheiro";
$pag[2] = "Cartão";
$pag[3] = "Cheque";
$pag[4] = "Promissória";
$pag["T"] = "Todas";

# TIPO DE MOVIMENTAÇÃO
$mov["+"] = "Contas recebidas";
$mov["-"] = "Contas pagas";
$mov["."] = "Todas";*/

#SOMA TOTAL
$totalDetalhado = 0.00;

if($dataInicial == "")
{
	$dataInicial = "2000-01-01";
}else{
	$dataInicial = new dateTime(str_replace("/", "-", $dataInicial));
	$dataInicial = $dataInicial->format("Y-m-d");
}
if($dataFinal == "")
{
	$dataFinal = date("Y-m-d");
}else{
	$dataFinal = new dateTime(str_replace("/", "-", $dataFinal));
	$dataFinal = $dataFinal->format("Y-m-d");
}


/*if($forma_pagamento != "T") $complemento = "modo_pagamento='$forma_pagamento' AND";

if($movimentacao == ".") // todos os tipos de movimentação
{
	$sql_1 = "SELECT caixa.* FROM caixa WHERE $complemento codigo!='NULL' AND modo_pagamento!='0' AND data BETWEEN '$dataInicial' AND '$dataFinal'";
	$sql = "SELECT sum(valor), caixa.* FROM caixa WHERE $complemento codigo!='NULL' AND modo_pagamento!='0' AND data BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY modo_pagamento ASC";
}else{
	$sql_1 = "SELECT caixa.* FROM caixa WHERE $complemento codigo!='NULL' AND modo_pagamento!='0' AND dc='$movimentacao' AND data BETWEEN '$dataInicial' AND '$dataFinal'";
	$sql = "SELECT sum(valor), caixa.* FROM caixa WHERE $complemento codigo!='NULL' AND modo_pagamento!='0' AND dc='$movimentacao' AND data BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY modo_pagamento ASC";
}*/

$sql_1 = "SELECT c.data AS data, c.id_ordem AS id_ordem, c.valor AS valor, p.nome AS paciente, d.comissao AS comissao FROM tb_comissao AS c INNER JOIN pacientes AS p ON c.id_paciente=p.codigo INNER JOIN dentistas AS d ON c.id_dentista=d.codigo WHERE c.id_dentista='$dentista' AND c.status='$tipo' AND c.data BETWEEN '$dataInicial' AND '$dataFinal' ORDER BY c.data DESC";
$sql = "SELECT sum(valor), tb_comissao.* FROM tb_comissao WHERE id_dentista='$dentista' AND status='$tipo' AND data BETWEEN '$dataInicial' AND '$dataFinal' ORDER BY data DESC";

$os = new OS();

$dentista_obj = new TDentistas();

$dentista_obj->LoadDentista($dentista);

$dados = $os->carregarComissao("sql", $sql);

if(count($dados) >= 1 AND $dados[0][0] != NULL)
{
	if(!isset($_GET["print"]))
	{
		echo"
		<a href='relatorios/comissao_pesquisa.php?print=print&dentista=".$dentista."&tipo=".$tipo."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal."' target='_blank'>
			<button class='btn btn-primary' id='imprimir'>
				<span class='glyphicon glyphicon-print'></span> Imprimir
			</button>
		</a>";
	}else{
		echo "<script type='text/javascript'>window.print();</script>";
	}

	echo"

	<center><h3>RELATÓRIO DE COMISSÃO - (VIA CLÍNICA)</h3></center><br>
	<table class='table' id='sem'>
		<tr>
			<td><b>Periodo do relatório:</b><br>
				".date("d/m/Y", strtotime($dataInicial))." à ".date("d/m/Y", strtotime($dataFinal))."
			</td>

			<td><b>Dentista</b><br>
				".utf8_encode($dentista_obj->RetornaDados("nome"))."
			</td>

			<td><b>Tipo:</b><br>
				".$tip[$tipo]." 
			</td>
		</tr>
	</table>
	<br><br><Br>

	<div class='panel panel-default'>
		<div class='panel-heading'><b>Movimentações consolidadas</div>
		<div class='panel-body'>
			<table class=\"table\" id=\"sem\">
				<thead>
					<th></th>
					<th>Movimentação</th>
					<th>Total (R$)</th>
				</thead>
				<tbody>";

					for($i = 0; $i < count($dados); $i++)
					{
						echo"
						<tr>
							<td>";

								if($dados[$i]["status"] == "0")
								{
									echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-right'></span>";
								}else{
									echo "<span style='color: #43a200;' class='glyphicon glyphicon-ok'></span>";
								}

								echo"
							</td>
							<td>".$tip[$dados[$i]["status"]]."</td>
							<td>R$ ".$moeda->formatar(round($dados[$i][0], 2))."</td>
						</tr>";

					}

					echo"
				</tbody>
			</table>
		</div>
	</div>

	<div class='panel panel-default'>
		<div class='panel-heading'><b>Movimentações detalhadas</div>
		<div class='panel-body' style='min-height: 600px;'>
			<table class='table' id='sem'>
				<thead>
					<th></th>
					<th>Data</th>
					<th>O.S</th>
					<th>Paciente</th>
					<th>%</th>
					<th>Valor</th>
				</thead>

				<tbody>";

					$dados_1 = $os->carregarComissao("sql", $sql_1);
					for($i = 0; $i < count($dados_1); $i++)
					{
						$totalDetalhado+=$dados_1[$i]["valor"];

						echo"
						<tr>
							<td>";



								if($dados_1[$i]["status"] == "0")
								{
									echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-right'></span>";
								}else{
									echo "<span style='color: #43a200;' class='glyphicon glyphicon-ok'></span>";
								}

								echo"

							</td>
							<td>".date("d/m/Y", strtotime($dados_1[$i]["data"]))."</td>

							<td>Nº ".$dados_1[$i]["id_ordem"]."</td>
							<td>".$dados_1[$i]["paciente"]."</td>
							<th>".$dados_1[$i]["comissao"]."%</td>
							<td>R$ ".$moeda->formatar(round($dados_1[$i]["valor"], 2))."</td>

						</tr>";
					}

					echo"
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>*TOTAL</td>
						<td>R$ ".$moeda->formatar(round($totalDetalhado, 2))."</td>
					</tr>
				</tbody>
			</table>

			<div class='panel panel-default'>
				<div class='panel-body'>
				Assinatura do dentista:
				</div>
			</div>

		</div>
	</div>
	<hr></hr>
	

	";

	echo"

	<center><h3>RELATÓRIO DE COMISSÃO - (VIA DENTISTA)</h3><br></center><br>
	<table class='table' id='sem'>
		<tr>
			<td><b>Periodo do relatório:</b><br>
				".date("d/m/Y", strtotime($dataInicial))." à ".date("d/m/Y", strtotime($dataFinal))."
			</td>

			<td><b>Dentista</b><br>
				".utf8_encode($dentista_obj->RetornaDados("nome"))."
			</td>

			<td><b>Tipo:</b><br>
				".$tip[$tipo]." 
			</td>
		</tr>
	</table>
	<br><br><Br>

	<div class='panel panel-default'>
		<div class='panel-heading'><b>Movimentações consolidadas</div>
		<div class='panel-body'>
			<table class=\"table\" id=\"sem\">
				<thead>
					<th></th>
					<th>Movimentação</th>
					<th>Total (R$)</th>
				</thead>
				<tbody>";

					for($i = 0; $i < count($dados); $i++)
					{
						echo"
						<tr>
							<td>";

								if($dados[$i]["status"] == "0")
								{
									echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-right'></span>";
								}else{
									echo "<span style='color: #43a200;' class='glyphicon glyphicon-ok'></span>";
								}

								echo"
							</td>
							<td>".$tip[$dados[$i]["status"]]."</td>
							<td>R$ ".$moeda->formatar(round($dados[$i][0], 2))."</td>
						</tr>";

					}

					echo"
				</tbody>
			</table>
		</div>
	</div>

	<div class='panel panel-default'>
		<div class='panel-heading'><b>Movimentações detalhadas</div>
		<div class='panel-body'>
			<table class='table' id='sem'>
				<thead>
					<th></th>
					<th>Data</th>
					<th>O.S</th>
					<th>Paciente</th>
					<th>%</th>
					<th>Valor</th>
				</thead>

				<tbody>";

					$dados_1 = $os->carregarComissao("sql", $sql_1);
					for($i = 0; $i < count($dados_1); $i++)
					{
						$totalDetalhado+=$dados_1[$i]["valor"];

						echo"
						<tr>
							<td>";



								if($dados_1[$i]["status"] == "0")
								{
									echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-right'></span>";
								}else{
									echo "<span style='color: #43a200;' class='glyphicon glyphicon-ok'></span>";
								}

								echo"

							</td>
							<td>".date("d/m/Y", strtotime($dados_1[$i]["data"]))."</td>

							<td>Nº ".$dados_1[$i]["id_ordem"]."</td>
							<td>".$dados_1[$i]["paciente"]."</td>
							<th>".$dados_1[$i]["comissao"]."%</td>
							<td>R$ ".$moeda->formatar(round($dados_1[$i]["valor"], 2))."</td>

						</tr>";
					}

					echo"
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>*TOTAL</td>
						<td>R$ ".$moeda->formatar(round($totalDetalhado, 2))."</td>
					</tr>
				</tbody>
			</table>

			<div class='panel panel-default'>
				<div class='panel-body'>
				Assinatura da clínica:
				</div>
			</div>

		</div>
	</div>
	<hr></hr>
	
	
	";
}else{
	echo "<li>Nenhum resultado encontrado!</li>";
}

?>

<link rel="stylesheet" href="../css/bootstrap.css">


<style type="text/css">
	
	body, html{
		font-size: 9pt;
	}

	.table{
		font-size: 9pt;
	}

	#sem tr td{
		border-top: 1px solid transparent;
	}

</style>



