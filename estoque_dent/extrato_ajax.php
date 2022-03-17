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
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `estoque_dent` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
	}
	if(isset($_POST[Salvar])) {		
		$senha = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `dentistas` WHERE `codigo` = '".$_SESSION[codigo]."'"));
		$obrigatorios[1] = 'descricao';
		$obrigatorios[] = 'quantidade';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
			    $j++;
				$r[$j] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			$caixa = new TEstoque('dentista');
			$caixa->SetDados('descricao', $_POST[descricao]);
			$caixa->SetDados('quantidade', $_POST[quantidade]);
			$caixa->SetDados('codigo_dentista', $_SESSION[codigo]);
			$caixa->SalvarNovo();
			$caixa->Salvar();
		}
	}
?>
<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    
    <?php echo $LANG['stock']['search_by_description']?>:
    <input name="procurar" id="procurar" type="text" class="form-control" style="max-width:600px;" maxlength="40" onkeyup="javascript:Ajax('estoque_dent/pesquisa', 'pesquisa', 'pesquisa='+this.value)">
            
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-tag"></span> <b><?php echo $LANG['stock']['professional_stock_control']?></b></div>
  <div class="panel-body">
  <table class="table">
    <tr>
      <!--<td width="60%">&nbsp;&nbsp;&nbsp;<img src="estoque/img/estoque.png" alt="<?php echo $LANG['stock']['clinic_stock_control']?>"> <span class="h3"><?php echo $LANG['stock']['clinic_stock_control']?></span></td>-->
    </tr>
  </table>
  
    
<?php
  if(verifica_nivel('estoque', 'I')) {
?>
  <form id="form2" name="form2" method="POST" action="estoque_dent/extrato_ajax.php" onsubmit="formSender(this, 'conteudo'); this.reset(); return false;">
  <table class="table">
    <tr>
      <td><?php echo $LANG['stock']['description']?> <br />
        <input type="text" size="80" name="descricao" id="descricao" class="form-control">
      </td>
      <td><?php echo $LANG['stock']['quantity']?> <br />
        <input type="text" size="20" name="quantidade" id="quantidade" class="form-control">
      </td>
      <td align="right"> <br />
        <button type="submit" name="Salvar" id="Salvar" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?php echo $LANG['stock']['save']?></button>
      </td>
     
    </tr>
  </table>
  </form>
<?php
    }
?>

  <div id="pesquisa"></div>
  <script>
  document.getElementById('procurar').focus();
  Ajax('estoque_dent/pesquisa', 'pesquisa', 'pesquisa=');
  </script>
</div>
