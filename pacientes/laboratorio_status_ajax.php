<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	$sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

  
	if(!checklog()) {
		die($frase_log);
	}
	if($_GET['confirm_del'] == 'delete') {
        mysqli_query($conn, "DELETE FROM laboratorios_procedimentos_status WHERE codigo = ".$_GET['codigo_status']) or die(mysqli_error());
	}
	$r = '';
	if(isset($_POST['Salvar'])) {
        if($_POST['status'] != '') {
            mysqli_query($conn, "INSERT INTO laboratorios_procedimentos_status (codigo_procedimento, status, datahora) VALUES (".$_GET['codigo_procedimento'].", '".utf8_decode ( htmlspecialchars( utf8_encode($_POST['status']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') )."', NOW())");
            $strScrp = 'Ajax("pacientes/laboratorio_status", "conteudo", "codigo='.$_GET['codigo'].'&acao=editar&codigo_procedimento='.$_GET['codigo_procedimento'].'")';
        } else {
            $r = '<font color="#FF0000">';
        }
	}
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$acao = '&acao=editar';
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tlp.*, tl.nomefantasia FROM laboratorios_procedimentos tlp INNER JOIN laboratorios tl ON tlp.codigo_laboratorio = tl.codigo WHERE tlp.codigo = ".$_GET['codigo_procedimento']));
	$codigo_dentista = $row['codigo_dentista'];
?>
<link href="../css/smile.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {color: #FFFFFF}
-->
</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="pacientes/img/pacientes.png" alt="<?php echo $LANG['patients']['manage_patients']?>"> <span class="h3"><?php echo $LANG['patients']['manage_patients']?> &nbsp;[<?php echo $strLoCase?>] </span></td>
    </tr>
  </table>
<div class="conteudo" id="table dados">
<br />
<?phpinclude('submenu.php')?>
<br>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela_titulo">
    
    <tr>
      <td height="26">&nbsp;<?php echo $LANG['patients']['laboratory_materials']?></td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
        <form id="form2" name="form2" method="POST" action="pacientes/laboratorio_status_ajax.php?acao=editar&codigo=<?php echo $_GET['codigo']?>&codigo_procedimento=<?php echo $_GET['codigo_procedimento']?>" onsubmit="formSender(this, 'conteudo'); return false;">
          <fieldset>
            <legend><?php echo $LANG['patients']['procedure']?></legend>
            <table width="98%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="3%"></td>
                <td width="94%">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="40%" valign="top"><?php echo $LANG['patients']['procedure']?>:<br />
                      <?php echo $row['procedimento']?></td>
                      <td width="30%" valign="top"><?php echo $LANG['patients']['laboratory']?>:<br />
                      <?php echo $row['nomefantasia']?></td>
                      <td width="30%" valign="top"><?php echo $LANG['patients']['date']?>:<br />
                      <?php echo converte_datahora(substr($row['datahora'], 0, 16), 2)?></td>
                    </tr>
                  </table>
                </td>
                <td width="3%"></td>
              </tr>
            </table>
          </fieldset><br />
          <fieldset>
            <legend><?php echo $LANG['patients']['status']?></legend>
            <table width="98%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="3%"></td>
                <td width="94%">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="85%"><?php echo $r?> *<?php echo $LANG['patients']['status']?>:<br />
                      <input type="text" class="forms" size="80" name="status" id="status" /></td>
                      <td width="15%" valign="bottom"><input type="submit" name="Salvar" value="<?php echo $LANG['patients']['save']?>" class="forms" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td width="3%"></td>
              </tr>
            </table>
          </fieldset><br />
          <table width="98%" border="0" cellpadding="2" cellspacing="0" align="center">
            <tr bgcolor="#009BE6">
              <td width="60%">&nbsp;</td>
              <td width="30%">&nbsp;</td>
              <td width="10%">&nbsp;</td>
            </tr>
            <tr class="tabela_titulo" height="23">
              <td><?php echo $LANG['patients']['status']?></td>
              <td><?php echo $LANG['patients']['date']?></td>
              <td><?php echo $LANG['patients']['delete']?></td>
            </tr>
<?php
    $query = mysqli_query($conn, "SELECT * FROM laboratorios_procedimentos_status WHERE codigo_procedimento = ".$_GET['codigo_procedimento']." ORDER BY datahora DESC");
    while($row = mysqli_fetch_assoc($query)) {
        if($_SESSION['codigo'] == $codigo_dentista) {
            $delete = '<a href="javascript:Ajax(\'pacientes/laboratorio_status\', \'conteudo\', \'codigo='.$_GET['codigo'].'&acao=editar&codigo_procedimento='.$_GET['codigo_procedimento'].'&codigo_status='.$row['codigo'].'" onclick="return confirmLink(this)"><img src="imagens/icones/excluir.gif" border="0"></a>';
        }
?>
            <tr>
              <td><input type="text" class="forms" size="50" name="status_novo[]" value="<?php echo $row['status']?>" id="status_novo[]"  onblur="Ajax('pacientes/atualiza_status', 'lab_atualiza', 'codigo=<?php echo $row['codigo']?>&status='%2Bthis.value)"></td>
              <td><?php echo converte_datahora(substr($row['datahora'], 0, 16), 2)?></td>
              <td align="center"><?php echo $delete?></td>
            </tr>
<?php
    }
?>
          </table><br />
      </form>
      </td>
    </tr>
  </table>
  <div id="lab_atualiza">&nbsp;</div>
