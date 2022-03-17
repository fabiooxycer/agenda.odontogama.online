<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	$paciente = new TImplantodontia();
	if(isset($_POST['Salvar'])) {
		$paciente->LoadImplantodontia($_GET['codigo']);
		foreach($_POST as $chave => $valor) {
            if($chave != 'Salvar') {
                $paciente->SetDados($chave, $valor);
            }
		}
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET['codigo'];
	$paciente->LoadImplantodontia($_GET['codigo']);
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET['codigo'], 'nome').' - '.$_GET['codigo'];
	$row = $paciente->RetornaTodosDados();
	$check = array('tratamento', 'enxerto');
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
      <?php include('submenu.php'); ?>
    </div>
  </div>

  <div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['patients']['implantodonty']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">

  
  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/implantodontia_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <table class="table">
          
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['has_the_patient_an_implant']?>
            </td>
            <td>
              <input name="tratamento" <?php echo $chk['tratamento']['Sim']?> type="radio" value="Sim" <?php echo $disable?> /> <?php echo $LANG['patients']['yes']?>
              <input name="tratamento" <?php echo $chk['tratamento']['Não']?> type="radio" value="Não" <?php echo $disable?> /> <?php echo $LANG['patients']['no']?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['if_yes_in_which_region']?>
            </td>
            <td>
              <input name="regioes" value="<?php echo utf8_encode($row['regioes'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['patients_expectations_regarding_the_treatment_of_implant']?>
            </td>
            <td>
              <input name="expectativa" value="<?php echo utf8_encode($row['expectativa'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['areas_the_implant_must_be_done']?>
            </td>
            <td>
              <input name="areas" value="<?php echo utf8_encode($row['areas'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['brand_and_size_of_the_implant']?>
            </td>
            <td>
              <input name="marca" value="<?php echo utf8_encode($row['marca'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $LANG['patients']['need_to_graft_the_region_to_be_implanted']?>
            </td>
            <td>
              <input name="enxerto" <?php echo $chk['enxerto']['Sim']?> type="radio" value="Sim" <?php echo $disable?> /> <?php echo $LANG['patients']['yes']?>
              <input name="enxerto" <?php echo $chk['enxerto']['Não']?> type="radio" value="Não" <?php echo $disable?> /> <?php echo $LANG['patients']['no']?>
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?php echo $LANG['patients']['kind_of_graft_to_be_performed']?>
            </td>
            <td>
              <input name="tipoenxerto" value="<?php echo utf8_encode($row['tipoenxerto'])?>" size="40" type="text" class="form-control" <?php echo $disable?> />
            </td>
          </tr>
          <tr>
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
          <input name="Salvar" type="submit" class="btn btn-success" id="Salvar" value="<?php echo $LANG['patients']['save']?>" <?php echo $disable?> />
        </div>
      </form>      </td>
    </tr>
  </table>
