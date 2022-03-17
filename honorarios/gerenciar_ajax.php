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
	if(!verifica_nivel('convenios', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
//	if($_GET[confirm_del] == "delete") {
//        mysql_query("DELETE FROM honorarios WHERE codigo = '".$_GET['codigo']."'") or die(mysql_error());
//        mysql_query("DELETE FROM honorarios_convenios WHERE codigo_convenio = '".$_GET['codigo']."'") or die(mysql_error());
//	}
?>
<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> <b><?php echo $LANG['menu']['fees']?></b></div>
  <div class="panel-body">

  <table class="table">
    <tr>
      <td align="right" ><?php echo ((verifica_nivel('convenios', 'I'))?'<a href="javascript:Ajax(\'convenios/incluir\', \'conteudo\', \'\')"><button class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span> '.$LANG['plan']['include_new_plan'].'</button></a>':'')?></td>
    </tr>
  </table>


  <div id="pesquisa"></div>
  <script>
  Ajax('honorarios/hpesquisa', 'pesquisa', '');
  </script>
</div>
</div>