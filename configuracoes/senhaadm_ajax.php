<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!verifica_nivel('senha_adm', 'V')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }

    $sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

	if(isset($_POST[login])) {
		$funcionario = new TFuncionarios();
		if($_POST[senha] != '') {
			if($_POST[senha] != $_POST[confsenha]) {
				$j++;
				$r[2] = '<font color="#FF0000">';
				$r[3] = '<font color="#FF0000">';
			}
			$senha = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `funcionarios` WHERE `codigo` = '1'"));
			if(md5($_POST[senhaatual]) != $senha[senha]) {
				$j++;
				$r[1] = '<font color="#FF0000">';
			}
			if($j == 0) {
				$funcionario->LoadFuncionario('1');
				$strScrp = "alert('".$LANG['admin_password']['password_successfully_changed']."'); Ajax('configuracoes/senhaadm', 'conteudo', '');";
				if($_POST[senha] != "") {
					$funcionario->SetDados('senha', md5($_POST[senha]));
				}
				$funcionario->Salvar();
			}
		}
	}
?>
<script>
<?php echo $strScrp?>
</script>


<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-lock"></span> <b><?php echo $LANG['admin_password']['change_admin_password']?></b></div>
  <div class="panel-body">

  <table class="table" align="center" style="max-width:500px;">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="configuracoes/senhaadm_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;">
        <fieldset>
        <legend><span class="style1"><?php echo $LANG['admin_password']['personal_access_information']?></span></legend>
        <table class="table">
          <tr>
            <td><?php echo $r[1]?><?php echo $LANG['admin_password']['current_password']?>:<br />
              <input name="senhaatual" value="" type="password" class="form-control" id="senhaatual" maxlength="32" />
            </td>
          </tr>
          <tr>
            <td><?php echo $r[2]?><?php echo $LANG['admin_password']['new_password']?><br />
              <input name="senha" value="" type="password" class="form-control" id="senha" maxlength="32" />
           </td>
          </tr>
          <tr>
            <td><?php echo $r[3]?><?php echo $LANG['admin_password']['retype_new_password']?><br />
              <input name="confsenha" value="" type="password" class="form-control" id="confsenha" maxlength="32" />
          </td>
          </tr>
          
        </table>
        </fieldset>
        <div align="center"><br>
          <button name="login" type="submit" class="btn btn-success" id="login" value=""><?php echo $LANG['admin_password']['save']?></button>
        </div>
      </form>
      </td>
    </tr>
  </table>
</div>
<script>
document.getElementById('senhaatual').focus();
</script>
