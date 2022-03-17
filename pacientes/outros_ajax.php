<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	$paciente = new TPacientes();

	$strUpCase = "ALTERAÇÂO";
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$paciente->LoadPaciente($_GET[codigo]);
	$row = $paciente->RetornaTodosDados();
	$row[nascimento] = converte_data($row[nascimento], 2);
	$row[nascimentomae] = converte_data($row[nascimentomae], 2);
	$row[nascimentopai] = converte_data($row[nascimentopai], 2);
	$row[datacadastro] = converte_data($row[datacadastro], 2);
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
  <div class="panel-heading"><b>Outros - <?php echo $strLoCase?></b> </div>
  <div class="panel-body">

  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <p align="left">
          <br />
          <ul>
            <li><a href="relatorios/agenda.php?codigo=<?php echo $_GET['codigo']?>" target="_blank"><?php echo $LANG['patients']['report_of_consultations_scheduled']?></a><br />&nbsp;</li>
<?php
	if(checknivel('Dentista')) {
?>
            <li><a href="relatorios/receita.php?codigo=<?php echo $_GET['codigo']?>&acao=editar" target="_blank"><?php echo $LANG['patients']['print_recipe']?></a><br />&nbsp;</li>
            <li><a href="relatorios/atestado.php?codigo=<?php echo $_GET['codigo']?>&acao=editar" target="_blank"><?php echo $LANG['patients']['print_certificate']?></a><br />&nbsp;</li>
            <li><a href="relatorios/exame.php?codigo=<?php echo $_GET['codigo']?>&acao=editar" target="_blank"><?php echo $LANG['patients']['print_request_for_examination']?></a><br />&nbsp;</li>
            <li><a href="relatorios/encaminhamento.php?codigo=<?php echo $_GET['codigo']?>&acao=editar" target="_blank"><?php echo $LANG['patients']['print_routing']?></a><br />&nbsp;</li>
            <li><a href="relatorios/laudo.php?codigo=<?php echo $_GET['codigo']?>&acao=editar" target="_blank"><?php echo $LANG['patients']['print_dental_opinion']?></a><br />&nbsp;</li>
            <li><a href="relatorios/agradecimento.php?codigo=<?php echo $_GET['codigo']?>&acao=editar" target="_blank"><?php echo $LANG['patients']['print_thanks_for_routing']?></a><br />&nbsp;</li>
            <li><a href="javascript:Ajax('pacientes/laboratorio', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&acao=editar');"><?php echo $LANG['patients']['laboratory_materials']?></a><br />&nbsp;</li>
<?php
	}
?>
          </ul>
  <br />
        </p>
        </fieldset>
        <br />
        <div align="center"></div>
      </form>      </td>
    </tr>
  </table>
