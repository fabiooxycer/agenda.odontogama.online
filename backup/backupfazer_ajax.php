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
	if(!verifica_nivel('backup_gerar', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
?>
<div class="alert alert-success">
  <strong>Backup gerado com sucesso!</strong> Aguarde o início do download.
</div>
  
<iframe name="iframe_backup" width="1" height="1" frameborder="0" scrolling="No" src="backup/backupfazendo.php"></iframe>
