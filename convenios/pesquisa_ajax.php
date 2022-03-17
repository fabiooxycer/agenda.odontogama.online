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
      <th><?php echo $LANG['plan']['company']?></th>
      <th><?php echo $LANG['plan']['city_state']?></th>
      <th><?php echo $LANG['plan']['telephone']?></th>
      <th><?php echo $LANG['plan']['fee_table']?></th>
      <th>Ações</th>
    </thead>
<?php
    $_GET['pesquisa'] = utf8_decode ( htmlspecialchars( utf8_encode($_GET['pesquisa']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
	$where = "`".$_GET['campo']."` LIKE '".$_GET['pesquisa']."%'";
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$sql = "SELECT * FROM `convenios` WHERE ".$where." ORDER BY codigo";
	$convenio = new TConvenio();
	$lista = $convenio->ListConvenios($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $convenio->ListConvenios($sql);
	$par = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
?>
    <tr>
      <td><?php echo utf8_encode($lista[$i]['nome']);?></td>
      <td><?php echo (($lista[$i]['codigo'] == '1')?'':utf8_encode($lista[$i]['cidade_uf']));?>&nbsp;</td>
      <td><?php echo (($lista[$i]['codigo'] == '1')?'':utf8_encode($lista[$i]['telefone']));?>&nbsp;</td>
      <td><?php echo ((verifica_nivel('honorarios', 'L'))?'<a href="javascript:Ajax(\'honorarios/honorarios\', \'conteudo\', \'codigo_convenio='.$lista[$i][codigo].'\')"><button class="btn btn-default"><span class=" glyphicon glyphicon-list-alt"></span></button></a>':'')?></td>
      <td>
        <?php echo (($lista[$i]['codigo'] == '1' || !verifica_nivel('convenios', 'V'))?'':'<a href="javascript:Ajax(\'convenios/incluir\', \'conteudo\', \'codigo='.$lista[$i]['codigo'].'&acao=editar\')"><button class="btn btn-default" title="Editar"><span class="glyphicon glyphicon-pencil"></span></button></a>')?>
        <?php echo (($lista[$i]['codigo'] == '1' || !verifica_nivel('convenios', 'A'))?'':'<a href="javascript:Ajax(\'convenios/gerenciar\', \'conteudo\', \'codigo='.$lista[$i]['codigo'].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>')?>
      </td>
    </tr>
<?php
	}
?>
  </table>
  <br>
  <table class="table">
    <tr bgcolor="#<?php echo $odev?>">
      <td width="190">
      <?php echo $LANG['plan']['total_plans']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td width="420" align="center">
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
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'convenios/pesquisa\', \'pesquisa\', \'pg='.$i.'&campo='.$_GET['campo'].'&pesquisa='.$_GET['pesquisa'].'\')">'.$i.'</a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td width="140" align="right"></td>
    </tr>
  </table>
