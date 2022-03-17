<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
if(!checklog()) {
	die($frase_log);
}
$caixa = new TCaixa();
if(isset($_POST[Salvar])) {	
	$obrigatorios[1] = 'data';
	$obrigatorios[] = 'descricao';
	$obrigatorios[] = 'dc';
	$obrigatorios[] = 'valor';
	$i = $j = 0;
	foreach($_POST as $post => $valor) {
		$i++;
		if(array_search($post, $obrigatorios) && $valor == "") {
			$j++;
			$r[$j] = '<font color="#FF0000">';
		}
	}
	if($j == 0) {
		$caixa->SalvarNovo();
		$caixa->SetDados('data', converte_data($_POST[data], 1));
		$caixa->SetDados('descricao', $_POST[descricao]);
		$caixa->SetDados('dc', $_POST[dc]);
		$caixa->SetDados('valor', $_POST[valor]);
		$caixa->Salvar();
	}
}

$os = new os();
?>


<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-theme.min.css" />
<link rel="stylesheet" href="../css/font-awesome.min.css" />

<style type="text/css">

	body, html, table, tr, td{
		font-size: 10pt;
	}



</style>

<?php
if(isset($_GET["print"]))
{
	echo "<script type='text/javascript'>window.print();</script>";
	echo "<style>#sumir{display:none;}#aparecer{display:block !important;}</style>";
}

?>

<div class="panel panel-default" id="aparecer" style="display: none;font-size: 12pt;">

	<div class="panel-heading">Relatório de fluxo de caixa</div>
	<div class="panel-body">
	<b>Periodo:</b> Todos<br>
	<b>Horário de impressão:</b> <?php echo date("d/m/Y H:i:s"); ?>
	</div>

</div>

<a href="caixa/inicial_ajax.php?print=" target="_blank" id="sumir">
	<button class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Imprimir Fluxo</button>
</a>
<br><br>
<table class="table table-hover table-bordered">
	<thead>
		<th></th>
		<th></th>
		<th width="11%" height="23" align="left"><?php echo $LANG['cash_flow']['date']?></th>
		<th width="41%" align="left"><?php echo $LANG['cash_flow']['description']?></th>
		<th width="13%" align="center"><?php echo $LANG['cash_flow']['debit']?></th>
		<th width="13%" align="center"><?php echo $LANG['cash_flow']['credit']?></th>
		<th width="13%" align="center">Caixa Atual</th>
		<th width="10%" align="center" id="sumir"><?php echo $LANG['patients']['delete']?></th>
	</thead>
	<tbody>
		<?php
		$lista = $caixa->ListCaixa("SELECT * FROM `caixa` ORDER BY `data` DESC,  `codigo` DESC");
		$par = "F0F0F0";
		$impar = "F8F8F8";
		for($i = 0; $i < count($lista); $i++) {
			if($lista[$i][dc] != '') {
				if($i % 2 == 0) {
					$odev = $par;
				} else {
					$odev = $impar;
				}
				if($lista[$i][dc] == "-") {
					$debito = $LANG['general']['currency'].' -'.money_form($lista[$i][valor]);
					$credito = '';
				} else {
					$debito = '';
					$credito = $LANG['general']['currency'].' '.money_form($lista[$i][valor]);
				}
				if($lista[$i][dc] == '-') {
					$saldo -= $lista[$i][valor];
					$saldod += $lista[$i][valor];
				} else {
					$saldo += $lista[$i][valor];
					$saldoc += $lista[$i][valor];
				}

				$saldo = $caixa->SaldoTotal();

				if($caixa->SaldoTotal() < 0) $cor = "f14949";

				for($j = $i-1; $j >= 0; $j--) {
					if($lista[$j][dc] == '-') {
						$saldo += $lista[$j][valor];
					} else {
						$saldo -= $lista[$j][valor];
					}
				}

				?>
				<tr>

					<td width="4%"><?php echo $os->getModoPagamento($lista[$i]["modo_pagamento"]); ?></td>
					<td width="3%">
						<?php

						if($lista[$i][dc] == "-")
						{
							echo "<span style='color: #f14949;' class='glyphicon glyphicon-arrow-up'></span>";
						}else{
							echo "<span style='color: #43a200;' class='glyphicon glyphicon-arrow-down'></span>";
						}

						?>
					</td>
					<td width="3%" height="23" align="left"><?php echo converte_data($lista[$i][data], 2)?></td>
					<td width="41%" align="left"><?php echo $lista[$i][descricao];?></td>
					<td width="13%" align="left"><?php echo $debito?></td>
					<td width="13%" align="left"><?php echo $credito?></td>
					<td width="13%" align="left">-</td>
					<td width="10%" align="center" id="sumir"><?php echo ((verifica_nivel('caixa', 'A'))?'<a href="javascript:Ajax(\'caixa/extrato\', \'conteudo\', \'codigo='.$lista[$i]['codigo'].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?></td>
				</tr>

				<?php
			}
		}
		?>
	<!--<tr>
		<td><b><?php echo date("d/m/Y"); ?></b></td>
		<td><b>Saldo total em caixa:</b></td>
		<td><b>-</b></td>
		<td><b>-</b></td>
		<td><b>R$ <?php echo money_form($caixa->SaldoTotal()); ?></b></td>
	</tr>-->
	<tr bgcolor="#<?php echo $odev?>" onmouseout="style.background='#<?php echo $odev?>'" onmouseover="style.background='#DDE1E6'">
		<td></td>
		<td></td>
		<td width="51%" colspan="2" height="23" align="left"><b><?php echo $LANG['cash_flow']['total']?></b></td>
		<td width="13%" align="right"><b><?php echo $LANG['general']['currency'].' '.money_form($saldod)?></b></td>
		<td width="13%" align="right"><b><?php echo $LANG['general']['currency'].' '.money_form($saldoc)?></b></td>
		<td width="13%" align="right"><font color="#<?php echo $cor?>"><b><?php echo $LANG['general']['currency'].' '.money_form($caixa->SaldoTotal())?></b></form></td>
		<td width="10%" align="center" id="sumir"></td>
	</tr>
</tbody>
</table>
<br><br>
<a href="caixa/inicial_ajax.php?print=" target="_blank" id="sumir">
	<button class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Imprimir Fluxo</button>
</a>
