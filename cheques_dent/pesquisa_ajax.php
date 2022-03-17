<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	$senha = mysql_fetch_array(mysql_query("SELECT * FROM `dentistas` WHERE `codigo` = '".$_SESSION['codigo']."'"));
?>
  <table class="table table-hover">
  	<thead>
  	  <th><?php echo $LANG['check_control']['holder']?></th>
      <th><?php echo $LANG['check_control']['received_from']?></th>
      <th><?php echo $LANG['check_control']['forwarded_to']?></th>
      <th><?php echo $LANG['check_control']['compensation_date']?></th>
      <th><?php echo $LANG['check_control']['value']?></th>
      <th>Ações</th>
  	</thead>
<?php
	$cheque = new TCheques('dentista');
	if($_GET['campo'] == 'nometitular') {
		$where = "`nometitular` LIKE '%".$_GET['pesquisa']."%'";
	} elseif($_GET['campo'] == 'recebidode') {
		$where = "`recebidode` LIKE '%".$_GET['pesquisa']."%'";
	}elseif($_GET['campo'] == 'encaminhadopara') {
		$where = "`encaminhadopara` LIKE '%".$_GET['pesquisa']."%'";
	} elseif($_GET['campo'] == 'compensacao') {
		$where = "`compensacao` = '".converte_data($_GET['pesquisa'], 1)."'";
	}
	if($_GET['pg'] != '') {
		$limit = ($_GET['pg']-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET['pg'] = 1;
	}
	$sql = "SELECT * FROM `cheques_dent` WHERE `codigo_dentista` = '" . $_SESSION['codigo'] . "' AND $where ORDER BY `" . $_GET['campo'] . "` ASC";
	$lista = $cheque->ListCheque($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $cheque->ListCheque($sql);
	$par = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
		$cheque->LoadCheque($lista[$i]['codigo']);
?>
    <tr>
      <td><?php echo utf8_encode($cheque->RetornaDados('nometitular'));?></td>
      <td><?php echo utf8_encode($cheque->RetornaDados('recebidode'));?></td>
      <td><?php echo utf8_encode($cheque->RetornaDados('encaminhadopara'))?></td>
      <td><?php echo converte_data($cheque->RetornaDados('compensacao'), 2)?></td>
      <td><?php echo $LANG['general']['currency'].''.money_form($cheque->RetornaDados('valor'))?></td>
      <td>
      	<?php echo ((verifica_nivel('cheques', 'V'))?'<a href="javascript:Ajax(\'cheques_dent/incluir\', \'conteudo\', \'codigo='.$cheque->RetornaDados('codigo').'&acao=editar\')"><button class="btn btn-default" title="Editar"><span class="glyphicon glyphicon-pencil"></span></button></a>':'')?>
      	<?php echo ((verifica_nivel('cheques', 'A'))?'<a href="javascript:Ajax(\'cheques_dent/gerenciar\', \'conteudo\', \'codigo='.$cheque->RetornaDados('codigo').'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>
      </td>
    </tr>
<?php
	}
?>
  </table>
  <br>
  <table class="table">
    <tr>
      <td align="center">
<?php
	$pg_total = ceil(count($total_regs)/PG_MAX);
	$i = $_GET['pg'] - 5;
	if($i <= 1) {
		$i = 1;
		$reti = '';
	} else {
		$reti = '...&nbsp;&nbsp;';
	}
	$j = $_GET['pg'] + 5;
	if($j >= $pg_total) {
		$j = $pg_total;
		$retf = '';
	} else {
		$retf = '...';
	}
	echo $reti;
	while($i <= $j) {
		if($i == $_GET['pg']) {
			echo $i.'&nbsp;&nbsp;';
		} else {
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'cheques_dent/pesquisa\', \'pesquisa\', \'pesquisa=\'+getElementById(\'procurar\').value+\'&campo=\'+getElementById(\'campo\').options[getElementById(\'campo\').selectedIndex].value+\'&pg='.$i.'\')">'.$i.'</a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
    </tr>
  </table>
