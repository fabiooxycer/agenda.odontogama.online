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
        <fieldset>
        <br />
          <table class="table">
            <tr>
<?php
    $query = mysqli_query($conn, "SELECT * FROM radiografias WHERE codigo = '".$_GET['codigo_foto']."'") or die(mysqli_error());
    $row = mysqli_fetch_array($query);
?>
              <td align="left">
                <button class="btn btn-default" onclick="javascript:Ajax('pacientes/radio', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&acao=editar&modelo=<?php echo $row['modelo']?>');">Voltar</button>
              </td>
            </tr>
            <tr>
              <td valign="top">
                 <div class="col-sm-6 col-md-4" style="max-width:262px;">
                <div class="thumbnail">
                 <img src="pacientes/verfoto_r.php?codigo=<?php echo $row['codigo']?>" onclick="javascript:Ajax('pacientes/radio_detalhe', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&acao=editar&codigo_foto=<?php echo $row['codigo']?>')">
                 <div class="caption">
                    <span><b><?php echo $row['legenda']?></b><br /><?php echo converte_data($row['data'], 2)?></span>
                    <p><?php echo ((verifica_nivel('pacientes', 'E'))?'<a href="pacientes/excluirfotos_r_ajax.php?codigo='.$_GET['codigo'].'&codigo_foto='.$row['codigo'].'&modelo='.$_GET['modelo'].'" onclick="return confirmLink(this)" target="iframe_upload"><button title="Apagar" class="btn btn-danger"><span class=" glyphicon glyphicon-trash"></span></button></a>':'')?>
                    <a href="pacientes/verfoto_r.php?codigo=<?php echo $row['codigo']?>" target="_blank"><button class="btn btn-primary"><span title="Visualizar tamanho original" class="  glyphicon glyphicon-eye-open"></span></button></a>
                    </p>
                  </div>
                </div>
              </div>
                
              </td>
           </tr>
        </table>
        <br />
        <iframe name="iframe_upload" width="1" height="1" frameborder="0" scrolling="No"></iframe>
          <form id="form2" name="form2" method="POST" action="pacientes/incluirradiodiagnostico_ajax.php?codigo=<?php echo $row['codigo']?>" enctype="multipart/form-data" target="iframe_upload"> <?php/*onsubmit="Ajax('arquivos/daclinica/arquivos', 'conteudo', '');">*/?>
  		  <table class="table">
    		<tr align="center">
              <td ><?php echo $LANG['patients']['radio_diagnosis']?> <br />
                <textarea cols="50" rows="8" class="form-control" <?php echo $disable?> name="texto"><?php echo $row['diagnostico']?></textarea>
              </td>
            </tr>
            <tr align="center">
              <td > <br />
                <button type="submit" name="Salvar" id="Salvar" value="<?php echo $LANG['patients']['save']?>" class="btn btn-success" <?php echo $disable?>><span class="glyphicon glyphicon-ok"></span> Salvar</button>
                <a href="relatorios/radiodiagnostico.php?codigo=<?php echo $row['codigo']?>" target="_blank"><button class="btn btn-warning"><span class="  glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print'].' '.$LANG['patients']['radio_diagnosis']?></button></a>
              </td>
            </tr>
          </table>
          </form>
          <br />
        </fieldset>
        <br />
        <div align="center"></div>
        <br />
      </td>
    </tr>
  </table>
