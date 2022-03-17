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
	if(!verifica_nivel('cheques', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `contaspagar` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
	if(checknivel('Dentista')) {
		echo '<script>Ajax(\'cheques_dent/gerenciar\', \'conteudo\', \'\')</script>';
	} else {
		echo '<script>Ajax(\'cheques/gerenciar\', \'conteudo\', \'\')</script>';
	}
	/*
?>
<div class="conteudo" id="conteudo_central">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="cheques/img/cheques.png" alt="Cheques da Clínica" border="0"> <a href="javascript:Ajax('cheques/gerenciar', 'conteudo', '')"><span class="h3">Cheques da Clínica </span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;<img src="cheques_dent/img/cheques.png" alt="Cheques dos dentistas" border="0"> <a href="javascript:Ajax('cheques_dent/gerenciar', 'conteudo', '')"><span class="h3">Cheques dos Dentistas </span></td>
    </tr>
  </table>
</div>
<?php
	*/
?>
