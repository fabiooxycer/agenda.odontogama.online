<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	$caixa_dent = new TCaixa('caixa_dent');
	if(isset($_POST[Salvar])) {		
		$senha = mysql_fetch_array(mysql_query("SELECT * FROM `dentistas` WHERE `codigo` = '".$_SESSION[codigo]."'"));
		$obrigatorios[1] = 'data';
		$obrigatorios[] = 'descricao';
		$obrigatorios[] = 'dc';
		$obrigatorios[] = 'valor';
		$obrigatorios[] = 'codigo_dentista';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
			    $j++;
				$r[$j] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			$caixa_dent->SalvarNovo();
			$caixa_dent->SetDados('codigo_dentista', $_SESSION[codigo]);
			$caixa_dent->SetDados('data', converte_data($_POST[data], 1));
			$caixa_dent->SetDados('descricao', $_POST[descricao]);
			$caixa_dent->SetDados('dc', $_POST[dc]);
			$caixa_dent->SetDados('valor', $_POST[valor]);
			$caixa_dent->Salvar();
		}
	}
?>
  <table class="table table-hover table-bordered">
  	<thead>
  	  <th align="left"><?php echo $LANG['cash_flow']['date']?></th>
      <th align="left"><?php echo $LANG['cash_flow']['description']?></th>
      <th align="center"><?php echo $LANG['cash_flow']['debit']?></th>
      <th align="center"><?php echo $LANG['cash_flow']['credit']?></th>
      <th align="center"><?php echo $LANG['cash_flow']['total']?></th>
      <th align="center"><?php echo $LANG['patients']['delete']?></th>
  	</thead>
  	<tbody>
<?php
	$lista = $caixa_dent->ListCaixa("SELECT * FROM `caixa_dent` WHERE `codigo_dentista` = '".$_SESSION[codigo]."' ORDER BY `data` DESC,  `codigo` DESC LIMIT 9");
	$par = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < 9; $i++) {
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
			$saldo = $caixa_dent->SaldoTotal($_SESSION[cpf]);
			for($j = $i-1; $j >= 0; $j--) {
				if($lista[$j][dc] == '-') {
					$saldo += $lista[$j][valor];
				} else {
					$saldo -= $lista[$j][valor];
				}
			}
?>
    <tr>
      <td align="left"><?php echo converte_data($lista[$i][data], 2)?></td>
      <td align="left"><?php echo utf8_encode($lista[$i][descricao]);?></td>
      <td align="left"><?php echo $debito?></td>
      <td align="left"><?php echo $credito?></td>
      <td align="left"></td>
      <td align="center"><?php echo ((verifica_nivel('caixa', 'A'))?'<a href="javascript:Ajax(\'caixa_dent/extrato\', \'conteudo\', \'codigo='.$lista[$i]['codigo'].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?></td>
    </tr>
</tbody>
<?php
		}
	}
?>
  </table>
