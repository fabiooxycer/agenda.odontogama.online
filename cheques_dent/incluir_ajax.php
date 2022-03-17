<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}

	if(($_GET['codigo'] != '' && !verifica_nivel('cheques', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('cheques', 'I'))) {
        $disabled = 'disabled';
    }
	$cheque = new TCheques('dentista');
	if(isset($_POST[Salvar])) {
		$obrigatorios[1] = 'recebidode';
		$obrigatorios[] = 'valor';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
			    $j++;
				$r[$i] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			if($_GET[acao] == "editar") {
				$cheque->LoadCheque($_GET[codigo]);
			}
			$cheque->SetDados('codigo_dentista', $_SESSION[codigo]);
			$cheque->SetDados('nometitular', $_POST[nometitular]);
			$cheque->SetDados('valor', $_POST[valor]);
			$cheque->SetDados('numero', $_POST[numero]);
            $cheque->SetDados('banco', $_POST[banco]);
            $cheque->SetDados('agencia', $_POST[agencia]);
			$cheque->SetDados('recebidode', $_POST[recebidode]);
			$cheque->SetDados('encaminhadopara', $_POST[encaminhadopara]);
			$cheque->SetDados('compensacao', converte_data($_POST[compensacao], 1));
			if($_GET[acao] != "editar") {
				$cheque->SalvarNovo();
			}
			$cheque->Salvar();
			echo "<script>Ajax('cheques_dent/gerenciar', 'conteudo', '')</script>";
		}
	}
	if($_GET[acao] == "editar") {
		$strLoCase = $LANG['check_control']['editing'];
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$cheque->LoadCheque($_GET[codigo]);
		$row = $cheque->RetornaTodosDados();
		$row[senha_dentista] = $_REQUEST[senha_dentista];
	} else {
		if($j == 0) {
			$row = "";
		} else {
			$row = $_POST;
		}
		$row[codigo_dentista] = $_REQUEST[codigo_dentista];
		$row[senha_dentista] = $_REQUEST[senha_dentista];
		$strLoCase = $LANG['check_control']['including'];
		$senha = mysql_fetch_array(mysql_query("SELECT * FROM `dentistas` WHERE `codigo` = '".$_REQUEST[codigo_dentista]."'"));

	}
?>
<div class="panel panel-default">
  <div class="panel-heading"><span class="  glyphicon glyphicon-plus"></span> Incluir novo cheque</div>
  <div class="panel-body">
 
<div class="conteudo" id="table dados"><br>

  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="cheques_dent/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><span class="style1"><?php echo $LANG['check_control']['check_information']?> </span></legend>
        <table class="table">
          
          <tr>
            <td><?php echo $LANG['check_control']['holder']?> <br />
                
                  <input name="nometitular" value="<?php echo utf8_encode($row[nometitular])?>" <?php echo $disabled?> type="text" class="form-control" id="nometitular" size="50" maxlength="80" />
                
            </td>
            <td><?php echo $r[2]?>* <?php echo $LANG['check_control']['value']?><br />
              <input name="valor" value="<?php echo $row[valor]?>" <?php echo $disabled?> type="text" class="form-control" id="valor" onKeypress="return Ajusta_Valor(this, event);" /></td>
          
            <td><?php echo $LANG['check_control']['check_number']?><br />
              <input name="numero" value="<?php echo $row[numero]?>" <?php echo $disabled?> type="text" class="form-control" id="numero" size="20" maxlength="150" /></td>
            </tr>
            <tr>
            <td><?php echo $LANG['check_control']['bank']?><br />
              <input name="banco" value="<?php echo utf8_encode($row[banco])?>" <?php echo $disabled?> type="text" class="form-control" id="banco" /></td>
            <td height="40"><?php echo $LANG['check_control']['agency_number']?>:<br />
                <input name="agencia" value="<?php echo converte_data($row[agencia], 2)?>" <?php echo $disabled?> type="text" class="form-control" id="agencia" size="20" maxlength="20" />
            </td>
            <td><?php echo $LANG['check_control']['compensation_date']?>:<br />
                <input name="compensacao" value="<?php echo converte_data($row[compensacao], 2)?>" <?php echo $disabled?> type="text" class="form-control" id="compensacao" size="14" maxlength="50" onKeypress="return Ajusta_Data(this, event);" />
            </td>
          </tr>
          <tr>
            <td height="40"><?php echo $r[5]?>* <?php echo $LANG['check_control']['received_from']?>:<br />
                <input name="recebidode" value="<?php echo utf8_encode($row[recebidode])?>" <?php echo $disabled?> type="text" class="form-control" id="recebidode" size="40" maxlength="50" />
            </td>
            <td><?php echo $LANG['check_control']['forwarded_to']?>:<br />
                <input name="encaminhadopara" value="<?php echo utf8_encode($row[encaminhadopara])?>" <?php echo $disabled?> type="text" class="form-control" id="encaminhadopara" size="40" maxlength="50" />
            </td>
          </tr>
        </table>
        </fieldset>
    <br />
        <div align="center"><br />
          <button name="Salvar" type="submit" class="btn btn-success" id="Salvar" <?php echo $disabled?>><span class="  glyphicon glyphicon-ok"></span> <?php echo $LANG['check_control']['save']?></button>
        </div>
      </form>      </td>
    </tr>
  </table>
</div>

