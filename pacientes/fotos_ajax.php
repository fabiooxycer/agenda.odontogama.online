<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
  
	if(!checklog()) {
		die($frase_log);
	}

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	$strUpCase = "ALTERAÇÂO";
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$acao = '&acao=editar';
?>

<div class="panel panel-default">
    <div class="panel-body">
      <?php 
      $ativo_fotos = true;
      include('submenu.php'); ?>
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
                   <p>
                   <?php 
                   $codigo_foto = $row['codigo'];
                   echo ((verifica_nivel('pacientes', 'E'))?'<a href="javascript:Ajax(\'pacientes/excluirfotos\', \'conteudo\', \'codigo_foto='.$codigo_foto.'" onclick="return confirmLink(this)" target="iframe_upload"><button title="Apagar" class="btn btn-danger"><span class=" glyphicon glyphicon-trash"></span></button></a>':'')?>      
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
          <form id="form2" name="form2" method="POST" action="pacientes/incluirfotos_ajax.php?codigo=<?php echo $_GET['codigo']?>" enctype="multipart/form-data" target="iframe_upload">
  		  <table class="table">
    		<tr align="center">
              <td><?php echo $LANG['patients']['file']?> <br />
                <input type="file" size="20" name="arquivo" id="arquivo" class="forms" <?php echo $disable?> />
              </td>
            </tr>
    		<tr align="center">
              <td><?php echo $LANG['patients']['legend']?> <br />
                <input type="text" size="33" style="max-width:200px;" name="legenda" id="legenda" class="form-control" <?php echo $disable?> />
              </td>
            </tr>
            <tr align="center">
              <td> <br />
                <input type="submit" name="Salvar" id="Salvar" value="<?php echo $LANG['patients']['save']?>" class="btn btn-primary" <?php echo $disable?> />
              </td>
            </tr>
          </table>
          </form>
          <br />
      </td>
    </tr>
  </table>
</div>
</div>
