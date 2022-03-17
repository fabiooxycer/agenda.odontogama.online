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
  <table class="table table-hover table-bordered">
    <thead>
      <th align="left"><?php echo $LANG['fee_table']['code']?></th>
      <th align="center"><?php echo $LANG['fee_table']['procedure']?></th>
      <th align="center"><?php echo $LANG['fee_table']['private']?></th>
      <th align="center"><?php echo $LANG['fee_table']['plan']?></th>
      <th colspan="2" align="center"><?php echo $LANG['fee_table']['difference']?></th>
      <th align="center"><?php echo $LANG['fee_table']['delete']?></th>
    </thead>
    <tbody>
<?php
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX_MEN;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
    switch($_GET['campo']) {
        case 'area' : $sql = "SELECT * FROM honorarios WHERE LEFT(codigo, 2) = '".$_GET['pesquisa']."' ORDER BY codigo ASC";
            break;
        case 'codigo' : $sql = "SELECT * FROM honorarios WHERE ".$_GET['campo']." = '".$_GET['pesquisa']."' ORDER BY codigo ASC";
            break;
        default : $sql = "SELECT * FROM honorarios WHERE ".$_GET['campo']." LIKE '%".$_GET['pesquisa']."%' ORDER BY codigo ASC";
            break;
    }

	$conta = new THonorarios('clinica');
	$lista = $conta->Consulta($sql.' LIMIT '.$limit.', '.PG_MAX_MEN);
	$total_regs = $conta->Consulta($sql);
	$par = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
		$conta->LoadInfo($lista[$i]['codigo']);
		$valor_particular = encontra_valor('honorarios_convenios', 'codigo_convenio = 1 AND codigo_procedimento', $conta->RetornaDados('codigo'), 'valor');
		$valor_convenio = encontra_valor('honorarios_convenios', 'codigo_convenio = '.$_GET['codigo_convenio'].' AND codigo_procedimento', $conta->RetornaDados('codigo'), 'valor');
?>
    <tr>
      <td align="left"><?php echo $conta->RetornaDados('codigo')?></td>
      <td align="center"><input type="text" <?php echo ((!verifica_nivel('honorarios', 'E'))?'disabled':'')?> class="form-control" size="70" name="procedimento" id="procedimento" value="<?php echo utf8_encode($conta->RetornaDados('procedimento'))?>" onblur="Ajax('honorarios/atualiza', 'conta_atualiza', 'codigo=<?php echo $conta->RetornaDados('codigo')?>&procedimento='+this.value)"></td>
      <td align="center"><input type="text" <?php echo (($_GET['codigo_convenio'] != '1' || !verifica_nivel('honorarios', 'E'))?'disabled':'')?> class="form-control" size="8" name="valor_particular" id="valor_particular" value="<?php echo number_format($valor_particular, 2, '.', '')?>" onblur="Ajax('honorarios/atualiza', 'conta_atualiza', '&codigo_convenio=1&codigo=<?php echo $conta->RetornaDados('codigo')?>&valor='+this.value)" onKeypress="return Ajusta_Valor(this, event);"></td>
      <td align="center"><?php echo (($_GET['codigo_convenio'] != '1')?'<input type="text" '.((!verifica_nivel('honorarios', 'E'))?'disabled':'').' class="form-control" size="8" name="valor_convenio"   id="valor_convenio"   value="'.number_format($valor_convenio, 2, '.', '').'"   onblur="Ajax(\'honorarios/atualiza\', \'conta_atualiza\', \'&codigo_convenio='.$_GET['codigo_convenio'].'&codigo='.$conta->RetornaDados('codigo').'&valor=\'+this.value)" onKeypress="return Ajusta_Valor(this, event);">':'')?></td>
      <td align="right"><?php echo (($_GET['codigo_convenio'] != '1')?$LANG['general']['currency'].' '.@number_format($valor_particular - $valor_convenio, 2, ',', '.'):'')?></td>
      <td align="right"><?php echo (($_GET['codigo_convenio'] != '1')?@number_format(round(100 - ($valor_convenio / $valor_particular) * 100, 2), 2, ',', '.').' %':'')?></td>
      <td align="center"><?php echo ((verifica_nivel('honorarios', 'A'))?'<a href="javascript:Ajax(\'honorarios/honorarios\', \'conteudo\', \'codigo_convenio='.$_GET['codigo_convenio'].'&codigo='.$conta->RetornaDados('codigo').'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?></td>
    </tr>
<?php
	}
?>
</tbody>
  </table>
  <br>
  <table class="table">
    <tr bgcolor="#<?php echo $odev?>">
      <td>
      <?php echo $LANG['fee_table']['total_procedures']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td align="center">
<?php
	$pg_total = ceil(count($total_regs)/PG_MAX_MEN);
	$i = $_GET[pg] - 5;
	if($i <= 1) {
		$i = 1;
		$reti = '';
	} else {
		$reti = '<button class="btn btn-default">...</button>';
	}
	$j = $_GET[pg] + 5;
	if($j >= $pg_total) {
		$j = $pg_total;
		$retf = '';
	} else {
		$retf = '<button class="btn btn-default">...</button>';
	}
  echo'<div class="btn-group">';
	echo $reti;
  
	while($i <= $j) {
		if($i == $_GET[pg]) {
			echo '<button class="btn btn-default">'.$i.'</button>';
		} else {
			echo '<button onclick="javascript:Ajax(\'honorarios/pesquisa\', \'pesquisa\', \'codigo_convenio='.$_GET['codigo_convenio'].'&pesquisa=\'+document.getElementById(document.getElementById(\'id_procurar\').value).value+\'&campo=\'+getElementById(\'campo\').options[getElementById(\'campo\').selectedIndex].value+\'&pg='.$i.'\')" class="btn btn-default">'.$i.'</button>';
		}
		$i++;
	}
 
	echo $retf;
  echo"</div>";
?>
      </td>
      <td align="right"><a href="relatorios/honorarios.php?codigo_convenio=<?php echo $_GET['codigo_convenio']?>&sql=<?php echo $sql?>" target="_blank"><button class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['fee_table']['print_report']?></button></a></td>
    </tr>
  </table>
  <div id="conta_atualiza"></div>
