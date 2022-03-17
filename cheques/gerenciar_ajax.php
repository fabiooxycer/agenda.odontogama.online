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

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `cheques` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
?>
<div id='calendario' name='calendario' style='display:none;position:absolute;'>
<?php
	//include "../lib/calendario.inc.php";
?>
</div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td>
          <br>
          <?php echo ((verifica_nivel('cheques', 'I'))?'<a href="javascript:Ajax(\'cheques/incluir\', \'conteudo\', \'\')"><button class="btn btn-success"><span class=" glyphicon glyphicon-plus"></span> '.$LANG['check_control']['include_new_check'].'</button></a>':'')?>
        </td>
        <td>
          Pesquisar por<br>
          <select name="campo" id="campo" class="form-control">
            <option value="nometitular"><?php echo $LANG['check_control']['holder']?></option>
            <option value="recebidode"><?php echo $LANG['check_control']['received_from']?></option>
            <option value="encaminhadopara"><?php echo $LANG['check_control']['forwarded_to']?></option>
            <option value="compensacao"><?php echo $LANG['check_control']['compensation_date']?></option>
          </select>
        </td>
        <td>
          <br>
          <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('cheques/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)" onKeypress="if(document.getElementById('campo').selectedIndex==3) {return Ajusta_Data(this, event);}" onclick="if(document.getElementById('campo').selectedIndex==3) {abreCalendario(this);}">
        </td>
      </tr>
    </table>
  </div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-object-align-horizontal"></span> <b><?php echo $LANG['check_control']['clinic_check_control']?></b></div>
  <div class="panel-body">


  <div id="pesquisa"></div>
  <script>
  Ajax('cheques/pesquisa', 'pesquisa', 'pesquisa=&campo=nometitular');
  </script>
</div></div>
