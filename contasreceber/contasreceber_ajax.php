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
	if(!verifica_nivel('contas_receber', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if(checknivel('Dentista')) {
		echo '<script>Ajax(\'contasreceber_dent/extrato\', \'conteudo\', \'\')</script>';
	} else {
		echo '<script>Ajax(\'contasreceber/extrato\', \'conteudo\', \'\')</script>';
	}
	/*
?>
<div class="conteudo" id="conteudo_central">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="contasreceber/img/contas.png" alt="Contas a receber da Clínica" border="0"> <a href="javascript:Ajax('contasreceber/extrato', 'conteudo', '')"><span class="h3">Contas a receber da Clínica </span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;<img src="contasreceber_dent/img/contas.png" alt="Contas a receber dos dentistas" border="0"> <a href="javascript:Ajax('contasreceber_dent/extrato', 'conteudo', '')"><span class="h3">Contas a receber dos Dentistas </span></td>
    </tr>
  </table>
</div>
	*/
?>
