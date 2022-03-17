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
	$acao = '&acao=editar';
	$paciente = new TPacientes();
    $query = mysqli_query($conn, "SELECT * FROM odontograma WHERE codigo_paciente = ".$_GET['codigo']) or die('Line 39: '.mysqli_error());
    while($row = mysqli_fetch_assoc($query)) {
        $dente[$row['dente']] = $row['descricao'];
    }
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
?>

<style type="text/css">
<!--
.style4 {color: #FFFFFF}
-->
</style>

<div class="panel panel-default">
    <div class="panel-body">
      <?php include('submenu.php'); ?>
    </div>
  </div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['patients']['manage_patients']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">


<table class="table">
  <tr>
    <td>&nbsp;<?php echo $LANG['patients']['odontogram']?></td>
  </tr>
</table>
<table class="table">
  <tr>
    <td>
      <form id="form2" name="form2" method="POST" action="pacientes/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background: url('pacientes/img/odontograma.gif') center center no-repeat;background-size:168px;">
        <tr>
          <td width="38%" align="right">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
    for($i = 18; $i != 49; $i++) {
?>
              <tr>
                <td width="100%" align="right" valign="middle">
                  <input type="text" style="width:100%;" name="dente[<?php echo $i?>]" value="<?php echo $dente[$i]?>" class="forms" <?php echo $disable?>
                  onblur="Ajax('pacientes/atualiza', 'pacientes_atualiza', 'descricao='+this.value+'&codigo_paciente=<?php echo $_GET['codigo']?>&dente=<?php echo $i?>');" />
                </td>
              </tr>
<?php
        if($i == 11) {
            $i = 40;
        }
        if($i < 40) {
            $i -= 2;
        }
    }
?>
            </table>
          </td>
          <td width="22%" align="center">

          </td>
          <td width="38%" align="center">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
    for($i = 28; $i != 39; $i++) {
?>
              <tr>
                <td width="100%" align="left" valign="middle">
                  <input type="text" style="width:100%;" name="dente[<?php echo $i?>]" value="<?php echo $dente[$i]?>" class="forms" <?php echo $disable?>
                  onblur="Ajax('pacientes/atualiza', 'pacientes_atualiza', 'descricao='+this.value+'&codigo_paciente=<?php echo $_GET['codigo']?>&dente=<?php echo $i?>');" />
                </td>
              </tr>
<?php
        if($i == 21) {
            $i = 30;
        }
        if($i < 30) {
            $i -= 2;
        }
    }
?>
            </table>
          </td>
        </tr>
      </table>
    </form>
    </td>
  </tr>
    <tr>
      <td align="right"> <br />
        <a href="relatorios/odontograma.php?codigo=<?php echo $_GET['codigo']?>" target="_blank"><button class="btn btn-warning">
                <span class="glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print_odontogram']?></button></a>&nbsp;
      </td>
    </tr>
</table>
</div></div>
<div id="pacientes_atualiza"></div>
