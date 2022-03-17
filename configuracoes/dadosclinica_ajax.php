<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('informacoes', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if(!verifica_nivel('informacoes', 'E')) {
		$disable = 'disabled';
	}
	$clinica = new TClinica();
    $clinica->LoadInfo();
	if(isset($_POST['Salvar'])) {
        $_POST['cnpj'] = ajusta_cnpj($_POST['cnpj'], 1);
		$obrigatorios[1] = 'nomefantasia';
		$obrigatorios[] = 'proprietario';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
			    $j++;
				$r[$j] = '<font color="#FF0000">';
			}
		}
		if(strlen($_POST['cnpj']) <= 11) {
			$cpfbool = true;
		}
		if(strlen($_POST['cnpj']) > 11 && strlen($_POST['cnpj']) <= 14) {
			$cpfbool = false;
		}
		if($_POST['cnpj'] != "" && $cpfbool && !is_valid_cpf($_POST['cnpj'])) {
			$j++;
			$r[3] = '<font color="#FF0000">';
		}
		if($$_POST['cnpj'] != "" && !$cpfbool && !is_valid_cnpj($_POST['cnpj'])) {
			$j++;
			$r[3] = '<font color="#FF0000">';
		}
		if($j == 0) {
            $clinica->LoadInfo();
			$clinica->CNPJ = $_POST['cnpj'];
            $clinica->RazaoSocial = utf8_decode ( htmlspecialchars( utf8_encode($_POST['razaosocial']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
            $clinica->Fantasia = utf8_decode ( htmlspecialchars( utf8_encode($_POST['fantasia']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
            $clinica->Proprietario = utf8_decode ( htmlspecialchars( utf8_encode($_POST['proprietario']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
            $clinica->Endereco = utf8_decode ( htmlspecialchars( utf8_encode($_POST['endereco']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
            $clinica->Bairro = utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
            $clinica->Cidade = utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
            $clinica->Estado = $_POST['estado'];
            $clinica->Cep = $_POST['cep'];
            $clinica->Pais = $_POST['pais'];
            $clinica->Fundacao = $_POST['fundacao'];
            $clinica->Telefone1 = $_POST['telefone1'];
            $clinica->Telefone2 = $_POST['telefone2'];
            $clinica->Fax = $_POST['fax'];
            $clinica->Email = $_POST['email'];
            $clinica->Web = $_POST['web'];
            $clinica->Banco1 = $_POST['banco1'];
            $clinica->Agencia1 = $_POST['agencia1'];
            $clinica->Conta1 = $_POST['conta1'];
            $clinica->Banco2 = $_POST['banco2'];
            $clinica->Agencia2 = $_POST['agencia2'];
            $clinica->Conta2 = $_POST['conta2'];
			$clinica->Salvar();
			echo"<script>location.reload();</script>";//$strScrp = 'Ajax(\'wallpapers/index\', \'conteudo\', \'\')';
		}
    }
    if($j == 0) {
        $row = "";
    } else {
        $row = $_POST;
    }
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Informações da Clínica</div>
  <div class="panel-body">
  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="configuracoes/dadosclinica_ajax.php" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><span class="style1"><?php echo $LANG['clinic_information']['clinic_information']?></span></legend>
        <table class="table">
          <tr>
            <td><?php echo $r[1]?>* <?php echo $LANG['clinic_information']['company_name']?> <br />
                
                  <input name="fantasia" value="<?php echo utf8_encode($clinica->Fantasia)?>" <?php echo $disable?> type="text" class="form-control" id="fantasia" size="50" maxlength="80" />
                </td>
            <td><?php echo $r[3]?><?php echo $LANG['clinic_information']['document1']?><br />
              <input name="cnpj" value="<?php echo $clinica->CNPJ?>" <?php echo $disable?> type="text" class="form-control" id="cnpj" size="30" maxlength="18" />
            </td>
          
            <td><?php echo $LANG['clinic_information']['legal_name']?><br />
              <input name="razaosocial" value="<?php echo utf8_encode($clinica->RazaoSocial)?>" <?php echo $disable?> type="text" class="form-control" id="razaosocial" size="50" /></td>
            <td><?php echo $r[2]?>* <?php echo $LANG['clinic_information']['owner']?><br />
              <input name="proprietario" value="<?php echo utf8_encode($clinica->Proprietario)?>" <?php echo $disable?> type="text" class="form-control" id="proprietario" size="40" /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['clinic_information']['address1']?><br />
              <input name="endereco" value="<?php echo utf8_encode($clinica->Endereco)?>" <?php echo $disable?> type="text" class="form-control" id="endereco" size="50" maxlength="150" /></td>
            <td><?php echo $LANG['clinic_information']['address2']?><br />
              <input name="bairro" value="<?php echo utf8_encode($clinica->Bairro)?>" <?php echo $disable?> type="text" class="form-control" id="bairro" /></td>
          
            <td><?php echo $LANG['clinic_information']['city']?><br />
                <input name="cidade" value="<?php echo utf8_encode($clinica->Cidade)?>" <?php echo $disable?> type="text" class="form-control" id="cidade" size="30" maxlength="50" />
             </td>
            <td><?php echo $LANG['clinic_information']['state']?><br />
                <input name="estado" value="<?php echo utf8_encode($clinica->Estado)?>" <?php echo $disable?> type="text" class="form-control" id="estado" /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['clinic_information']['country']?><br />
                <input name="pais" value="<?php echo utf8_encode($clinica->Pais)?>" <?php echo $disable?> type="text" class="form-control" id="pais" size="30" maxlength="50" />
              
            <td><?php echo $LANG['clinic_information']['zip']?><br />
              <input name="cep" value="<?php echo $clinica->Cep?>" <?php echo $disable?> type="text" class="form-control" id="cep" size="10" maxlength="9" onKeypress="return Ajusta_CEP(this, event);" /></td>
            <td><?php echo $LANG['clinic_information']['year_of_foundation']?><br />
              <input name="fundacao" value="<?php echo $clinica->Fundacao?>" <?php echo $disable?> type="text" class="form-control" id="fundacao" maxlength="4" /></td>
          
            <td><?php echo $LANG['clinic_information']['phone1']?><br />
              <input name="telefone1" value="<?php echo $clinica->Telefone1?>" <?php echo $disable?> type="text" class="form-control" id="telefone1" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            </tr><tr>
            <td><?php echo $LANG['clinic_information']['phone2']?><br />
              <input name="telefone2" value="<?php echo $clinica->Telefone2?>" <?php echo $disable?> type="text" class="form-control" id="telefone2" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          
            <td><?php echo $LANG['clinic_information']['fax']?> <br />
              <input name="fax" value="<?php echo $clinica->Fax?>" <?php echo $disable?> type="text" class="form-control" id="fax" size="25" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?php echo $LANG['clinic_information']['website']?><br />
              <input name="web" value="<?php echo $clinica->Web?>" <?php echo $disable?> type="text" class="form-control" id="web" size="40" /></td>
          
            <td><?php echo $LANG['clinic_information']['email']?><br />
              <input name="email" value="<?php echo $clinica->Email?>" <?php echo $disable?> type="text" class="form-control" id="email" size="40" /></td>
          
          </tr>
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?php echo $LANG['clinic_information']['bank_information']?></span></legend>
        <table class="table">
          
          <tr>
            <td><?php echo $LANG['clinic_information']['bank']?> <br />
                
                  <input name="banco1" value="<?php echo $clinica->Banco1?>" <?php echo $disable?> type="text" class="form-control" id="banco1" size="50" maxlength="80" />
                </td>
            
            <td><?php echo $LANG['clinic_information']['agency']?><br />
                <input name="agencia1" value="<?php echo $clinica->Agencia1?>" <?php echo $disable?> type="text" class="form-control" id="agencia1" size="50" maxlength="100" /></td>
            <td><?php echo $LANG['clinic_information']['account']?><br />
                <input name="conta1" value="<?php echo $clinica->Conta1?>" <?php echo $disable?> type="text" class="form-control" id="conta1" /></td>
          
            <td><?php echo $LANG['clinic_information']['bank']?> <br />
                
                  <input name="banco2" value="<?php echo $clinica->Banco2?>" <?php echo $disable?> type="text" class="form-control" id="banco2" size="50" maxlength="80" />
                </td>
            
          </tr>
          <tr>
            <td><?php echo $LANG['clinic_information']['agency']?><br />
                <input name="agencia2" value="<?php echo $clinica->Agencia2?>" <?php echo $disable?> type="text" class="form-control" id="agencia2" size="50" maxlength="100" /></td>
            <td><?php echo $LANG['clinic_information']['account']?><br />
                <input name="conta2" value="<?php echo $clinica->Conta2?>" <?php echo $disable?> type="text" class="form-control" id="conta2" /></td>
          </tr>

          
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?php echo $LANG['clinic_information']['clinic_logotype']?></span></legend>
        <table class="table">
          <tr>
            <td style="max-width:450px;" align="center">
                <iframe style="border-radius: 3px;border:1px solid silver;" height="200" width="262" scrolling="No" name="foto_frame" id="foto_frame" src="configuracoes/logo.php" frameborder="0"></iframe>
            </td>
          </tr>
          
        </table>
        </fieldset>
		<br />
        <div align="center"><br />
<button name="Salvar" type="submit" class="btn btn-primary" id="Salvar" <?php echo $disable2?>><span class=" glyphicon glyphicon-ok"></span> <?php echo $LANG['employee']['save']?></button>        </div>
      </form>      </td>
    </tr>
  </table>
</div>
<script>
document.getElementById('fantasia').focus();
</script>
