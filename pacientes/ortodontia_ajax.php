<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	$paciente = new TOrtodontia();
	if(isset($_POST['Salvar'])) {
		$paciente->LoadOrtodontia($_GET['codigo']);
		foreach($_POST as $chave => $valor) {
            if($chave != 'Salvar') {
                $paciente->SetDados($chave, $valor);
            }
		}
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET['codigo'];
	$paciente->LoadOrtodontia($_GET['codigo']);
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET['codigo'], 'nome').' - '.$_GET['codigo'];
	$row = $paciente->RetornaTodosDados();
	$check = array('tratamento');
	foreach($check as $campo) {
		if($row[$campo] == 'Sim') {
			$chk[$campo]['Sim'] = 'checked';
		} else {
			$chk[$campo]['Não'] = 'checked';
		}
	}
	$acao = '&acao=editar';
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();
	}
?>

<div class="panel panel-default">
    <div class="panel-body">
      <?php 
      $ativo_ortodontia = true;
      include('submenu.php'); ?>
    </div>
  </div>

  <div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['patients']['orthodonty']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">

  
  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/ortodontia_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <table class="table">
          
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['has_the_patient_been_under_orthodontic_treatment_before']?>
            </td>
            <td>
              <input name="tratamento" <?php echo $chk['tratamento']['Sim']?> type="radio" <?php echo $disable?> value="Sim" /> <?php echo $LANG['patients']['yes']?>
              <input name="tratamento" <?php echo $chk['tratamento']['Não']?> type="radio" <?php echo $disable?> value="Não" /> <?php echo $LANG['patients']['no']?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['forecast_for_orthodontic_treatment']?>
            </td>
            <td>
              <input name="previsao" value="<?php echo utf8_encode($row['previsao'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['reasons_for_orthodontic_treatment']?>
            </td>
            <td>
              <input name="razoes" value="<?php echo utf8_encode($row['razoes'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['patients_degree_of_motivation']?>
            </td>
            <td>
              <input name="motivacao" value="<?php echo utf8_encode($row['motivacao'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['evaluation_of_profile']?>
            </td>
            <td>
              <input name="perfil" value="<?php echo utf8_encode($row['perfil'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['facial_symmetry']?>
            </td>
            <td>
              <input name="simetria" value="<?php echo utf8_encode($row['simetria'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['patients_facial_type']?>
            </td>
            <td>
              <input name="tipologia" value="<?php echo utf8_encode($row['tipologia'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['patients_dental_class']?>
            </td>
            <td>
              <input name="classe" value="<?php echo utf8_encode($row['classe'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['cross_bite']?>
            </td>
            <td>
              <input name="mordida" value="<?php echo utf8_encode($row['mordida'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['spee_curve']?>
            </td>
            <td>
              <input name="spee" value="<?php echo utf8_encode($row['spee'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['overbite']?>
            </td>
            <td>
              <input name="overbite" value="<?php echo utf8_encode($row['overbite'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['overjet']?>
            </td>
            <td>
              <input name="overjet" value="<?php echo utf8_encode($row['overjet'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['midline']?>
            </td>
            <td>
              <input name="media" value="<?php echo utf8_encode($row['media'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['atm_status']?>
            </td>
            <td>
              <input name="atm" value="<?php echo utf8_encode($row['atm'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['radiographic_analysis']?>
            </td>
            <td colspan="3">
              <textarea name="radio" cols="40" rows="5" class="form-control" <?php echo $disable?>><?php echo utf8_encode($row['radio'])?></textarea>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['model_analysis']?>
            </td>
            <td colspan="3">
              <textarea name="modelo" cols="40" rows="5" class="form-control" <?php echo $disable?>><?php echo utf8_encode($row['modelo'])?></textarea>
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['comments']?>
            </td>
            <td colspan="3">
              <textarea name="observacoes" cols="40" rows="5" class="form-control" <?php echo $disable?>><?php echo utf8_encode($row['observacoes'])?></textarea>
            </td>
          </tr>
        </table>
        </fieldset>
        <br />
        <div align="center"><br />
          <input name="Salvar" type="submit" class="btn btn-success" <?php echo $disable?> id="Salvar" value="<?php echo $LANG['patients']['save']?>" />
        </div>
      </form>      </td>
    </tr>
  </table>
