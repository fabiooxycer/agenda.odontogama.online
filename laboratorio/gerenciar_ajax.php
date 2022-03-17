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
	if(!verifica_nivel('laboratorios', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `laboratorios` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
?>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td>
          <?php echo $LANG['laboratory']['search_for']?><br>
          <select name="campo" id="campo" class="form-control">
                <option value="nomefantasia"><?php echo $LANG['laboratory']['name']?></option>
                <option value="cidade"><?php echo $LANG['laboratory']['city']?></option>
              </select>
        </td>
        <td>
          <br>
          
          <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('laboratorio/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">

        </td>
        <td align="right">
          <br>
          <?php echo ((verifica_nivel('laboratorios', 'I'))?'<a href="javascript:Ajax(\'laboratorio/incluir\', \'conteudo\', \'\')"><button class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span> '.$LANG['laboratory']['include_new_laboratory'].'</button></a>':'')?>
        </td>
      </tr>
    </table>
  </div>
</div>


<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-tint"></span> <b>Gerenciar Laboratórios</b></div>
  <div class="panel-body">

  <div id="pesquisa"></div>
  <script>
  document.getElementById('procurar').focus();
  Ajax('laboratorio/pesquisa', 'pesquisa', 'pesquisa=&campo=nomefantasia');
  </script>
</div>
</div>
