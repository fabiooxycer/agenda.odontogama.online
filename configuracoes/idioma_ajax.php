<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('idiomas', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if(!verifica_nivel('idiomas', 'E')) {
		$disable = 'disabled';
	}
	$clinica = new TClinica();
    $clinica->LoadInfo();
	if(isset($_POST['Salvar'])) {
        $clinica->LoadInfo();
        $clinica->Idioma = $_POST['idioma'];
	    $clinica->Salvar();
	    $strScrp = 'alert("'.$LANG['language']['data_successfully_recorded'].'"); location.href="./"';
    }
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>
<div class="conteudo" id="conteudo_central">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="56%">&nbsp;&nbsp;&nbsp;<img src="configuracoes/img/clinica.png" alt="<?php echo $LANG['language']['language']?>"> <span class="h3"><?php echo $LANG['clinic_information']['clinic_information']?> </span></td>
      <td width="6%" valign="bottom"></td>
      <td width="36%" valign="bottom" align="right">&nbsp;</td>
      <td width="2%" valign="bottom">&nbsp;</td>
    </tr>
  </table>
<div class="conteudo" id="table dados"><br>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela_titulo">
    <tr>
      <td width="243" height="26"><?php echo $LANG['language']['language']?> </td>
      <td width="381">&nbsp;</td>
    </tr>
  </table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="configuracoes/idioma_ajax.php" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><span class="style1"><?php echo $LANG['language']['choose_your_language']?></span></legend>
        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="287"><?php echo $LANG['language']['language']?> <br />
                  <select name="idioma" <?php echo $disable?> class="forms" id="idioma">
<?php
	$handle = opendir('../lang');
	while ($file = readdir($handle)) {
		if(strpos($file, '.') !== 0) {
			$nome_file = explode('.', $file);
			echo '                    <option value="'.$nome_file[0].'" '.(($nome_file[0] == $idioma)?'selected':'').'>'.$nome_file[0].'</option>'."\n";
		}
	}
?>
                  </select>
            </td>
            <td width="210"> </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
		<br />
        <div align="center"><br />
          <input name="Salvar" type="submit" <?php echo $disable?> class="forms" id="Salvar" value="<?php echo $LANG['language']['save']?>" />
        </div>
      </form>      </td>
    </tr>
  </table>
</div>
<script>
document.getElementById('fantasia').focus();
</script>
