<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
  
	if(!checklog()) {
		die($frase_log);
	}
	$paciente = new TExObjetivo();
	if(isset($_POST[Salvar])) {	
		$paciente->LoadExObjetivo($_GET[codigo]);
		//$strScrp = "Ajax('pacientes/objetivos', 'conteudo', '')";
		$paciente->SetDados('pressao', $_POST[pressao]);
		$paciente->SetDados('peso', $_POST[peso]);
		$paciente->SetDados('altura', $_POST[altura]);
		$paciente->SetDados('edema', $_POST[edema]);
		$paciente->SetDados('face', $_POST[face]);
		$paciente->SetDados('atm', $_POST[atm]);
		$paciente->SetDados('linfonodos', $_POST[linfonodos]);
		$paciente->SetDados('labio', $_POST[labio]);
		$paciente->SetDados('mucosa', $_POST[mucosa]);
		$paciente->SetDados('soalhobucal', $_POST[soalhobucal]);
		$paciente->SetDados('palato', $_POST[palato]);
		$paciente->SetDados('orofaringe', $_POST[orofaringe]);
		$paciente->SetDados('lingua', $_POST[lingua]);
		$paciente->SetDados('gengiva', $_POST[gengiva]);
		$paciente->SetDados('higienebucal', $_POST[higienebucal]);
		$paciente->SetDados('habitosnocivos', $_POST[habitosnocivos]);
		$paciente->SetDados('aparelho', $_POST[aparelho]);
		$paciente->SetDados('lesaointra', $_POST[lesaointra]);
		$paciente->SetDados('observacoes', $_POST[observacoes]);
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$paciente->LoadExObjetivo($_GET[codigo]);
	$row = $paciente->RetornaTodosDados();
	if($row[aparelho] == 'Sim') {
		$chk[aparelho][sim] = 'checked';
	} else {
		$chk[aparelho][nao] = 'checked';
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
        <form id="form2" name="form2" method="POST" action="pacientes/objetivo_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo $LANG['patients']['manage_patients']?> &nbsp;[<?php echo $strLoCase?>] </div>
    <div class="panel-body">
  <table class="table">
    
    <tr>
      <td height="26">&nbsp;<?php echo $LANG['patients']['objective_examination']?> </td>
    </tr>
  </table>
  <table class="table">
    <tr>
      <td>
        <table class="table">
          
          <tr>
            <td><?php echo $LANG['patients']['arterial_pressure']?><br>
            <input name="pressao" value="<?php echo $row[pressao]?>" type="text" class="form-control" <?php echo $disable?> /></td>
            <td><?php echo $LANG['patients']['oral_floor']?><br>
            <input name="soalhobucal" value="<?php echo utf8_encode($row[soalhobucal])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          
            <td><?php echo $LANG['patients']['weight']?><br>
            <input name="peso" value="<?php echo $row[peso]?>" type="text" class="form-control" <?php echo $disable?> /></td>
            </tr>
          <tr>
            <td><?php echo $LANG['patients']['palate']?><br>
            <input name="palato" value="<?php echo utf8_encode($row[palato])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          
            <td><?php echo $LANG['patients']['height']?><br>
            <input name="altura" value="<?php echo $row[altura]?>" type="text" class="form-control" <?php echo $disable?> /></td>
            <td><?php echo $LANG['patients']['oropharynx']?><br>
            <input name="orofaringe" value="<?php echo utf8_encode($row[orofaringe])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['patients']['edema']?><br>
            <input name="edema" value="<?php echo utf8_encode($row[edema])?>" type="text" class="form-control" <?php echo $disable?> /></td>
            <td><?php echo $LANG['patients']['tongue']?><br>
            <input name="lingua" value="<?php echo utf8_encode($row[lingua])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          
            <td><?php echo $LANG['patients']['face']?><br>
            <input name="face" value="<?php echo utf8_encode($row[face])?>" type="text" class="form-control" <?php echo $disable?> /></td>
            </tr>
          <tr>
            <td><?php echo $LANG['patients']['gingiva']?><br>
            <input name="gengiva" value="<?php echo utf8_encode($row[gengiva])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          
            <td><?php echo $LANG['patients']['atm']?><br>
            <input name="atm" value="<?php echo $row[atm]?>" type="text" class="form-control" <?php echo $disable?> /></td>
            <td><?php echo $LANG['patients']['oral_hygiene']?><br>
            <input name="higienebucal" value="<?php echo utf8_encode($row[higienebucal])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['patients']['linfonodes']?><br>
            <input name="linfonodos" value="<?php echo utf8_encode($row[linfonodos])?>" type="text" class="form-control" <?php echo $disable?> /></td>
            <td><?php echo $LANG['patients']['harmful_habits']?><br>
            <input name="habitosnocivos" value="<?php echo utf8_encode($row[habitosnocivos])?>" type="text" class="form-control" <?php echo $disable?> /></td>
          
            <td><?php echo $LANG['patients']['lips']?><bR>
            <input name="labio" value="<?php echo utf8_encode($row[labio])?>" type="text" class="form-control" <?php echo $disable?> /></td>
            </tr>
          
          <tr>
            <td><?php echo $LANG['patients']['mucus']?><bR>
            <input name="mucosa" value="<?php echo utf8_encode($row[mucosa])?>" type="text" class="form-control" <?php echo $disable?> /></td>
            <td><?php echo $LANG['patients']['intra_oral_lesion']?><br>
            <input name="lesaointra" value="<?php echo utf8_encode($row[lesaointra])?>" type="text" class="form-control" <?php echo $disable?> /></td>
           
            <td><?php echo $LANG['patients']['bearer_of_orthodontic_or_prosthetic_apparatus']?><br>
            <input name="aparelho"  type="radio" <?php echo $disable?> <?php echo $chk[aparelho][sim]?> value="Sim" />
<?php echo $LANG['patients']['yes']?>
  <input name="aparelho" type="radio" <?php echo $disable?> <?php echo $chk[aparelho][nao]?> value="Não" />
<?php echo $LANG['patients']['no']?></td>
          
          </tr>
          <tr>
            <td colspan="3"><?php echo $LANG['patients']['comments']?>:<br>
            <textarea name="observacoes" cols="40" rows="5" class="form-control" <?php echo $disable?>><?php echo utf8_encode($row[observacoes]);?></textarea></td>
          </tr>
        </table>
        </fieldset>
        <br />
        <div align="center"><br />
<button name="Salvar" type="submit" class="btn btn-primary" id="Salvar" onclick="alert('Salvo com sucesso!');" <?php echo $disable2?>><span class=" glyphicon glyphicon-ok"></span> <?php echo $LANG['employee']['save']?></button>        </div>
            </td>
    </tr>
  </table>
</div></div>
  </form>
