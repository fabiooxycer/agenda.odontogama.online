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
	if(!verifica_nivel('convenios', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `convenios` WHERE `codigo` = '".$_GET['codigo']."'") or die(mysqli_error());
        mysqli_query($conn, "DELETE FROM honorarios_convenios WHERE codigo_convenio = '".$_GET['codigo']."'") or die(mysqli_error());
	}
?>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td>
          <?php echo $LANG['plan']['search_for']?><br>
          <select name="campo" id="campo" class="form-control">
            <option value="nomefantasia"><?php echo $LANG['plan']['name']?></option>
            <option value="cidade"><?php echo $LANG['plan']['city']?></option>
          </select>
        </td>
        <td>
          <br>
          <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('convenios/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
        </td>
        <td align="right">
          <br>
          <?php echo ((verifica_nivel('convenios', 'I'))?'<a href="javascript:Ajax(\'convenios/incluir\', \'conteudo\', \'\')"><button class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span> '.$LANG['plan']['include_new_plan'].'</button></a>':'')?>
        </td>
      </tr>
    </table>
  </div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-credit-card"></span> <b><?php echo $LANG['plan']['manage_plans']?></b></div>
  <div class="panel-body">

    <div id="pesquisa"></div>
    <script>
    document.getElementById('procurar').focus();
    Ajax('convenios/pesquisa', 'pesquisa', 'pesquisa=&campo=nomefantasia');
    </script>
  </div>
</div>
