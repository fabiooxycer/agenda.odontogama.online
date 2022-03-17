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
	if(!verifica_nivel('contatos', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `telefones` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
?>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td style="max-width:50px;">
          <br>
          <?php echo ((verifica_nivel('contatos', 'I'))?'<a href="javascript:Ajax(\'telefones/incluir\', \'conteudo\', \'\')"><button class="btn btn-success"><span class=" glyphicon glyphicon-plus"></span> '.$LANG['useful_telephones']['include_new_contact'].'</button></a>':'')?>
        </td>
        <td style="text-align:left;">
          <?php echo $LANG['useful_telephones']['search_for']?><br>
          <input name="procurar" id="procurar" type="text" class="form-control" maxlength="40" onkeyup="javascript:Ajax('telefones/pesquisa', 'pesquisa', 'pesquisa='+this.value)">
        </td>
      </tr>
    </table>
  </div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-earphone"></span> <b><?php echo $LANG['useful_telephones']['useful_telephones']?></b></div>
  <div class="panel-body">

  <div id="pesquisa"></div>
  <script>
  document.getElementById('procurar').focus();
  Ajax('telefones/pesquisa', 'pesquisa', 'pesquisa=');
  </script>
</div>
</div>
