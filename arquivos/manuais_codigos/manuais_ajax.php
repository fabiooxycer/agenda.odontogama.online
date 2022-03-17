<?php
   
	include "../../lib/config.inc.php";
	include "../../lib/func.inc.php";
	include "../../lib/classes.inc.php";
	require_once '../../lang/'.$idioma.'.php';
	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('manuais', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
?>
<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-pushpin"></span> <b>Manuais e códigos</b></div>
  <div class="panel-body">

<div class="conteudo" id="table dados"><br>
 
  <table class="table table-hover">

    <tr>
      <td>Codigo Internacional de Doen&ccedil;as Odontol&oacute;gicas<br /></td>
      <td><a href="arquivos/manuais_codigos/files/cid.pdf" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>
    </tr>
    <tr>
      <td>C&oacute;digo de &Eacute;tica Odontol&oacute;gico - CFO<br /></td>
      <td><a href="arquivos/manuais_codigos/files/codigo_etica.pdf" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>
    </tr>
    <tr>
      <td>C&oacute;digo de Processo &Eacute;tico - CFO<br /></td>
      <td><a href="arquivos/manuais_codigos/files/codigo_proc_etico.pdf" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>
    </tr>
    <tr>
      <td>Manual de Biosseguran&ccedil;a - CFO<br /></td>
      <td><a href="arquivos/manuais_codigos/files/manual_biosseguranca.pdf" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>
    </tr>
    <tr>
      <td>Manual de Gerenciamento de Res&iacute;duos - ANVISA<br /></td>
      <td><a href="arquivos/manuais_codigos/files/manual_gerenciamento_residuos_anvisa.pdf" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>
    </tr>
    <tr>
      <td>Manual da Odontologia - ANVISA</td>
      <td><a href="arquivos/manuais_codigos/files/manual_odonto_anvisa.pdf" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>
    </tr>
  </table>
</div>
</div>