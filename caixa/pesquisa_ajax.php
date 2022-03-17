<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
$sistema = new sistema(); 
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

if(!checklog()) {
	die($frase_log);
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
<?php

	if($_GET['peri'] == "mesatual")
	{
		echo"
		<div class=\"panel-heading\">Relatório de fluxo de caixa</div>
		<div class=\"panel-body\">
		<b>Periodo:</b> ".date("m/Y")."<br>
		<b>Horário de impressão:</b> ".date("d/m/Y H:i:s")."
		</div>";
	}

	if($_GET['peri'] == "ano")
	{
		echo"
		<div class=\"panel-heading\">Relatório de fluxo de caixa</div>
		<div class=\"panel-body\">
		<b>Periodo:</b> Ano de ".$_GET['pesquisa']."<br>
		<b>Horário de impressão:</b> ".date("d/m/Y H:i:s")."
		</div>";
	}

	if($_GET['peri'] == "mes")
	{
		echo"
		<div class=\"panel-heading\">Relatório de fluxo de caixa</div>
		<div class=\"panel-body\">
		<b>Periodo:</b> ".$_GET['pesquisa']."<br>
		<b>Horário de impressão:</b> ".date("d/m/Y H:i:s")."
		</div>";
	}

	if($_GET['peri'] == "dia")
	{
		echo"
		<div class=\"panel-heading\">Relatório de fluxo de caixa</div>
		<div class=\"panel-body\">
		<b>Periodo: </b> ".$_GET['pesquisa']."<br>
		<b>Horário de impressão:</b> ".date("d/m/Y H:i:s")."
		</div>";
	}

?>
	

</div>


<a href="caixa/pesquisa_ajax.php?peri=<?php echo $_GET['peri']; ?>&pesquisa=<?php echo $_GET['pesquisa']; ?>&print=" target="_blank" id="sumir">
	<button class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Imprimir Fluxo</button>
</a>
<br><Br>
<table class="table table-hover table-bordered">
	<thead>
		<th></th>
		<th></th>
		<th width="11%" height="23" align="left"><?php echo $LANG['cash_flow']['date']?></th>
		<th width="41%" align="left"><?php echo $LANG['cash_flow']['description']?></th>
		<th width="13%" align="center"><?php echo $LANG['cash_flow']['debit']?></th>
		<th width="13%" align="center"><?php echo $LANG['cash_flow']['credit']?></th>
		<th width="13%" align="center"><?php echo $LANG['cash_flow']['total']?></th>
		<th width="10%" align="center" id="sumir"><?php echo $LANG['patients']['delete']?></th>
	</thead>
	<?php
	$caixa = new TCaixa();
	$data = converte_data($_GET[pesquisa], 1);
	switch ($_GET[peri]) {
		case 'dia': {
			$sql = "SELECT * FROM `caixa` WHERE `data` = '$data' ORDER BY `data` ASC, `codigo` ASC";
		} break;
		case 'mes': {
			$sql = "SELECT * FROM `caixa` WHERE LEFT(`data`, 7) = '$data' ORDER BY `data` ASC, `codigo` ASC";
		} break;
		case 'ano': {
			$sql = "SELECT * FROM `caixa` WHERE LEFT(`data`, 4) = '$data' ORDER BY `data` ASC, `codigo` ASC";
		} break;
		case 'mesatual': {
			$sql = "SELECT * FROM `caixa` WHERE LEFT(`data`, 7) = '".date('Y-m')."' ORDER BY `data` ASC, `codigo` ASC";
		} break;
	}
	$lista = $caixa->ListCaixa($sql);
	$par = "F0F0F0";
	$impar = "F8F8F8";
	$saldo = 0;
	$saldoc = 0;
	$saldod = 0;
	for($i = 0; $i < count($lista); $i++) {
		if($_GET['cpf_dentista'] != 0) {
			$codigo_parcela = explode(' - ', $lista[$i]['descricao']);
			$codigo_parcela = explode(' ', $codigo_parcela[0]);
			$codigo_parcela = ((strpos($lista[$i]['descricao'], 'Pagamento da parcela') !== false)?$codigo_parcela[(count($codigo_parcela)-1)]:'');
			$sql1 = "SELECT tor.cpf_dentista FROM orcamento tor INNER JOIN parcelas_orcamento tpo ON tor.codigo = tpo.codigo_orcamento WHERE tpo.codigo = ".$codigo_parcela;
			$query1 = @mysqli_query($conn, $sql1);
			$row1 = @mysqli_fetch_assoc($query1);
			if($_GET['cpf_dentista'] != $row1['cpf_dentista'] || !is_numeric($codigo_parcela)){
				continue;
			}
		}
		if($lista[$i][dc] != '') {
			if($i % 2 == 0) {
				$odev = $par;
			} else {
				$odev = $impar;
			}
			if($lista[$i][dc] == "-") {
				$debito = $LANG['general']['currency'].' '.money_form($lista[$i][valor]);
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
			if($saldo < 0) {
				$cor = "FF0000";
			} else {
				$cor = "000000";
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
				<td width="11%" height="23" align="left"><?php echo converte_data($lista[$i][data], 2)?></td>
				<td width="41%" align="left"><?php echo utf8_encode($lista[$i]["descricao"]);?></td>
				<td width="13%" align="right"><?php echo $debito?></td>
				<td width="13%" align="right"><?php echo $credito?></td>
				<td width="13%" align="right"><font color="#<?php echo $cor?>"><?php echo $LANG['general']['currency'].' '.money_form($saldo)?></form></td>
				<td width="10%" align="center" id="sumir"><?php echo ((verifica_nivel('caixa', 'A'))?'<a href="javascript:Ajax(\'caixa/extrato\', \'conteudo\', \'codigo='.$lista[$i]['codigo'].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?></td>
			</tr>
			<?php
		}
	}
	if($odev == $impar) {
		$odev = $par;
	} else {
		$odev = $impar;
	}	
	?>
	
	<tr bgcolor="#<?php echo $odev?>" onmouseout="style.background='#<?php echo $odev?>'" onmouseover="style.background='#DDE1E6'">
		<td width="51%" colspan="4" height="23" align="left"><b><?php echo $LANG['cash_flow']['total']?></b></td>
		<td width="13%" align="right"><b><?php echo $LANG['general']['currency'].' '.money_form($saldod)?></b></td>
		<td width="13%" align="right"><b><?php echo $LANG['general']['currency'].' '.money_form($saldoc)?></b></td>
		<td width="13%" align="right"><font color="#<?php echo $cor?>"><b><?php echo $LANG['general']['currency'].' '.money_form($saldo)?></b></form></td>
		<td width="10%" align="center" id="sumir"></td>
	</tr>
</table><br />
<a href="caixa/pesquisa_ajax.php?peri=<?php echo $_GET['peri']; ?>&pesquisa=<?php echo $_GET['pesquisa']; ?>&print=" target="_blank" id="sumir">
	<button class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Imprimir Fluxo</button>
</a>