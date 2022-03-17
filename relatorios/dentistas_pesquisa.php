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

if($dentista == "T")
{
	$sql_1 = "SELECT *, d.nome AS n_dentista, p.nome AS n_paciente FROM tb_ordens AS o INNER JOIN dentistas AS d ON o.dentista=d.codigo INNER JOIN pacientes AS p ON o.paciente=p.codigo WHERE o.data BETWEEN '$dataInicial' AND '$dataFinal' ORDER BY o.id DESC";
	$sql = "SELECT sum(total), o.*, d.* FROM tb_ordens AS o INNER JOIN dentistas AS d ON o.dentista=d.codigo WHERE data BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY dentista ORDER BY data DESC";
}else{
	$sql_1 = "SELECT *, d.nome AS n_dentista, p.nome AS n_paciente FROM tb_ordens AS o INNER JOIN dentistas AS d ON o.dentista=d.codigo INNER JOIN pacientes AS p ON o.paciente=p.codigo WHERE dentista='$dentista' AND o.data BETWEEN '$dataInicial' AND '$dataFinal' ORDER BY o.id DESC";
	$sql = "SELECT sum(o.total), o.*, d.* FROM tb_ordens AS o INNER JOIN dentistas AS d ON o.dentista=d.codigo WHERE dentista='$dentista' AND data BETWEEN '$dataInicial' AND '$dataFinal' ORDER BY data DESC";
}

$os = new OS();

$dentista_obj = new TDentistas();

$dentista_obj->LoadDentista($dentista);

$dados = $os->carregarGanhos("sql", $sql); // carrega consolidado.

if(count($dados) >= 1 AND $dados[0][0] != NULL)
{
	if(!isset($_GET["print"]))
	{
		echo"
		<a href='relatorios/dentistas_pesquisa.php?print=print&dentista=".$dentista."&tipo=".$tipo."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal."' target='_blank'>
			<button class='btn btn-primary' id='imprimir'>
				<span class='glyphicon glyphicon-print'></span> Imprimir
			</button>
		</a>";
	}else{
		echo "
		<script type='text/javascript'>
			window.print();
		</script>";
	}

	echo"

	<center><h3>RELATÓRIO DE GANHOS</h3></center><br>
	<table class='table' id='sem'>
		<tr>
			<td><b>Periodo do relatório:</b><br>
				".date("d/m/Y", strtotime($dataInicial))." à ".date("d/m/Y", strtotime($dataFinal))."
			</td>

			<td><b>Dentista</b><br>
				";
				if($dentista == "T")
				{
					echo "TODOS";
				}else{
					echo mb_convert_case(utf8_encode($dentista_obj->RetornaDados("nome")), MB_CASE_UPPER, "UTF-8");
				}
				echo "
			</td>
			<!--
			<td><b>Tipo:</b><br>
				".$tip[$tipo]." 
			</td>
		-->
	</tr>
</table>
<br><br><Br>


<div class='panel panel-default'>
	<div class='panel-heading'><b>Movimentações consolidadas</div>
	<div class='panel-body'>
		<table class=\"table\" id=\"sem\">
			<thead>
				<!--<th></th>-->
				<th>DENTISTA</th>
				<th>TOTAL (R$)</th>
			</thead>
			<tbody>";

				for($i = 0; $i < count($dados); $i++)
				{
					echo"
					<tr>
						<!--
						<td>";

							if($dados[$i]["status"] == "0")
							{
								echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-right'></span>";
							}else{
								echo "<span style='color: #43a200;' class='glyphicon glyphicon-ok'></span>";
							}

							echo"
						</td>
					-->
					<td>".mb_convert_case($dados[$i]["nome"], MB_CASE_UPPER, "UTF-8")."</td>
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
				<th>DENTISTA</th>
				<th>Valor</th>
			</thead>

			<tbody>";

				$dados_1 = $os->carregarComissao("sql", $sql_1);
				for($i = 0; $i < count($dados_1); $i++)
				{
					$totalDetalhado+=$dados_1[$i]["total"];

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

						<td>Nº ".$dados_1[$i]["id"]."</td>
						<td>".$dados_1[$i]["n_paciente"]."</td>
						<td>".mb_convert_case($dados_1[$i]["n_dentista"], MB_CASE_UPPER, "UTF-8")."</td>
						<td>R$ ".$moeda->formatar(round($dados_1[$i]["total"], 2))."</td>

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
					<td></td>
					<td>*TOTAL</td>
					<td>R$ ".$moeda->formatar(round($totalDetalhado, 2))."</td>
				</tr>
			</tbody>
		</table>

		<!--
		<div class='panel panel-default'>
			<div class='panel-body'>
				Assinatura do dentista:
			</div>
		</div>
	-->

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



