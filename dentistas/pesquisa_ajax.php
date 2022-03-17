<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=UTF-8", true);
	if(!checklog()) {
		die($frase_log);
	}
?>

<table class="table table-hover">
    <thead>
      <tr>
        <th><?php echo $LANG['professionals']['professional']?></th>
        <th><?php echo $LANG['professionals']['telephone']?></th>
        <!--<th>E-mail</th>-->
        <th>Localidade</th>
        <th><?php echo $LANG['professionals']['council']?></th>
        <th>Ações</th>
      </tr>
    </thead>

<?php
    $_GET['pesquisa'] = htmlspecialchars($_GET['pesquisa'], ENT_QUOTES);
	$dentistas = new TDentistas();
	if($_GET[campo] == 'nascimento') {
		$where = "WHERE MONTH(`nascimento`) = '".$_GET[pesquisa]."'";
	} elseif($_GET[campo] == 'nome') {
		$where = "WHERE `nome` LIKE '%".$_GET[pesquisa]."%'";
	}elseif($_GET[campo] == 'cpf') {
		$where = "WHERE `cpf` = '".$_GET[pesquisa]."'";
	}
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$sql = "SELECT * FROM `dentistas` ".$where." ORDER BY `nome` ASC";
	$lista = $dentistas->ListDentistas($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $dentistas->ListDentistas($sql);
	$par = $odev = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
		if($lista[$i][ativo] == 'Não') {
			$ativo = '#808080';
		} else {
			$ativo = '#000000';
		}
?>
    <tr>
      <td><font color="<?php echo $ativo?>"><?php echo $lista[$i][titulo].' '.$lista[$i][nome]?></font></td>
      <td><font color="<?php echo $ativo?>"><?php echo $lista[$i][telefone]?></font></td>
      
      <!--<td><font color="<?php echo $ativo?>"><?php echo $lista[$i][email]?></font></td>-->
      <td><font color="<?php echo $ativo?>"><?php echo $lista[$i][cidade]." - ".$lista[$i][estado];?></font></td>
      

      <td><font color="<?php echo $ativo?>"><?php echo $lista[$i][conselho_tipo].'/'.$lista[$i][conselho_estado].' '.$lista[$i][conselho_numero]?></td>
      
      <td>
      	<?php echo ((verifica_nivel('profissionais', 'V'))?'<a href="javascript:Ajax(\'dentistas/incluir\', \'conteudo\', \'codigo='.$lista[$i][codigo].'&acao=editar\')"><button class="btn btn-default" title="Editar"><span class="glyphicon glyphicon-pencil"></span></button></a>':'')?>
        <?php echo ((verifica_nivel('profissionais', 'A'))?'<a href="javascript:Ajax(\'dentistas/gerenciar\', \'conteudo\', \'codigo='.$lista[$i][codigo].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>
      </td>
    </tr>
<?php
	}
?>
</table>



<table class="table" style="background:#ECECEC;height:40px;line-height:40px;padding-left:5px;border-radius:3px;">
    <tr>
      <td style="border-top:0;line-height:34px;">
      <?php echo $LANG['professionals']['total_professionals']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td style="border-top:0;line-height:34px;text-align:left;" align="center">
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
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'dentistas/pesquisa\', \'pesquisa\', \'pg='.$i.'&campo='.$_GET['campo'].'&pesquisa='.$_GET['pesquisa'].'\')"><button class="btn btn-default">'.$i.'</button></a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td style="border-top:0;" align="right"><a href="etiquetas/print_etiqueta.php?sql=<?php echo $sql; ?>" target="_blank"><button class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir</button></a></td>
    </tr>
  </table>
	
  
