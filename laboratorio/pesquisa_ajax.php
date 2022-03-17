<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
?>
  <table class="table table-hover">
    <thead>
      <th><?php echo $LANG['laboratory']['company']?></th>
      <th><?php echo $LANG['laboratory']['city_state']?></th>
      <th><?php echo $LANG['laboratory']['telephone']?></th>
      <th>Ações</th>
    </thead>
<?php
    $_GET['pesquisa'] = htmlspecialchars($_GET['pesquisa'], ENT_QUOTES);
	$where = "`".$_GET['campo']."` LIKE '".$_GET['pesquisa']."%'";
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$sql = "SELECT * FROM `laboratorios` WHERE ".$where." ORDER BY `nomefantasia` ASC";
	$pacientes = new TLaboratorio();
	$lista = $pacientes->ListLaboratorios($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $pacientes->ListLaboratorios($sql);
	$par = $odev = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
?>
    <tr>
      <td><?php echo utf8_encode($lista[$i][nome]);?></td>
      <td ><?php echo utf8_encode($lista[$i][cidade_uf]);?></td>
      <td><?php echo utf8_encode($lista[$i][telefone]);?></td>
      <td>
        <?php echo ((verifica_nivel('laboratorios', 'V'))?'<a href="javascript:Ajax(\'laboratorio/incluir\', \'conteudo\', \'codigo='.$lista[$i][codigo].'&acao=editar\')"><button class="btn btn-default" title="Editar"><span class="glyphicon glyphicon-pencil"></span></button></a>':'')?>
        <?php echo ((verifica_nivel('laboratorios', 'A'))?'<a href="javascript:Ajax(\'laboratorio/gerenciar\', \'conteudo\', \'codigo='.$lista[$i][codigo].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>
      </td>
    </tr>
<?php
	}
?>
  </table>
  <br>
  <table class="table">
    <tr bgcolor="#<?php echo $odev?>">
      <td width="160">
      <?php echo $LANG['laboratory']['total_laboratories']?>: <b><?php echo count($total_regs)?></b>
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
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'laboratorio/pesquisa\', \'pesquisa\', \'pg='.$i.'&campo='.$_GET['campo'].'&pesquisa='.$_GET['pesquisa'].'\')">'.$i.'</a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td width="140" align="right"></td>
    </tr>
  </table>
