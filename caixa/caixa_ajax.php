<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('caixa', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `caixa` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
    }
	if(checknivel('Dentista')) {
		echo '<script>Ajax(\'caixa_dent/extrato\', \'conteudo\', \'\')</script>';
	} else {
		echo '<script>Ajax(\'caixa/extrato\', \'conteudo\', \'\')</script>';
	}
	/*
?>
<div class="conteudo" id="conteudo_central">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="caixa/img/caixa.png" alt="<?php echo $LANG['cash_flow']['professional_cash_flow']?>" border="0"> <a href="javascript:Ajax('caixa/extrato', 'conteudo', '')"><span class="h3"><?php echo $LANG['cash_flow']['clinic_cash_flow']?></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;<img src="caixa_dent/img/caixa.png" alt="<?php echo $LANG['cash_flow']['professional_cash_flow']?>" border="0"> <a href="javascript:Ajax('caixa_dent/extrato', 'conteudo', '')"><span class="h3"><?php echo $LANG['cash_flow']['professional_cash_flow']?></span></td>
    </tr>
  </table>
</div>
<?php
	*/
?>
