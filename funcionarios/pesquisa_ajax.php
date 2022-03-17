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
  <table class="table">
  	<thead>
  		<th><?php echo $LANG['employee']['employee']?></th>
    	<th><?php echo $LANG['employee']['telephone']?></th>
    	<th><?php echo $LANG['employee']['main_function']?></th>
    	<th>Ações</th>
  	</thead>
<?php
    $_GET['pesquisa'] = htmlspecialchars($_GET['pesquisa'], ENT_QUOTES);
	$pacientes = new TFuncionarios();
	if($_GET[campo] == 'nascimento') {
		$where = "MONTH(`nascimento`) = '".$_GET[pesquisa]."' AND ";
	} elseif($_GET[campo] == 'nome') {
		$where = "`nome` LIKE '%".$_GET[pesquisa]."%' AND";
	}elseif($_GET[campo] == 'CPF') {
		$where = "`cpf` = '".$_GET[pesquisa]."' AND";
	}
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$href = 'href=';
	$onclick = 'onclick=';
	if(checknivel('Dentista') || checknivel('Funcionario')) {
		$href = '';
		$onclick = '';
	}
	$sql = "SELECT * FROM `funcionarios` WHERE ".$where." usuario != 'admin' ORDER BY `nome` ASC";
	$lista = $pacientes->ListFuncionarios($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $pacientes->ListFuncionarios($sql);
	$par = $odev = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
		if($lista[$i][ativo] == 'Não') {
			$ativo = '#C0C0C0';
		} else {
			$ativo = '#000000';
		}
		$pacientes->LoadFuncionario($lista[$i][codigo]);
?>
    <tr>
      <td><font color="<?php echo $ativo?>"><?php echo utf8_encode($lista[$i][titulo].' '.$lista[$i][nome])?></td>
      <td><font color="<?php echo $ativo?>"><?php echo $pacientes->RetornaDados('telefone1')?></td>
      <td><font color="<?php echo $ativo?>"><?php echo utf8_encode($lista[$i][funcao1])?></td>
      <td>
      	<?php echo ((verifica_nivel('funcionarios', 'V'))?'<a href="javascript:Ajax(\'funcionarios/incluir\', \'conteudo\', \'codigo='.$lista[$i][codigo].'&acao=editar\')"><button class="btn btn-default" title="Editar"><span class="glyphicon glyphicon-pencil"></span></button></a>':'')?>
      	<?php echo ((verifica_nivel('funcionarios', 'A'))?'<a href="javascript:Ajax(\'funcionarios/gerenciar\', \'conteudo\', \'codigo='.$lista[$i][codigo].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>
  	  </td>
    </tr>
<?php
	}
?>
  </table>
  <br>
  <table class="table">
    <tr bgcolor="#<?php echo $odev?>" onmouseout="style.background='#<?php echo $odev?>'" onmouseover="style.background='#DDE1E6'">
      <td width="160" style="border-top:0;line-height:34px;">
      <?php echo $LANG['employee']['total_employees']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td width="450" align="center" style="border-top:0;line-height:34px;">
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
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'funcionarios/pesquisa\', \'pesquisa\', \'pg='.$i.'&campo='.$_GET['campo'].'&pesquisa='.$_GET['pesquisa'].'\')">'.$i.'</a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td width="140" align="right"><a href="etiquetas/print_etiqueta.php?sql=<?php echo $sql?>" target="_blank"><button class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['employee']['print_labels']?></button></a></td>
    </tr>
  </table>
