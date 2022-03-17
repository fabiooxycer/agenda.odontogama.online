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
if(($_GET['codigo'] != '' && !verifica_nivel('funcionarios', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('funcionarios', 'I'))) {
	$disable = 'disabled';
	$disable2 = $disable;
	if($_GET['codigo'] == $_SESSION['codigo']) {
		$disable2 = '';
	}
}
$funcionario = new TFuncionarios();
if(isset($_POST[Salvar])) {
	if($_POST[sosenha] == 'true') {
		if($_POST[senha] != '') {
			if($_POST[senha] != $_POST[confsenha]) {
				$j++;
				$r[29] = '<font color="#FF0000">';
				$r[30] = '<font color="#FF0000">';
			}
			$senha = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `funcionarios` WHERE `codigo` = '".$_GET[codigo]."'"));
			if(md5($_POST[senhaatual]) != $senha[senha] && (checknivel('Dentista') || checknivel('Funcionario'))) {
				$j++;
				$r[28] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			$funcionario->LoadFuncionario($_GET[codigo]);
			$strScrp = "Ajax('funcionarios/gerenciar', 'conteudo', '');";	
			if($_POST[senha] != "") {
				$funcionario->SetDados('senha', md5($_POST[senha]));
			}
			$funcionario->Salvar();
		}
	} else {
		$obrigatorios[1] = 'nom';
		$obrigatorios[] = 'funcao1';
		$obrigatorios[] = 'login';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
				$j++;
				$r[$i] = '<font color="#FF0000">';
			}
		}
		if($_POST[senha] != $_POST[confsenha] || $_POST[senha] == "" && $_GET[acao] != "editar") {
			$j++;
			$r[28] = '<font color="#FF0000"> *';
			$r[29] = '<font color="#FF0000"> *';
		}
		if($_POST[senha] != '' && $_GET[acao] == 'editar') {
			if($_POST[senha] != $_POST[confsenha]) {
				$j++;
				$r[29] = '<font color="#FF0000">';
				$r[30] = '<font color="#FF0000">';
			}
			$senha = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `funcionarios` WHERE `codigo` = '".$_GET[codigo]."'"));
			if(md5($_POST[senhaatual]) != $senha[senha] && (checknivel('Dentista') || checknivel('Funcionario'))) {
				$j++;
				$r[28] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			if($_GET[acao] == "editar") {
				$funcionario->LoadFuncionario($_GET['codigo']);
				$strScrp = "Ajax('funcionarios/gerenciar', 'conteudo', '');";
			}

			$funcionario->SetDados('nome', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nom']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$funcionario->SetDados('cpf', $_POST[cpf]);
			if($_POST[senha] != "") {
				$funcionario->SetDados('senha', md5($_POST[senha]));
			}
			$funcionario->SetDados('login', $_POST[login]);
			$funcionario->SetDados('rg', $_POST[rg]);
			$funcionario->SetDados('estadocivil', $_POST[estadocivil]);
			$funcionario->SetDados('endereco', $_POST[endereco]);
			$funcionario->SetDados('bairro', $_POST[bairro]);
			$funcionario->SetDados('cidade', $_POST[cidade]);
			$funcionario->SetDados('estado', $_POST[estado]);
			$funcionario->SetDados('pais', $_POST[pais]);
			$funcionario->SetDados('cep', $_POST[cep]);
			$funcionario->SetDados('nascimento', converte_data($_POST[nascimento], 1));
			$funcionario->SetDados('telefone1', $_POST[telefone1]);
			$funcionario->SetDados('telefone2', $_POST[telefone2]);
			$funcionario->SetDados('celular', $_POST[celular]);
			$funcionario->SetDados('sexo', $_POST[sexo]);
			$funcionario->SetDados('email', $_POST[email]);
			$funcionario->SetDados('nomemae', $_POST[nomemae]);
			$funcionario->SetDados('nascimentomae', converte_data($_POST[nascimentomae], 1));
			$funcionario->SetDados('nomepai', $_POST[nomepai]);
			$funcionario->SetDados('nascimentopai', converte_data($_POST[nascimentopai], 1));
			$funcionario->SetDados('enderecofamiliar', $_POST[enderecofamiliar]);
			$funcionario->SetDados('funcao1', $_POST[funcao1]);
			$funcionario->SetDados('funcao2', $_POST[funcao2]);
			$funcionario->SetDados('admissao', converte_data($_POST[admissao], 1));
			$funcionario->SetDados('demissao', converte_data($_POST[demissao], 1));
			$funcionario->SetDados('observacoes', $_POST[observacoes]);
			$funcionario->SetDados('ativo', $_POST[ativo]);
			$funcionario->SetDados('usuario', $_POST[usuario]);
			if($_GET[acao] != "editar") {
				$funcionario->SalvarNovo();
			}
			$funcionario->Salvar();
			$strScrp = "Ajax('funcionarios/gerenciar', 'conteudo', 'codigo=".$_POST['codigo']."&acao=editar');";
		}
	}
}
if($_GET[acao] == "editar") {
	$strLoCase = $LANG['employee']['editing'];
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$funcionario->LoadFuncionario($_GET[codigo]);
	$row = $funcionario->RetornaTodosDados();
	$row[nascimento] = converte_data($row[nascimento], 2);
	$row[nascimentomae] = converte_data($row[nascimentomae], 2);
	$row[nascimentopai] = converte_data($row[nascimentopai], 2);
	$row[admissao] = converte_data($row[admissao], 2);
	$row[demissao] = converte_data($row[demissao], 2);
} else {
	if(checknivel('Dentista') || checknivel('Funcionario')) {
		die('<script>alert(\''.substr($frase_adm, 12).'\'); Ajax(\'funcionarios/gerenciar\', \'conteudo\', \'\')</script>');
	}
	if($j == 0) {
		$row = "";
	} else {
		$row = $_POST;
		$row[nome] = $_POST[nom];
	}
	$strLoCase = $LANG['employee']['including'];
}
if(isset($strScrp)) {
	echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
	die();	
}


?>
<div class="panel panel-default" id="conteudo_central">
	<div class="panel-heading"><span class="  glyphicon glyphicon-share-alt"></span> <b><?php echo $LANG['employee']['manage_employee']?> - <?php echo $strLoCase?></b></div>
	<div class="panel-body">

		<table class="table">
			<tr>
				<td>
					<form id="form2" name="form2" method="POST" action="funcionarios/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
						<legend><span class="style1"><?php echo $LANG['employee']['personal_information']?></span></legend>
						<table class="table">
							
							<td><?php echo $r[1]?>* <?php echo $LANG['employee']['name']?><br />
								<label>
									<input name="nom" value="<?php echo utf8_encode($row[nome])?>" <?php echo $disable?> type="text" class="form-control" id="nom" size="50" maxlength="80" />
								</label>
							</td>
							<td><?php echo $LANG['employee']['document1']?><br />
								<input name="cpf" value="<?php echo utf8_encode($row[cpf])?>" type="text" class="form-control" id="cpf" maxlength="50" />
							</td>
            <!--<td ><br />
    		<iframe height="300" scrolling="No" name="foto_frame" id="foto_frame" width="150" src="funcionarios/fotos.php?codigo=<?php echo $row[codigo]?><?php echo (($_GET[acao] != "editar")?'&disabled=yes':'')?>" frameborder="0"></iframe>
    	</td>-->
    	
    	<td><?php echo $LANG['employee']['document2']?><br />
    		<input name="rg" value="<?php echo $row[rg]?>" <?php echo $disable?> type="text" class="form-control" id="rg" /></td>
    		<td><?php echo $LANG['employee']['relationship_status']?><br /><select name="estadocivil" class="form-control" <?php echo $disable?> class="forms" id="estadocivil">
    			<?php
    			$valores = array('solteiro' => $LANG['employee']['single'], 'casado' => $LANG['employee']['married'], 'divorciado' => $LANG['employee']['divorced'], 'viuvo' => $LANG['employee']['widowed']);
    			foreach($valores as $chave => $valor) {
    				if($row[estadocivil] == $chave) {
    					echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
    				} else {
    					echo '<option value="'.$chave.'">'.$valor.'</option>';
    				}
    			}
    			?>       
    		</select></td>
    	</tr>
    	<tr>
    		<td><?php echo $LANG['employee']['zip']?><br />
    			<input name="cep" value="<?php echo $row[cep]?>" <?php echo $disable?> type="text" class="form-control" id="cep" size="10" onKeypress="return Ajusta_CEP(this, event);" /></td>
    			
    			<td><?php echo $LANG['employee']['address2']?><br />
    				<input name="bairro" value="<?php echo utf8_encode($row[bairro])?>" <?php echo $disable?> type="text" class="form-control" id="bairro" /></td>
    				
    				<td><?php echo $LANG['employee']['city']?><br />
    					<input name="cidade" value="<?php echo utf8_encode($row[cidade])?>" <?php echo $disable?> type="text" class="form-control" id="cidade" size="30" maxlength="50" />
    				</td>
    				<td><?php echo $LANG['employee']['state']?><br />
    					<input name="estado" value="<?php echo utf8_encode($row[estado])?>" <?php echo $disable?> type="text" class="form-control" id="estado" maxlength="50" />
    				</td>
    			</tr>
    			<tr>
    				<td><?php echo $LANG['employee']['country']?><br />
    					<input name="pais" value="<?php echo utf8_encode($row[pais])?>" <?php echo $disable?> type="text" class="form-control" id="pais" size="30" />
    				</td>

    				<td><?php echo $LANG['employee']['address1']?><br />
    					<input name="endereco" value="<?php echo utf8_encode($row[endereco])?>" <?php echo $disable?> type="text" class="form-control" id="endereco" size="50" maxlength="150" /></td>

    					<td><?php echo $LANG['employee']['birthdate']?><br />
    						<input name="nascimento" value="<?php echo $row[nascimento]?>" <?php echo $disable?> type="text" class="form-control" id="nascimento" onKeypress="return Ajusta_Data(this, event);" /></td>
    						
    						<td><?php echo $LANG['employee']['phone1']?><br />
    							<input name="telefone1" value="<?php echo $row[telefone1]?>" <?php echo $disable?> type="text" class="form-control" id="telefone1" onKeypress="return Ajusta_Telefone(this, event);" /></td>
    						</tr>
    						<tr>
    							<td><?php echo $LANG['employee']['phone2']?><br />
    								<input name="telefone2" value="<?php echo $row[telefone2]?>" <?php echo $disable?> type="text" class="form-control" id="telefone2" onKeypress="return Ajusta_Telefone(this, event);" /></td>
    								
    								<td><?php echo $LANG['employee']['cellphone']?><br />
    									<input name="celular" value="<?php echo $row[celular]?>" <?php echo $disable?> type="text" class="form-control" id="celular" onKeypress="return Ajusta_Telefone(this, event);" /></td>
    									<td><?php echo $LANG['employee']['gender']?><br /><select name="sexo" <?php echo $disable?> class="form-control" id="sexo">
    										<?php
    										$valores = array('Masculino' => $LANG['employee']['male'], 'Feminino' => $LANG['employee']['female']);
    										foreach($valores as $chave => $valor) {
    											if($row[sexo] == $chave) {
    												echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
    											} else {
    												echo '<option value="'.$chave.'">'.$valor.'</option>';
    											}
    										}
    										?>       
    									</select></td>
    									
    									<td><?php echo $LANG['employee']['email']?><br />
    										<input name="email" value="<?php echo utf8_encode($row[email])?>" <?php echo $disable?> type="text" class="form-control" id="email" size="50" /></td>
    									</td>
    								</tr>
    								
    							</table>
    						</fieldset>
    						<br />
    						 <fieldset>
    							<legend><span class="style1"><?php echo $LANG['employee']['familiar_information']?></span></legend>

    							<table class="table">
    								<tr>
    									<td><?php echo $LANG['employee']['mothers_name']?><br />
    										
    										<input name="nomemae" value="<?php echo utf8_encode($row[nomemae])?>" <?php echo $disable?> type="text" class="form-control" id="nome_mae" size="50" maxlength="80" />
    									</td>
    									<td><?php echo $LANG['employee']['birthdate']?><br />
    										<input name="nascimentomae" value="<?php echo $row[nascimentomae]?>" <?php echo $disable?> type="text" class="form-control" id="nascimento_mae" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
    									</tr>
    									<tr>
    										<td><?php echo $LANG['employee']['fathers_name']?><br />
    											<input name="nomepai" value="<?php echo utf8_encode($row[nomepai])?>" <?php echo $disable?> type="text" class="form-control" id="nome_pai" size="50" maxlength="80" /></td>
    											<td><?php echo $LANG['employee']['birthdate']?><br />
    												<input name="nascimentopai" value="<?php echo $row[nascimentopai]?>" <?php echo $disable?> type="text" class="form-control" id="nascimento_pai" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
    											</tr>
    											<tr>
    												<td colspan="2"><?php echo $LANG['employee']['complete_address_in_case_of_be_different_from_personal']?><br />
    													<input name="enderecofamiliar" value="<?php echo utf8_encode($row[enderecofamiliar])?>" <?php echo $disable?> type="text" class="form-control" id="endereco_familiar" size="78" maxlength="220" />                <br /></td>
    												</tr>
    											</table>
    										</fieldset>

    										 <fieldset>
    											<legend><span class="style1"><?php echo $LANG['employee']['professional_information']?> </span></legend>

    											<table class="table">
    												<tr>
    													<td><?php echo $r[21]?>* <?php echo $LANG['employee']['main_function']?> <br />
    														
    														<input name="funcao1" value="<?php echo utf8_encode($row[funcao1])?>" <?php echo $disable?> type="text" class="form-control" id="funcao1" size="40" maxlength="80" />
    													</td>
    													<td><?php echo $LANG['employee']['secondary_function']?><br />
    														
    														<input name="funcao2" value="<?php echo utf8_encode($row[funcao2])?>" <?php echo $disable?> type="text" class="form-control" id="funcao2" size="40" maxlength="80" />
    													</td>
    												</tr>
    												<tr>
    													<td><?php echo $LANG['employee']['admission_date']?><br />
    														<input name="admissao" value="<?php echo $row[admissao]?>" <?php echo $disable?> type="text" class="form-control" id="data_admissao" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" />
    													</td>
    													<td><?php echo $LANG['employee']['resignation_date']?><br />
    														<input name="demissao" value="<?php echo $row[demissao]?>" <?php echo $disable?> type="text" class="form-control" id="data_demissao" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
    													</tr>
    													<tr>
    														<td valign="top"><br /><?php echo $LANG['employee']['active_on_clinic']?>?<br />
    															
    															<select name="ativo" <?php echo $disable?> class="form-control" id="ativo">
    																<?php
    																$valores = array('Sim' => $LANG['employee']['yes'], 'Não' => $LANG['employee']['no']);
    																foreach($valores as $chave => $valor) {
    																	if($row[ativo] == $chave) {
    																		echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
    																	} else {
    																		echo '<option value="'.$chave.'">'.$valor.'</option>';
    																	}
    																}
    																?>       
    															</select>
    														</td>
    													</tr>
    													<tr>
    														<td colspan="2"><?php echo $LANG['employee']['comments']?><br />
    															
    															<textarea name="observacoes" class="form-control" <?php echo $disable?> cols="25" rows="4"><?php echo utf8_encode($row[observacoes])?></textarea>
    														</td>
    														
    													</tr>
    													
    												</table>
    											</fieldset>	
    											<br />
    											<fieldset>
    												<legend><span class="style1"><?php echo $LANG['employee']['personal_access_information']?></span></legend>
    												<table class="table" style="max-width:500px;" align="center">
    													<tr>
    														<td><?php echo $r[27]?><?php echo $LANG['employee']['login']?> <br />
    															<input name="usuario" value="<?php echo utf8_encode($row[usuario])?>" <?php echo $disable?> type="text" class="form-control" id="usuario" maxlength="15" />
    														</td>
    													</tr>
    													<?php
    													$x = 28;
    													if($disable == 'disabled' && $disable2 == '') {
    														echo '<input type="hidden" name="sosenha" value="true">';
    													}
    													if($_GET[acao] == 'editar' && (checknivel('Dentista') || checknivel('Funcionario'))) {
    														$nova = "Nova ";
    														?>
    														<tr>
    															<td><?php echo $r[28]?><?php echo $LANG['employee']['current_password']?> <br />
    																<input name="senhaatual" value="" <?php echo $disable2?> type="password" class="form-control" id="senhaatual" maxlength="32" />
    															</td>
    														</tr>
    														<?php
    														$x++;
    													}
    													?>
    													<tr>
    														<td><?php echo $r[$x]?><?php echo $LANG['employee']['new_password']?> <br />
    															<input name="senha" value="" <?php echo $disable2?> type="password" class="form-control" id="senha" maxlength="32" />
    														</td>
    													</tr>
    													<tr>
    														<td><?php echo $r[($x+1)]?><?php echo $LANG['employee']['retype_new_password']?><br />
    															<input name="confsenha" value="" <?php echo $disable2?> type="password" class="form-control" id="confsenha" maxlength="32" />
    														</td>
    													</tr>
    													
    												</table>
    											</fieldset>
    											<div align="center"><br />
    												<button name="Salvar" type="submit" class="btn btn-primary" id="Salvar" <?php echo $disable2?>><span class=" glyphicon glyphicon-ok"></span> <?php echo $LANG['employee']['save']?></button>
    												<a href="relatorios/funcionario.php?codigo=<?php echo $row['codigo']?>" target="_blank"><button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['employee']['print_sheet']?></button></a>
    											</div>
    										</form>      </td>
    									</tr>
    									
    								</table>
    							</div>
    						</div>
    						<script>
    							document.getElementById('nom').focus();
    						</script>
