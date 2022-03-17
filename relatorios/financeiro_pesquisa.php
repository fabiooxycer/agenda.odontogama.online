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

$movimentacao = mysqli_real_escape_string($conn, $_GET["fluxo"]);
$forma_pagamento = mysqli_real_escape_string($conn, $_GET["forma_pagamento"]);
$dataInicial = mysqli_real_escape_string($conn, $_GET["dataInicial"]);
$dataFinal = mysqli_real_escape_string($conn, $_GET["dataFinal"]);
$fornecedor = mysqli_real_escape_string($conn, $_GET["fornecedor"]);

# FORMAS DA PAGAMENTO
$pag[1] = "Dinheiro";
$pag[2] = "Cartão de débito";
$pag[3] = "Cheque";
$pag[4] = "Promissória";
$pag[5] = "Boleto";
$pag[6] = "Cartão de crédito";

$pag["T"] = "Todas";

# TIPO DE MOVIMENTAÇÃO
$mov["+"] = "Contas recebidas";
$mov["-"] = "Contas pagas";
$mov["AP"] = "Contas a pagar";
$mov["AR"] = "Contas a receber";

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
	
	$dataFinal = date("Y-m-d", strtotime("2099-01-01"));
	
}else{
	$dataFinal = new dateTime(str_replace("/", "-", $dataFinal));
	$dataFinal = $dataFinal->format("Y-m-d");
}


if($forma_pagamento != "T")
{
	if($movimentacao != "AP" AND $movimentacao != "AR")
	{
		$complemento = "forma_pagamento='$forma_pagamento' AND";
	}else{
		$complemento = "forma_pagamento='$forma_pagamento' AND";
	}
}

if($fornecedor != "" AND ($movimentacao == "AP" OR $movimentacao == "-"))
{
	$complemento.=" fornecedor='$fornecedor' AND";
}

if($movimentacao == "+") // contas recebidas
{
	$sql_1 = "SELECT contasreceber.* FROM contasreceber WHERE $complemento codigo!='NULL' AND forma_pagamento!='0' AND status='1' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal'";
	$sql = "SELECT sum(valor), contasreceber.* FROM contasreceber WHERE $complemento codigo!='NULL' AND forma_pagamento!='0' AND status='1' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY forma_pagamento ASC";
}

if($movimentacao == "-") // contas recebidas
{
	$sql_1 = "SELECT contaspagar.* FROM contaspagar WHERE $complemento codigo!='NULL' AND forma_pagamento!='0' AND status='1' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal'";
	$sql = "SELECT sum(valor), contaspagar.* FROM contaspagar WHERE $complemento codigo!='NULL' AND forma_pagamento!='0' AND status='1' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY forma_pagamento ASC";
}

if($movimentacao == "AP")
{
	$sql_1 = "SELECT contaspagar.* FROM contaspagar WHERE $complemento codigo!='NULL' AND status='0' AND forma_pagamento!='0' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal'";
	$sql = "SELECT sum(valor), contaspagar.* FROM contaspagar WHERE $complemento codigo!='NULL' AND status='0' AND forma_pagamento!='0' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY forma_pagamento ASC";
}

if($movimentacao == "AR")
{
	$sql_1 = "SELECT contasreceber.* FROM contasreceber WHERE $complemento codigo!='NULL' AND status='0' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal'";
	$sql = "SELECT sum(valor), contasreceber.* FROM contasreceber WHERE $complemento codigo!='NULL' AND status='0' AND datavencimento BETWEEN '$dataInicial' AND '$dataFinal' GROUP BY forma_pagamento ASC";
}

$caixa = new TCaixa();

//echo $sql;

$dados = $caixa->ListCaixa($sql);

if(count($dados) >= 1 AND $dados[0][0] != NULL)
{
	if(!isset($_GET["print"]))
	{
		echo"
		<a href='relatorios/financeiro_pesquisa.php?print=print&fornecedor=".$fornecedor."&fluxo=".urlencode($movimentacao)."&forma_pagamento=".$forma_pagamento."&dataInicial=".$dataInicial."&dataFinal=".$dataFinal."' target='_blank'>
			<button class='btn btn-primary' id='imprimir'>
				<span class='glyphicon glyphicon-print'></span> Imprimir
			</button>
		</a>";
	}else{
		echo "<script type='text/javascript'>window.print();</script>";
	}

	echo"

	<center><h3>RELATÓRIO FINANCEIRO</h3></center><br>
	<table class='table' id='sem'>
		<tr>
			<td><b>Periodo do relatório:</b><br>
				".date("d/m/Y", strtotime($dataInicial))." à ".date("d/m/Y", strtotime($dataFinal))."
			</td>

			<td><b>Forma de pagamento:</b><br>
				".$pag[$forma_pagamento]."
			</td>

			<td><b>Movimentação:</b><br>
				".$mov[$movimentacao]." 
			</td>
			";

			if($fornecedor != "")
			{
				$fornecedores = new TFornecedores();
				$fornecedores->LoadFornecedores($fornecedor);
				echo "
				<td><b>Fornecedor:</b><br>
				".$fornecedores->RetornaDados("razaosocial")."
				</td>

				";
			}

			echo"
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
					<th>Forma de pagamento</th>
					<th>Total (R$)</th>
				</thead>
				<tbody>";

					for($i = 0; $i < count($dados); $i++)
					{
						
						echo"
						<tr>
							<td>";

								if($movimentacao == "-" OR $movimentacao == "AP")
								{
									echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-up'></span>";
								}else{
									echo "<span style='color: #43a200;' class='glyphicon glyphicon-arrow-down'></span>";
								}

								echo"
							</td>
							<td>".$mov[$movimentacao]."</td>
							<td>".$pag[$dados[$i]["forma_pagamento"]]."</td>
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
					<th>Pagamento</th>
					<th>Descrição</th>
					<th>Valor</th>
				</thead>

				<tbody>";

					$dados_1 = $caixa->ListCaixa($sql_1);
					for($i = 0; $i < count($dados_1); $i++)
					{
						$totalDetalhado+=$dados_1[$i]["valor"];

						
						echo"
						<tr>
							<td>";

								if($movimentacao == "-" OR $movimentacao == "AP")
								{
									echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-up'></span>";
								}else{
									echo "<span style='color: #43a200;' class='glyphicon glyphicon-arrow-down'></span>";
								}

								echo"

							</td>
							<td>".date("d/m/Y", strtotime($dados_1[$i]["datavencimento"]))."</td>
							<td>".$pag[$dados_1[$i]["forma_pagamento"]]."</td>
							<td>".utf8_encode($dados_1[$i]["descricao"])."</td>
							<td>R$ ".$moeda->formatar(round($dados_1[$i]["valor"], 2))."</td>

						</tr>";
					}
					

					echo"
					<tr>
						<td></td>
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
		</div>
	</div>
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



