<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	if(!checklog()) {
		die($frase_log);
	}
?>

<table class="table table-hover">
  <thead>
    <th>Código</th>
    <th>Descrição</th>
    <th>Setor</th>
    <th>Valor</th>
    <th>Ação</th>
 </thead>

<?php
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$sql = "SELECT * FROM `patrimonio` WHERE `descricao` LIKE '%$_GET[pesquisa]%' ORDER BY `descricao` ASC";
	$patrimonio = new TPatrimonios();
	$lista = $patrimonio->ListPatrimonio($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $patrimonio->ListPatrimonio($sql);
	$par = "F0F0F0";
	$impar = "F8F8F8";
	$saldo = 0;
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
		$saldo += $lista[$i][valor];
?>


 <tr>
    <td><?php echo $lista[$i][codigo]?></td>
    <td><?php echo $lista[$i][descricao]?></td>
    <td><?php echo $lista[$i][setor]?></td>
    <td><?php echo $LANG['general']['currency'].' '.money_form($lista[$i][valor])?></td>
    <td>
      <?php echo ((verifica_nivel('patrimonio', 'V'))?'<a href="javascript:Ajax(\'patrimonio/incluir\', \'conteudo\', \'codigo='.$lista[$i][codigo].'&acao=editar\')"><button class="btn btn-default"><span class="glyphicon glyphicon-pencil" style="color:#333;"></span></button></a>':'')?>
      <?php echo ((verifica_nivel('patrimonio', 'A'))?'<a href="javascript:Ajax(\'patrimonio/gerenciar\', \'conteudo\', \'codigo='.$lista[$i][codigo].'" onclick="return confirmLink(this)"><button class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>
    </td>
 </tr>

<?php
	}
	if($odev == $impar) {
		$odev = $par;
	} else {
		$odev = $impar;
	}	
?>
  </table>
    
  <table class="table">
    <tr>
      <td><b><?php echo strtoupper($LANG['patrimony']['total']); ?>: </b>
      <font color="#<?php echo $cor?>"><b><?php echo $LANG['general']['currency'].' '.money_form($saldo)?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>

  </table>

  <br>
  <table class="table">
    <tr bgcolor="#<?php echo $odev?>" onmouseout="style.background='#<?php echo $odev?>'" onmouseover="style.background='#DDE1E6'">
      <td width="160">
      <?php echo $LANG['patrimony']['total_items']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td width="450" align="center">
<?php
	$pg_total = ceil(count($total_regs)/PG_MAX);
	$i = $_GET[pg] - 5;
	if($i <= 1) {
		$i = 1;
		$reti = '';
	} else {
		$reti = '...&nbsp;&nbsp;';
	}
	$j = $_GET[pg] + 5;
	if($j >= $pg_total) {
		$j = $pg_total;
		$retf = '';
	} else {
		$retf = '...';
	}
	echo $reti;
	while($i <= $j) {
		if($i == $_GET[pg]) {
			echo $i.'&nbsp;&nbsp;';
		} else {
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'patrimonio/pesquisa\', \'pesquisa\', \'pg='.$i.'\')">'.$i.'</a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td width="140" align="right"></td>
    </tr>
  </table>
  <div id="conta_atualiza"></div>
