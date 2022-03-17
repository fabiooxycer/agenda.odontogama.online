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
      <th><?php echo $LANG['useful_telephones']['name']?></th>
      <th><?php echo $LANG['useful_telephones']['telephone']?></th>
      <th>Ações</th>
    </thead>
<?php
    $_GET['pesquisa'] = utf8_decode ( htmlspecialchars( utf8_encode($_GET['pesquisa']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$sql = "SELECT * FROM `telefones` WHERE `nome` LIKE '%$_GET[pesquisa]%' ORDER BY `nome` ASC";
	$telefones = new TTelefones();
	$lista = $telefones->ListTelefones($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $telefones->ListTelefones($sql);
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
      <td><?php echo $lista[$i][telefone1]?></td>
      <td>
        <?php echo ((verifica_nivel('contatos', 'V'))?'<a href="javascript:Ajax(\'telefones/incluir\', \'conteudo\', \'codigo='.$lista[$i][codigo].'&acao=editar\')"><button class="btn btn-default" title="Editar"><span class="glyphicon glyphicon-pencil"></span></button></a>':'')?>
        <?php echo ((verifica_nivel('contatos', 'A'))?'<a href="javascript:Ajax(\'telefones/gerenciar\', \'conteudo\', \'codigo='.$lista[$i][codigo].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>
      </td>
    </tr>
<?php
	}
?>
  </table>
  <br>
  <table class="table">
    <tr>
      <td style="line-hright:35px;">
      <?php echo $LANG['useful_telephones']['total_contacts']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td>
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
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'telefones/pesquisa\', \'pesquisa\', \'pg='.$i.'\')">'.$i.'</a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td align="right"><a href="etiquetas/print_etiqueta.php?sql=<?php echo $sql;?>" target="_blank"><button class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['suppliers']['print_labels']?></button></a></td>
    </tr>
  </table>
