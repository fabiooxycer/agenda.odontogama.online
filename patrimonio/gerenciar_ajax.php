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
	if(!verifica_nivel('patrimonio', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `patrimonio` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
?>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td>
          <?php echo $LANG['patrimony']['search_for']?><br>
          <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('patrimonio/pesquisa', 'pesquisa', 'pesquisa='+this.value)">
        </td>
        <td style="text-align:right;">
          <br>
          <?php echo ((verifica_nivel('patrimonio', 'I'))?'<a href="javascript:Ajax(\'patrimonio/incluir\', \'conteudo\', \'\')"><button class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span> '.$LANG['patrimony']['include_new_item'].'</button></a>':'')?>

        </td>
      </tr>
    </table>
  </div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-edit"></span> <b>Gerenciar Patrimônio</b></div>
  <div class="panel-body">
  
      
     
    <div id="pesquisa"></div>
    
    <script>
    Ajax('patrimonio/pesquisa', 'pesquisa', 'pesquisa=');
    </script>
  </div>
</div>
