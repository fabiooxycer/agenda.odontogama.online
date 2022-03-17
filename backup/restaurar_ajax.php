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
	if(!verifica_nivel('backup_restaurar', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	
?>
<div class="panel panel-default">
  <div class="panel-heading"><span class="  glyphicon glyphicon-th-large"></span> <?php echo $LANG['backup_restoration']['backup_restoration']?></div>
  <div class="panel-body"> 
    

    <fieldset>
  <legend><?php echo $LANG['backup_restoration']['explanation']?> </legend>

  <?php echo $LANG['backup_restoration']['the_area_you_accessed']?><br />
  <br />
  O que acontece é que o PHP, linguagem na qual o sistema foi programado, não suporta arquivos muito grandes e, dependendo do tamanho de seu banco, pode causar problemas no programa e nos dados nele arquivados.
<br />
  <br />
  <?php echo $LANG['backup_restoration']['because_of_this']?><br />
  <br />
  No entanto, não é preciso se preocupar. Se você fez o backup, os dados estão salvos e há maneiras de se recuperar a cópia de segurança. Existem, no mercado, várias ferramentas que realizam esta função de maneira rápida e segura. Um desses exemplos é o MySQL-Front.<br />
  <br />
  <?php echo $LANG['backup_restoration']['the_mysql_front']?><br />
  <br />
    </fieldset>
  </div>
</div>
