<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	if(($_GET['codigo'] != '' && !verifica_nivel('contatos', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('contatos', 'I'))) {
        $disable = 'disabled';
    }
	$telefones = new TTelefones();
	if(isset($_POST[Salvar])) { 
		$obrigatorios[1] = 'nom';
		$obrigatorios[] = 'telefone1';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
				$r[$i] = '<font color="#FF0000">';
			    $j++;
			}
		}
		if($j == 0) {
			if($_GET[acao] == "editar") {
				$telefones->LoadTelefones($_GET[codigo]);
			}
			$telefones->SetDados('nome', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nom']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$telefones->SetDados('endereco', utf8_decode ( htmlspecialchars( utf8_encode($_POST['endereco']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$telefones->SetDados('bairro', utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$telefones->SetDados('cidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$telefones->SetDados('estado', $_POST[estado]);
			$telefones->SetDados('pais', utf8_decode ( htmlspecialchars( utf8_encode($_POST['pais']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$telefones->SetDados('cep', $_POST[cep]);
			$telefones->SetDados('celular', $_POST[celular]);
			$telefones->SetDados('telefone1', $_POST[telefone1]);
			$telefones->SetDados('telefone2', $_POST[telefone2]);
			$telefones->SetDados('website', $_POST[website]);
			$telefones->SetDados('email', $_POST[email]);
			if($_GET[acao] != "editar") {
				$telefones->SalvarNovo();
				//$strScrp = "alert('Cadastro realizado com sucesso!'); Ajax('telefones/incluir', 'conteudo', '');";
			}
			$strScrp = "Ajax('telefones/gerenciar', 'conteudo', '');";
			$telefones->Salvar();
		}
	}
	if($_GET[acao] == "editar") {
		$strLoCase = $LANG['useful_telephones']['editing'];
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$telefones->LoadTelefones($_GET[codigo]);
		$row = $telefones->RetornaTodosDados();
	} else {		
		if($j == 0) {
			$row = "";
		} else {
			$row = $_POST;
			$row[nome] = $_POST[nom];
		}
		$strLoCase = $LANG['useful_telephones']['including'];
	}
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>
<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-phone-alt"></span> <b><?php echo $LANG['useful_telephones']['useful_telephones']?> - <?php echo $strLoCase?> </b></div>
  <div class="panel-body">

 
<div class="conteudo" id="table dados"><br>

  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="telefones/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><span class="style1"><?php echo $LANG['useful_telephones']['contact_information']?></span></legend>
        <table class="table">
          <tr>
            <td><?php echo $r[1]?>* <?php echo $LANG['useful_telephones']['name']?> <br />
                <label>
                  <input name="nom" value="<?php echo utf8_encode($row[nome])?>" type="text" class="form-control" <?php echo $disable?> id="nom" size="50" maxlength="80" />
                </label>
            </td>
            <td><?php echo $LANG['useful_telephones']['address1']?><br />
              <input name="endereco" value="<?php echo utf8_encode($row[endereco])?>" type="text" class="form-control" <?php echo $disable?> id="endereco" size="50" maxlength="150" /></td>
            <td><?php echo $LANG['useful_telephones']['address2']?><br />
              <input name="bairro" value="<?php echo utf8_encode($row[bairro])?>" type="text" class="form-control" <?php echo $disable?> id="bairro" /></td>
          
            <td><?php echo $LANG['useful_telephones']['city']?><br />
                <input name="cidade" value="<?php echo utf8_encode($row[cidade])?>" <?php echo $disable?> type="text" class="form-control" <?php echo $disable?> id="cidade" size="30" maxlength="50" />
            </td>
          </tr>
          <tr>
            <td><?php echo $LANG['useful_telephones']['state']?><br />
                <input name="estado" value="<?php echo utf8_encode($row[estado])?>" <?php echo $disable?> type="text" class="form-control" <?php echo $disable?> id="estado" maxlength="50" />
            </td>
          
            <td><?php echo $LANG['useful_telephones']['country']?><br />
                <input name="pais" value="<?php echo utf8_encode($row[pais])?>" <?php echo $disable?> type="text" class="form-control" <?php echo $disable?> id="pais" size="30" maxlength="50" />
            </td>

            <td><?php echo $LANG['useful_telephones']['zip']?><br />
              <input name="cep" value="<?php echo $row[cep]?>" type="text" class="form-control" <?php echo $disable?> id="cep" size="10" maxlength="9" onKeypress="return Ajusta_CEP(this, event);" /></td>
            <td><?php echo $LANG['useful_telephones']['cellphone']?><br />
              <input name="celular" value="<?php echo $row[celular]?>" type="text" class="form-control" <?php echo $disable?> id="celular" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?php echo $r[8]?>* <?php echo $LANG['useful_telephones']['phone1']?><br />
              <input name="telefone1" value="<?php echo $row[telefone1]?>" type="text" class="form-control" <?php echo $disable?> id="telefone1" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?php echo $LANG['useful_telephones']['phone2']?><br />
              <input name="telefone2" value="<?php echo $row[telefone2]?>" type="text" class="form-control" <?php echo $disable?> id="telefone2" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          
            <td><?php echo $LANG['useful_telephones']['email']?><br />
              <input name="email" value="<?php echo $row[email]?>" type="text" class="form-control" <?php echo $disable?> id="email" size="40" /></td>
            <td><?php echo $LANG['useful_telephones']['website']?> <br />
              <input name="website" value="<?php echo $row[website]?>" type="text" class="form-control" <?php echo $disable?> id="site" size="40" /></td>
          </tr>
         
        </table>
        </fieldset>
		<br />
        <div align="center"><br />
          <button name="Salvar" type="submit" class="btn btn-success" <?php echo $disable?> id="Salvar"><span class=" glyphicon glyphicon-ok"></span> <?php echo $LANG['useful_telephones']['save']?></button>
        </div>
      </form>      </td>
    </tr>
  </table>
</div>
</div>
<script>
  document.getElementById('nom').focus();
</script>
