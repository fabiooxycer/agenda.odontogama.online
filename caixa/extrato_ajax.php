<?php

	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error());

	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `caixa` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
?>

<script type="text/javascript">
  
</script>


<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td>
          <input type="hidden" name="peri" id="peri" value="">
          <input type="radio" name="pesq" id="pesqdia" value="dia" onclick="document.getElementById('peri').value='dia'"><label for="pesqdia"> <?php echo $LANG['cash_flow']['day_month_year']?></label>
        </td>
        <td>
          <input type="radio" name="pesq" id="pesqmes" value="mes" onclick="document.getElementById('peri').value='mes'"><label for="pesqmes"> <?php echo $LANG['cash_flow']['month_year']?></label>
        </td>
        <td>
          <input type="radio" name="pesq" id="pesqano" value="ano" onclick="document.getElementById('peri').value='ano'"><label for="pesqano"> <?php echo $LANG['cash_flow']['year']?></label>
        </td>
        <td>
          <input type="radio" name="pesq" id="pesqmesatual" value="mesatual" onclick="javascript:Ajax('caixa/pesquisa', 'pesquisa', 'peri=mesatual')"><label for="pesqmesatual"> <?php echo $LANG['cash_flow']['current_month']?></label>
        </td>
      </tr>
      <tr>
        <td colspan="4">
          <?php echo $LANG['cash_flow']['search_for']?> <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('caixa/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&peri='+document.getElementById('peri').value)" onKeypress="return Ajusta_DMA(this, event, document.getElementById('peri').value);"
              onclick="if(document.getElementById('pesqdia').checked) {abreCalendario(this);}">
        </td>
      </tr>
    </table>
  </div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="  glyphicon glyphicon-share-alt"></span> <b><?php echo $LANG['cash_flow']['clinic_cash_flow']?></b></div>
  <div class="panel-body">

<?php
    if(verifica_nivel('caixa', 'I')) {
?>
  <form id="form2" name="form2" method="POST" action="caixa/inicial_ajax.php" onsubmit="formSender(this, 'pesquisa'); this.reset(); return false;">
  <div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-asterisk"></span> <b>Novo valor</b></div>
  <div class="panel-body">
  <table class="table">
    <tr>
      <td width="4%">
      </td>
      <td width="12%"><?php echo $LANG['cash_flow']['date']?> <br />
        <input type="text" size="13" value="<?php echo converte_data(hoje(), 2)?>" name="data" id="data" class="form-control" onKeypress="return Ajusta_Data(this, event);">
      </td>
      <td width="53%"><?php echo $LANG['cash_flow']['description']?> <br />
        <input type="text" size="77" name="descricao" id="descricao" class="form-control">
      </td>
      <td width="7%"><?php echo $LANG['cash_flow']['d_c']?> <br />
        <select name="dc" class="form-control" id="dc">
<?php
	$estados = array('+', '-');
	foreach($estados as $uf) {
		if($row[sexo] == $uf) {
			echo '<option value="'.$uf.'" selected>'.$uf.'</option>';
		} else {
			echo '<option value="'.$uf.'">'.$uf.'</option>';
		}
	}
?>       
			 </select>
      </td>
      <td width="11%"><?php echo $LANG['cash_flow']['value']?> <br />
        <input type="text" size="12" name="valor" id="valor" class="form-control" onKeypress="return Ajusta_Valor(this, event);">
      </td>
      <td width="10%"> <br />
        <button type="submit" name="Salvar" id="Salvar" value="" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?php echo $LANG['cash_flow']['save']?></button>
      </td>
      <td width="3%">
      </td>
    </tr>
  </table>
</div>
</div>
  </form>
<?php
    }
?>

  <div id="pesquisa"></div>
  <script>
  Ajax('caixa/inicial', 'pesquisa', 'pesquisa=');
  </script>
</div>
