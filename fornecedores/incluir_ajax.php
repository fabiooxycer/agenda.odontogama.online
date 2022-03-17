<?php
  
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	if(($_GET['codigo'] != '' && !verifica_nivel('fornecedores', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('fornecedores', 'I'))) {
        $disable = 'disabled';
    }
	$fornecedor = new TFornecedores();
	if(isset($_POST[Salvar])) {
		$obrigatorios[1] = 'nomefantasia';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
			    $j++;
				$r[$j] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			if($_GET[acao] == "editar") {
				$fornecedor->LoadFornecedores($_GET[codigo]);
				$strScrp = "Ajax('fornecedores/gerenciar', 'conteudo', '');";
			}
			$fornecedor->SetDados('nomefantasia', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomefantasia']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$fornecedor->SetDados('cpf', $_POST[cpf]);
			$fornecedor->SetDados('razaosocial', utf8_decode ( htmlspecialchars( utf8_encode($_POST['razaosocial']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$fornecedor->SetDados('atuacao', utf8_decode ( htmlspecialchars( utf8_encode($_POST['atuacao']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$fornecedor->SetDados('endereco', utf8_decode ( htmlspecialchars( utf8_encode($_POST['endereco']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$fornecedor->SetDados('bairro', utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$fornecedor->SetDados('cidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$fornecedor->SetDados('estado', $_POST[estado]);
			$fornecedor->SetDados('pais', $_POST[pais]);
			$fornecedor->SetDados('cep', $_POST[cep]);
			$fornecedor->SetDados('celular', $_POST[celular]);
			$fornecedor->SetDados('telefone1', $_POST[telefone1]);
			$fornecedor->SetDados('telefone2', $_POST[telefone2]);
			$fornecedor->SetDados('inscricaoestadual', $_POST[inscricaoestadual]);
			$fornecedor->SetDados('website', $_POST[website]);
			$fornecedor->SetDados('email', $_POST[email]);
			$fornecedor->SetDados('nomerepresentante', $_POST[nomerepresentante]);
			$fornecedor->SetDados('apelidorepresentante', $_POST[apelidorepresentante]);
			$fornecedor->SetDados('emailrepresentante', $_POST[emailrepresentante]);
			$fornecedor->SetDados('celularrepresentante', $_POST[celularrepresentante]);
			$fornecedor->SetDados('telefone1representante', $_POST[telefone1representante]);
			$fornecedor->SetDados('telefone2representante', $_POST[telefone2representante]);
			$fornecedor->SetDados('banco', $_POST[banco]);
			$fornecedor->SetDados('agencia', $_POST[agencia]);
			$fornecedor->SetDados('conta', $_POST[conta]);
			$fornecedor->SetDados('favorecido', $_POST[favorecido]);
			if($_GET[acao] != "editar") {
				$fornecedor->SalvarNovo();
			}
			$fornecedor->Salvar();
    		$strScrp = "Ajax('fornecedores/gerenciar', 'conteudo', '');";
		}
	}
	if($_GET[acao] == "editar") {
		$strLoCase = $LANG['suppliers']['editing'];
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$fornecedor->LoadFornecedores($_GET[codigo]);
		$row = $fornecedor->RetornaTodosDados();
		$row[nascimento] = converte_data($row[nascimento], 2);
		$row[nascimentomae] = converte_data($row[nascimentomae], 2);
		$row[nascimentopai] = converte_data($row[nascimentopai], 2);
		$row[admissao] = converte_data($row[admissao], 2);
		$row[demissao] = converte_data($row[demissao], 2);
		if(strlen($row['cpf']) == 11 || $row['cpf'] == '') {
            $cpf_cnpj = 'cpf';
            $row['cpf'] = ajusta_cpf($row['cpf'], 2);
            $chk['cpfcnpj']['cpf'] = 'checked';
		} elseif(strlen($row['cpf']) == 14) {
            $cpf_cnpj = 'cnpj';
            $row['cpf'] = ajusta_cnpj($row['cpf'], 2);
            $chk['cpfcnpj']['cnpj'] = 'checked';
		}
	} else {
		/*if(checknivel('Dentista') || checknivel('Funcionario')) {
			die('<script>alert(\''.substr($frase_adm, 12).'\'); Ajax(\'fornecedores/gerenciar\', \'conteudo\', \'\')</script>');
		}*/
		if($j == 0) {
			$row = "";
		} else {
			$row = $_POST;
		}
		$strLoCase = $LANG['suppliers']['including'];
	}
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}

?>
<div class="panel panel-default">
  <div class="panel-heading"><span class="  glyphicon glyphicon-bookmark"></span> <b><?php echo $LANG['suppliers']['manage_suppliers']?></b></div>
  <div class="panel-body">

  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="fornecedores/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><span class="style1"><?php echo $LANG['suppliers']['supplier_information']?> </span></legend>
        <table class="table">
          <tr>
            <td><?php echo $r[1]?>* <?php echo $LANG['suppliers']['company']?> <br />
               
                  <input name="nomefantasia" value="<?php echo utf8_encode($row[nomefantasia])?>" <?php echo $disable?> type="text" class="form-control" id="nomefantasia" size="50" maxlength="80" />
                </td>
            <td><?php echo $LANG['suppliers']['document1']?><br />
              <input name="cpf" value="<?php echo $row[cpf]?>" <?php echo $disable?> type="text" class="form-control" id="cpf" size="30" maxlength="18" onKeypress="return Ajusta_CPFCNPJ(this, event, document.getElementById('cpf_cnpj').value);" /></td>
          
            <td><?php echo $LANG['suppliers']['legal_name']?><br />
              <input name="razaosocial" value="<?php echo utf8_encode($row[razaosocial])?>" <?php echo $disable?> type="text" class="form-control" id="razaosocial" size="50" /></td>
            <td><?php echo $LANG['suppliers']['operation_area']?><br />
              <input name="atuacao" value="<?php echo utf8_encode($row[atuacao])?>" <?php echo $disable?> type="text" class="form-control" id="atuacao" size="40" /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['suppliers']['address1']?><br />
              <input name="endereco" value="<?php echo utf8_encode($row[endereco])?>" <?php echo $disable?> type="text" class="form-control" id="endereco" size="50" maxlength="150" /></td>
            <td><?php echo $LANG['suppliers']['address2']?><br />
              <input name="bairro" value="<?php echo utf8_encode($row[bairro])?>" <?php echo $disable?> type="text" class="form-control" id="bairro" /></td>
            
            <td><?php echo $LANG['suppliers']['city']?><br />
                <input name="cidade" value="<?php echo utf8_encode($row[cidade])?>" <?php echo $disable?> type="text" class="form-control" id="cidade" size="30" maxlength="50" />
              <br /></td>
            <td><?php echo $LANG['suppliers']['state']?><br />
                <input name="estado" value="<?php echo utf8_encode($row[estado])?>" <?php echo $disable?> type="text" class="form-control" id="estado" maxlength="50" />
            </td>
          </tr>
          <tr>
            <td><?php echo $LANG['suppliers']['country']?><br />
                <input name="pais" value="<?php echo utf8_encode($row[pais])?>" <?php echo $disable?> type="text" class="form-control" id="pais" size="30" maxlength="50" />
              </td>
            
      
            <td><?php echo $LANG['suppliers']['zip']?><br />
              <input name="cep" value="<?php echo $row[cep]?>" <?php echo $disable?> type="text" class="form-control" id="cep" size="10" onKeypress="return Ajusta_CEP(this, event);" /></td>
            <td><?php echo $LANG['suppliers']['cellphone']?><br />
              <input name="celular" value="<?php echo $row[celular]?>" <?php echo $disable?> type="text" class="form-control" id="celular" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          
            <td><?php echo $LANG['suppliers']['phone1']?><br />
              <input name="telefone1" value="<?php echo $row[telefone1]?>" <?php echo $disable?> type="text" class="form-control" id="telefone1" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            </tr>
            <tr>
            <td><?php echo $LANG['suppliers']['phone2']?><br />
              <input name="telefone2" value="<?php echo $row[telefone2]?>" <?php echo $disable?> type="text" class="form-control" id="telefone2" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          
            <td><?php echo $LANG['suppliers']['document2']?><br />
              <input name="inscricaoestadual" value="<?php echo $row[inscricaoestadual]?>" <?php echo $disable?> type="text" class="form-control" id="ie" size="25" /></td>
            <td><?php echo $LANG['suppliers']['website']?><br />
              <input name="website" value="<?php echo $row[website]?>" <?php echo $disable?> type="text" class="form-control" id="site" size="40" /></td>
          
            <td><?php echo $LANG['suppliers']['email']?><br />
              <input name="email" value="<?php echo $row[email]?>" <?php echo $disable?> type="text" class="form-control" id="email" size="40" /></td>
           
          </tr>
          
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?php echo $LANG['suppliers']['representative_information_contact_person']?></span></legend>
        <table class="table">
          
          <tr>
            <td><?php echo $LANG['suppliers']['representative_name_contact_person']?><br />
                
                  <input name="nomerepresentante" value="<?php echo utf8_encode($row[nomerepresentante])?>" <?php echo $disable?> type="text" class="form-control" id="nome" size="50" maxlength="80" />
                </td>
            <td width="210"><?php echo $LANG['suppliers']['nickname']?><br />
              <input name="apelidorepresentante" value="<?php echo utf8_encode($row[apelidorepresentante])?>" <?php echo $disable?> type="text" class="form-control" id="apelido" /></td>
          
            <td><?php echo $LANG['suppliers']['email']?><br />
                <input name="emailrepresentante" value="<?php echo $row[emailrepresentante]?>" <?php echo $disable?> type="text" class="form-control" id="email" size="50" maxlength="100" /></td>
            <td><?php echo $LANG['suppliers']['cellphone']?><br />
                <input name="celularrepresentante" value="<?php echo $row[celularrepresentante]?>" <?php echo $disable?> type="text" class="form-control" id="celularrep" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['suppliers']['phone1']?><br />
                <input name="telefone1representante" value="<?php echo $row[telefone1representante]?>" <?php echo $disable?> type="text" class="form-control" id="telefonerep"  onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?php echo $LANG['suppliers']['phone2']?><br />
                <input name="telefone2representante" value="<?php echo $row[telefone2representante]?>" <?php echo $disable?> type="text" class="form-control" id="telefone1rep"  onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?php echo $LANG['suppliers']['bank_information']?></span></legend>
        <table class="table">
         
          <tr>
            <td><?php echo $LANG['suppliers']['bank']?> <br />
                
                  <input name="banco1" value="<?php echo utf8_encode($row['banco1'])?>" <?php echo $disable?> type="text" class="form-control" id="banco1" size="50" maxlength="50" />
                </td>
            
          
            <td><?php echo $LANG['suppliers']['agency']?><br />
                <input name="agencia1" value="<?php echo $row['agencia1']?>" <?php echo $disable?> type="text" class="form-control" id="agencia1" size="50"  /></td>
            <td><?php echo $LANG['suppliers']['account']?><br />
                <input name="conta1" value="<?php echo $row['conta1']?>" <?php echo $disable?> type="text" class="form-control" id="conta1"  /></td>
          
            <td><?php echo $LANG['suppliers']['account_holder']?><br />
                
                  <input name="favorecido1" value="<?php echo utf8_encode($row['favorecido1'])?>" <?php echo $disable?> type="text" class="form-control" id="favorecido1" size="50" maxlength="50" />
                </td>
            
          </tr>

          
          <tr>
            <td><?php echo $LANG['suppliers']['bank']?> <br />
                
                  <input name="banco2" value="<?php echo utf8_encode($row['banco2'])?>" <?php echo $disable?> type="text" class="form-control" id="banco2" size="50" maxlength="50" />
                </td>
            
        
            <td><?php echo $LANG['suppliers']['agency']?><br />
                <input name="agencia2" value="<?php echo $row['agencia2']?>" <?php echo $disable?> type="text" class="form-control" id="agencia2" size="50" maxlength="15" /></td>
            <td><?php echo $LANG['suppliers']['account']?><br />
                <input name="conta2" value="<?php echo $row['conta2']?>" <?php echo $disable?> type="text" class="form-control" id="conta2" /></td>
          
            <td><?php echo $LANG['suppliers']['account_holder']?><br />
                
                  <input name="favorecido2" value="<?php echo utf8_encode($row['favorecido2'])?>" <?php echo $disable?> type="text" class="form-control" id="favorecido2" size="50" maxlength="50" />
                </td>
          </tr>

          
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?php echo $LANG['suppliers']['comments']?></span></legend>
        <table class="table">
          
          <tr>
            <td><?php echo $LANG['suppliers']['comments']?> <br />
                  <textarea name="observacoes" <?php echo $disable?> class="form-control" id="observacoes" cols="50" rows="8"><?php echo utf8_encode($row['observacoes'])?></textarea>
                </td>
            
          </tr>
        </table>
        </fieldset>
		<br />
        <div align="center"><br />
<button name="Salvar" type="submit" class="btn btn-success" id="Salvar" <?php echo $disable2?>><span class=" glyphicon glyphicon-ok"></span> <?php echo $LANG['employee']['save']?></button>        </div>
      </form>      </td>
    </tr>
  </table>
</div>
</div>
<script>
  document.getElementById('nomefantasia').focus();
</script>
