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
	if(!verifica_nivel('pagamentos', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if(isset($_POST['Salvar'])) {

        $parcela = intval($_POST[parcela]);

        $row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM v_orcamento WHERE codigo_parcela='$parcela'"))or die(mysqli_error());

        mysqli_query($conn, "UPDATE parcelas_orcamento SET pago = 'Sim', datapgto = '".date('Y-m-d')."', valor = '".$_POST['valor']."' WHERE codigo = ".$_POST['parcela']);
        mysqli_query($conn, "INSERT INTO caixa (data, dc, valor, descricao) VALUES ('".date('Y-m-d')."', '+', '".$_POST['valor']."', 'Pagamento da parcela ".$row['codigo_parcela']." - Paciente: ".$row['paciente']." - Dentista: ".$row['dentista']."')");
        echo '<script>if(confirm("'.$LANG['payment']['payment_successfully_done'].'\n\n'.$LANG['payment']['patient'].': '.$row['paciente'].'\n\n'.$LANG['payment']['professional'].': '.(($row['sexo_dentista'] == 'Masculino')?'Dr. ':'Dra. ').$row['dentista'].'\n\n'.$LANG['payment']['total_to_pay'].': '.$LANG['general']['currency'].' '.money_form($_POST['valor']).'\n\n'.$LANG['payment']['deadline'].': '.converte_data($row['data'], 2).'\n\n'.$LANG['payment']['payment_date'].': '.date('d/m/Y').'\n\n'.$LANG['payment']['do_you_wish_to_print_the_receipt'].'")) { window.open("relatorios/recibo.php?codigo_parcela='.$_POST['parcela'].'", "'.$LANG['payment']['receipt'].'",  "height=350,width=320,status=yes,toolbar=no,menubar=no,location=no") }</script>';
	}
?>
<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-usd"></span> <?php echo $LANG['payment']['payment']?></div>
  <div class="panel-body">
 
<div class="conteudo" id="table dados"><br>
  
  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pagamentos/parcelas_ajax.php" onsubmit="formSender(this, 'conteudo'); return false;">
      <fieldset>
        <legend><span class="style1"><?php echo $LANG['payment']['plot_information']?> </span></legend>
        <table align="center" cellpadding="0" cellspacing="0" class="table">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center">PASSE O LEITOR ÓPTICO OU
DIGITE O CÓDIGO DE BARRAS<BR><br />
                  <input autocomplete="off" style="max-width:500px;" name="parcela" value="<?php echo $_GET['codigo']?>" <?php echo $disable?> type="text" class="form-control" id="parcela" size="50" maxlength="11" onkeypress="return Bloqueia_Caracteres(event);" onkeyup="javascript:Ajax('pagamentos/dadosparcela', 'pagamento', 'parcela='+this.value)" />
            </td>
          </tr>
          <tr>
            <td align="center"><div id="pagamento"></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
      </fieldset>
      </form>
      </td>
    </tr>
  </table>
</div>
</div>
<script>
document.getElementById('parcela').focus();
Ajax('pagamentos/dadosparcela', 'pagamento', 'parcela=<?php echo $_GET['codigo']?>');
</script>
