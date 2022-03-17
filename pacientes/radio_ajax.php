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

      <?php 
      $ativo_radio = true;
      include('submenu.php'); ?>
    </div>
  </div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['patients']['radiograph']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">


  <table class="table">
    <tr>
      <td>
        <br />
        <div align="center"><select name="modelo" class="form-control" style="max-width:300px;" onchange="Ajax('pacientes/radio', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&acao=editar&modelo='%2Bthis.value)">
<?php
    $_GET['modelo'] = (($_GET['modelo'] != '')?$_GET['modelo']:'Panoramica');
    $valores = array('Panoramica' => $LANG['patients']['panoramic'], 'Oclusal' => $LANG['patients']['occlusal'], 'Periapical' => $LANG['patients']['periapical'], 'Interproximal' => $LANG['patients']['interproximal'], 'ATM' => $LANG['patients']['atm'], 'PA' => $LANG['patients']['posteroanterior'], 'AP' => $LANG['patients']['anteroposterior'], 'Lateral' => $LANG['patients']['lateral']);
    foreach($valores as $chave => $valor) {
        echo '          <option value="'.$chave.'" '.(($chave == $_GET['modelo'])?'selected':'').'>'.$valor.'</option>'."\n";
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
                 <img src="pacientes/verfoto_r.php?codigo=<?php echo $row['codigo']?>" onclick="javascript:Ajax('pacientes/radio_detalhe', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&acao=editar&codigo=<?php echo $row['codigo']?>')">
                 <div class="caption">
                    <span><b><?php echo $row['legenda']?></b><br /><?php echo converte_data($row['data'], 2)?></span> <?php $codigo_foto = $row[$i]['codigo']; ?>
                    <p><?php echo ((verifica_nivel('pacientes', 'E'))?'<a href="javascript:Ajax(\'pacientes/excluirfotos_r\', \'conteudo\', \'codigo='.$row[$i][codigo].'" onclick="return confirmLink(this)"><button class="btn btn-danger" title="Excluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?>

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
        <br />
        </fieldset>
        <br />
        <iframe name="iframe_upload" width="1" height="1" frameborder="0" scrolling="No"></iframe>
          <form id="form2" name="form2" method="POST" action="pacientes/incluirfotos_r_ajax.php?codigo=<?php echo $_GET['codigo']?>" enctype="multipart/form-data" target="iframe_upload"> 
          <input type="hidden" name="modelo" value="<?php echo $_GET['modelo']?>" />
  		  <table class="table">
    		<tr align="center">
              <td><?php echo $LANG['patients']['file']?> <br />
                <input type="file" size="20" name="arquivo" id="arquivo" class="forms" <?php echo $disable?>>
              </td>
            </tr>
    		<tr align="center">
              <td><?php echo $LANG['patients']['legend']?> <br />
                <input type="text" size="33" name="legenda" id="legenda" class="form-control" style="max-width:300px;" <?php echo $disable?>>
              </td>
            </tr>
    		<tr align="center">
              <td><?php echo $LANG['patients']['date']?> <br />
                <input type="text" size="33" name="data" id="data" value="<?php echo date('d/m/Y')?>" style="max-width:300px;" class="form-control" <?php echo $disable?>>
              </td>
            </tr>
            <tr align="center">
              <td> <br />
                <input type="submit" name="Salvar" id="Salvar" value="<?php echo $LANG['patients']['save']?>" class="btn btn-success" <?php echo $disable?>>
              </td>
            </tr>
          </table>
          </form>
          <br />
      </td>
    </tr>
  </table>
