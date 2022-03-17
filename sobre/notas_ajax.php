<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=ISO-8859-1", true);

?>
<div class="panel panel-default" id="conteudo_central">
  <div class="panel-body">
    <div class="panel panel-default" style="background:#1D4656;">
      <div class="panel-body">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
          <tr>
            <td width="74%"><span class="h3" style="color:#fff;">NOTAS DA VERSÃO ATUAL </span></td>
            <td width="7%" align="right"><img src="imgSite/logo.png" width="100" title="MDev - Desenvolvimento de sistemas"/></td>
          </tr>
        </table>
      </div>
    </div>
<div class="conteudo" id="table dados"><br />
  <div class="sobre" id="sobre">

    <fieldset>
  <legend>Notas da vers&atilde;o atual  </legend>
  <p><b>Versão atual: <?php echo "UCO V 1.0 - 2016";?></b></p>
  <p><?php include "../lib/changelog.php"; ?></p>
    </fieldset>
  </div>
</div></div>
