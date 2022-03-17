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
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['patients']['radiograph']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">


  <table class="table">
    <tr>
      <td>
        <br />
        <div align="center">
<?php
    $_GET['modelo'] = (($_GET['modelo'] != '')?$_GET['modelo']:'Panoramica');
    $valores = array('Panoramica' => $LANG['patients']['panoramic'], 'Oclusal' => $LANG['patients']['occlusal'], 'Periapical' => $LANG['patients']['periapical'], 'Interproximal' => $LANG['patients']['interproximal'], 'ATM' => $LANG['patients']['atm'], 'PA' => $LANG['patients']['posteroanterior'], 'AP' => $LANG['patients']['anteroposterior'], 'Lateral' => $LANG['patients']['lateral']);
    foreach($valores as $chave => $valor) {
        
    }
?>
        </select></div>
        <br />
        <fieldset>
        <br />
          <table class='table'>
            <tr>
<?php
	$i = 0;
	$query = mysqli_query($conn, "SELECT * FROM radiografias WHERE modelo = '".$_GET['modelo']."' AND codigo_paciente = '".$_GET['codigo']."' ORDER BY data DESC, codigo DESC") or die(mysqli_error());
	while($row = mysqli_fetch_array($query)) {
		if($i % 2 === 0) {
			echo '</tr><tr>';
		}
?>
              <div class="col-sm-6 col-md-4" style="max-width:262px;">
                <div class="thumbnail">
                 <img src="pacientes/verfoto_r.php?codigo=<?php echo $row['codigo']?>" onclick="javascript:Ajax('pacientes/radio_detalhe', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&acao=editar&codigo_foto=<?php echo $row['codigo']?>')">
                 <div class="caption">
                    <span><b><?php echo $row['legenda']?></b><br /><?php echo converte_data($row['data'], 2)?></span>
                    <p><?php echo '<a href="pacientes/verfoto_r.php?codigo='.$row['codigo'].'" target="_blank"><button class="btn btn-primary"><span title="Visualizar tamanho original" class="  glyphicon glyphicon-eye-open"></span></button></a>';?>
                    </p>
                  </div>
                </div>
              </div>
<?php
		$i++;
	}
?>
           </tr>
        </table> 
