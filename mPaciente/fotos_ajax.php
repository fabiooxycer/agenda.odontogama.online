<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	if(!checklog()) {
		die($frase_log);
	}
	$strUpCase = "ALTERAÇÂO";
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$acao = '&acao=editar';
?>

<div class="panel panel-default">
    <div class="panel-body">
      <?php include('submenu.php'); ?>
    </div>
  </div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="  glyphicon glyphicon-camera"></span> <b><?php echo $LANG['patients']['photos']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">

  <table class="table">
    <tr>
      <td>
        <br />
        <fieldset>
        <br />
          <table class="table">
            <tr>
<?php
	$i = 0;
	$query = mysqli_query($conn, "SELECT * FROM `fotospacientes` WHERE `codigo_paciente` = '".$_GET[codigo]."' ORDER BY `codigo`") or die(mysqli_error());
	while($row = mysqli_fetch_array($query)) {
		if($i % 2 === 0) {
			echo '</tr><tr>';
		}
?>
              
                <div class="col-sm-6 col-md-4" style="max-width:262px;">
                <div class="thumbnail">
                  <img src="pacientes/verfoto.php?codigo=<?php echo $row['codigo']?>">
                  <div class="caption">
                    <span><b><?php echo $row['legenda']?></b></span>
                   
                 </div>
               </div>
             </div>
              
<?php
		$i++;
	}
?>
           </tr>
        </table> 
        <br />
        </fieldset>
        <br />
        
          
          <br />
      </td>
    </tr>
  </table>
</div>
</div>
