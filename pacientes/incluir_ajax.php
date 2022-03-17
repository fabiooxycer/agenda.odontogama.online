<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
header("Content-type: text/html; charset=UTF-8", true);

$sistema = new sistema(); 
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


if(!checklog()) {
	die($frase_log);
}

$requer = "required";

$paciente = new TPacientes();
if(isset($_POST['Salvar'])) {
	$_POST['tratamento'] = @implode(',', $_POST['tratamento']);
	$obrigatorios[1] = 'codigo';
	$obrigatorios[] = 'nom';
	$i = $j = 0;
	foreach($_POST as $post => $valor) {
		$i++;
		if(array_search($post, $obrigatorios) && $valor == "") {
			$r[$i] = '<font color="#FF0000">';
			$j++;
		}
	}
	if(!is_valid_codigo($_POST[codigo]) && $_GET[acao] != "editar") {
		$j++;
		$r[1] = '<font color="#FF0000">';
	}
	if($j === 0) {
		if($_GET[acao] == "editar") {
			$paciente->LoadPaciente($_POST[codigo]);
			$strScrp = "Ajax('pacientes/gerenciar', 'conteudo', '')";
		}

		$usuario = $_POST['usuario'];
		$senha = md5($_POST['senha']);
		$cpf = $_POST['cpf'];
		$codigo = $_POST['codigo'];

		mysqli_query($conn, "UPDATE funcionarios SET nome='PAC', cpf='$cpf', usuario='$usuario', senha='$senha' WHERE cpf='$cpf'")or die(mysqli_error());

		$paciente->SetDados('codigo', $_POST[codigo]);
		$paciente->SetDados('nome', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nom']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('cpf', $_POST[cpf]);
		$paciente->SetDados('rg', $_POST[rg]);
		$paciente->SetDados('estadocivil', utf8_decode ( htmlspecialchars( utf8_encode($_POST['estadocivil']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('sexo', $_POST[sexo]);
		$paciente->SetDados('etnia', $_POST[etnia]);
		$paciente->SetDados('profissao', utf8_decode ( htmlspecialchars( utf8_encode($_POST['profissao']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('naturalidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['naturalidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('nacionalidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nacionalidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('nascimento', converte_data($_POST[nascimento], 1));
		$paciente->SetDados('endereco', utf8_decode ( htmlspecialchars( utf8_encode($_POST['endereco']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('bairro', utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('cidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('estado', $_POST[estado]);
		$paciente->SetDados('pais', utf8_decode ( htmlspecialchars( utf8_encode($_POST['pais']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('falecido', $_POST[falecido]);
		$paciente->SetDados('cep', $_POST[cep]);
		$paciente->SetDados('celular', $_POST[celular]);
		$paciente->SetDados('telefone1', $_POST[telefone1]);
		$paciente->SetDados('telefone2', $_POST[telefone2]);
		$paciente->SetDados('hobby', utf8_decode ( htmlspecialchars( utf8_encode($_POST['hobby']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('indicadopor', utf8_decode ( htmlspecialchars( utf8_encode($_POST['indicadopor']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('email', $_POST[email]);
		$paciente->SetDados('obs_etiqueta', utf8_decode ( htmlspecialchars( utf8_encode($_POST['obs_etiqueta']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('tratamento', $_POST[tratamento]);
		$paciente->SetDados('codigo_dentistaprocurado', $_POST[codigo_dentistaprocurado]);
		$paciente->SetDados('codigo_dentistaatendido', $_POST[codigo_dentistaatendido]);
		$paciente->SetDados('codigo_dentistaencaminhado', $_POST[codigo_dentistaencaminhado]);
		$paciente->SetDados('nomemae', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomemae']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('nascimentomae', converte_data($_POST[nascimentomae], 1));
		$paciente->SetDados('profissaomae', utf8_decode ( htmlspecialchars( utf8_encode($_POST['profissaomae']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('nomepai', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomepai']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('nascimentopai', converte_data($_POST[nascimentopai], 1));
		$paciente->SetDados('profissaopai', utf8_decode ( htmlspecialchars( utf8_encode($_POST['profissaopai']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('telefone1pais', $_POST[telefone1pais]);
		$paciente->SetDados('telefone2pais', $_POST[telefone2pais]);
		$paciente->SetDados('enderecofamiliar', $_POST[enderecofamiliar]);
		$paciente->SetDados('datacadastro', converte_data($_POST[datacadastro], 1));
		$paciente->SetDados('dataatualizacao', date(Y.'-'.m.'-'.d));
		$paciente->SetDados('status', $_POST[status]);
		$paciente->SetDados('objetivo', utf8_decode ( htmlspecialchars( utf8_encode($_POST['objetivo']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('observacoes', utf8_decode ( htmlspecialchars( utf8_encode($_POST['observacoes']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
		$paciente->SetDados('codigo_convenio', $_POST[convenio]);
		$paciente->SetDados('outros', $_POST[outros]);
		$paciente->SetDados('matricula', $_POST[matricula]);
		$paciente->SetDados('titular', $_POST[titular]);
		$paciente->SetDados('validadeconvenio', $_POST[validadeconvenio]);



		if($_GET[acao] != "editar") {

			$usuario = $_POST['usuario'];
			$senha = md5($_POST['senha']);
			$cpf = $_POST['cpf'];

			if($senha != "") 
			{
				mysqli_query($conn, "INSERT INTO funcionarios (nome, cpf, usuario, senha) VALUES ('PAC', '$cpf', '$usuario', '$senha')");
			}

			$paciente->SalvarNovo();
			$objetivo = new TExObjetivo();
			$objetivo->SetDados('codigo_paciente', $_POST['codigo']);
			$objetivo->SalvarNovo();
			$objetivo = new TInquerito();
			$objetivo->SetDados('codigo_paciente', $_POST['codigo']);
			$objetivo->SalvarNovo();
			$objetivo = new TAtestado();
			$objetivo->Codigo_Paciente = $_POST['codigo'];
			$objetivo->SalvarNovo();
			$objetivo = new TReceita();
			$objetivo->Codigo_Paciente = $_POST['codigo'];
			$objetivo->SalvarNovo();
			$objetivo = new TExame();
			$objetivo->Codigo_Paciente = $_POST['codigo'];
			$objetivo->SalvarNovo();
			$objetivo = new TEncaminhamento();
			$objetivo->Codigo_Paciente = $_POST['codigo'];
			$objetivo->SalvarNovo();
			$objetivo = new TLaudo();
			$objetivo->Codigo_Paciente = $_POST['codigo'];
			$objetivo->SalvarNovo();
			$objetivo = new TAgradecimento();
			$objetivo->Codigo_Paciente = $_POST['codigo'];
			$objetivo->SalvarNovo();
			$strScrp = "Ajax('pacientes/gerenciar', 'conteudo', 'codigo=".$_POST[codigo]."&acao=editar')";
		}
		$paciente->Salvar();


	}
}

if($_GET[acao] == "editar") {
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$paciente->LoadPaciente($_GET[codigo]);
	$row = $paciente->RetornaTodosDados();
	$row[nascimento] = converte_data($row[nascimento], 2);
	$row[nascimentomae] = converte_data($row[nascimentomae], 2);
	$row[nascimentopai] = converte_data($row[nascimentopai], 2);
	$row[datacadastro] = converte_data($row[datacadastro], 2);
	$strCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$strLoCase = $LANG['patients']['editing'];
	$acao = '&acao=editar';

	$requer = "";

	$usuario = mysqli_fetch_array(mysqli_query($conn, "SELECT usuario FROM funcionarios WHERE cpf='$row[cpf]'"));

} else {
	$strCase = $LANG['patients']['including'];
	$strLoCase = $LANG['patients']['including'];
	$row = $_POST;
	$row[nome] = $_POST[nom];
	if(!isset($_POST[codigo]) || $j == 0) {
		$row = "";
		$row[codigo] = $paciente->ProximoCodigo();
	} else {
		$row[codigo] = $_POST[codigo];
	}
}
if(isset($strScrp)) {
	echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
	die();	
}
?>
<script type="text/javascript">

	$(function(){
		var usr = $("#usuario").val();
		
		var senha = $("#senha").val();
		var csenha = $("#csenha").val();

		$("#Salvar").click(function(){

			if(senha != csenha)
			{
				alert("As senhas informadas não combinam.");
				$("#senha").focus();
				return false;
			}

		});

		$("#usuario").focusout(function()
		{
			if(usr != "")
			{
				$.post("validarUsuario.php", {usuario: usr}, function(ret){
					if(ret != "OK")
					{
						alert("O usuário informado já existe no sistema, informe outro nome de usuário.");
						$("#usuario").focus();
					}
				});
			}
		});
	});
</script>
<style>
	.table tbody tr td{border-top:0;}
</style>

<div class="panel panel-default">
	<div class="panel-body">
		<?php 
		$ativo_incluir = true;
		include('submenu.php'); ?>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo $LANG['patients']['manage_patients']?> - <?php echo $strCase?> </div>
	<div class="panel-body">


		<div class="conteudo" id="table dados">
			<br />

			<br>
			<form id="form2" name="form2" method="POST" action="pacientes/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>

				<table class="table">
					<tr>
						<td colspan="4" style="border-top:0;">
							<legend>Informações Pessoais</legend>
						</td>
					</tr>
					<tr>

						<td>
							<iframe height="250" scrolling="No" width="150" name="foto_frame" id="foto_frame" src="pacientes/fotos.php?codigo=<?php echo $row[codigo]?><?php echo (($_GET[acao] != "editar")?'&disabled=yes':'')?>" frameborder="0"></iframe>
						</td>

					</tr>

					<tr>

						<td><?php echo $r[1]?>* <?php echo $LANG['patients']['clinical_sheet']?><br />
							<?php
							if($_GET[acao] == "editar") {
								?>
								<input disabled value="<?php echo $row[codigo]?>" type="text" class="form-control" id="codigo" onblur="javascript:foto_frame.location.href='pacientes/fotos.php?codigo='+this.value" />
								<input name="codigo" value="<?php echo $row[codigo]?>" type="hidden" class="form-control" <?php echo $disable?> id="codigo" onblur="javascript:foto_frame.location.href='pacientes/fotos.php?codigo='+this.value">
								<?php
							} else {
								?>
								<input name="codigo" value="<?php echo $row[codigo]?>" type="text" class="form-control" <?php echo $disable?> id="codigo" onblur="javascript:foto_frame.location.href='pacientes/fotos.php?codigo='+this.value">
								<?php
							}
							?>
						</td>

						<td><?php echo $r[2]?>* <?php echo $LANG['patients']['name']?><br />
							<label>
								<input name="nom" required value="<?php echo utf8_encode($row[nome])?>" type="text" class="form-control" <?php echo $disable?> id="nom" size="50" maxlength="80" />
							</label>
							<br />
							<label></label></td>
							<td><?php echo $r[3]?><?php echo $LANG['patients']['document1']?><br />
								<input name="cpf" required value="<?php echo $row['cpf']?>" type="text" class="form-control" <?php echo $disable?> id="cpf" maxlength="50" />
							</td>

							<td><?php echo $LANG['patients']['relationship_status']?><br /><select name="estadocivil" class="form-control" <?php echo $disable?> id="estadocivil">
								<?php
								$valores = array('solteiro' => $LANG['patients']['single'], 'casado' => $LANG['patients']['married'], 'divorciado' => $LANG['patients']['divorced'], 'viuvo' => $LANG['patients']['widowed']);
								foreach($valores as $chave => $valor) {
									if($row[estadocivil] == $chave) {
										echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
									} else {
										echo '<option value="'.$chave.'">'.$valor.'</option>';
									}
								}
								?>       
							</select>            </td>

						</tr>
						<tr>

							<td><?php echo $LANG['patients']['gender']?><br />
								<select name="sexo" required class="form-control" <?php echo $disable?> id="sexo">
									<?php
									$valores = array('Masculino' => $LANG['patients']['male'], 'Feminino' => $LANG['patients']['female']);
									foreach($valores as $chave => $valor) {
										if($row[sexo] == $chave) {
											echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
										} else {
											echo '<option value="'.$chave.'">'.$valor.'</option>';
										}
									}
									?>       
								</select> </td>

								<td><?php echo $LANG['patients']['profession']?><br />
									<input name="profissao" required value="<?php echo utf8_encode($row[profissao])?>" type="text" class="form-control" <?php echo $disable?> id="profissao" /></td>



									<td><?php echo $LANG['patients']['birthdate']?><br />
										<input name="nascimento" required value="<?php echo $row[nascimento]?>" type="text" class="form-control" <?php echo $disable?> id="nascimento" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>

										<td><?php echo $LANG['patients']['address1']?><br />
											<input name="endereco" required value="<?php echo utf8_encode($row[endereco])?>" type="text" class="form-control" <?php echo $disable?> id="endereco" size="50" maxlength="150" /></td>
										</tr><tr>
										<td><?php echo $LANG['patients']['address2']?><br />
											<input name="bairro" required value="<?php echo utf8_encode($row[bairro])?>" type="text" class="form-control" <?php echo $disable?> id="bairro" /></td>

											<td><?php echo $LANG['patients']['city']?><br />
												<input name="cidade" required value="<?php echo utf8_encode($row[cidade])?>" <?php echo $disable?> type="text" class="form-control" <?php echo $disable?> id="cidade" size="30" maxlength="50" />
												<br /></td>
												<td><?php echo $LANG['patients']['state']?><br />
													<input name="estado" required value="<?php echo utf8_encode($row[estado])?>" <?php echo $disable?> type="text" class="form-control" <?php echo $disable?> id="estado" maxlength="50" />
												</td>



												<td><?php echo $LANG['patients']['zip']?><br />
													<input name="cep" required value="<?php echo $row[cep]?>" type="text" class="form-control" <?php echo $disable?> id="cep" onKeypress="return Ajusta_CEP(this, event);" /></td>

												</tr><tr>

												<td><?php echo $LANG['patients']['cellphone']?><br />
													<input name="celular" required value="<?php echo $row[celular]?>" type="text" class="form-control" <?php echo $disable?> id="celular" onKeypress="return Ajusta_Telefone(this, event);" /></td>

													<td><?php echo $LANG['patients']['residential_phone']?><br />
														<input name="telefone1" value="<?php echo $row[telefone1]?>" type="text" class="form-control" <?php echo $disable?> id="telefone1" onKeypress="return Ajusta_Telefone(this, event);" /></td>

														<td><?php echo $LANG['patients']['comercial_phone']?><br />
															<input name="telefone2" value="<?php echo $row[telefone2]?>" type="text" class="form-control" <?php echo $disable?> id="telefone2" onKeypress="return Ajusta_Telefone(this, event);" /></td>


															<td><?php echo $LANG['patients']['email']?><br />
																<input name="email" value="<?php echo utf8_encode($row[email])?>" type="email" class="form-control" <?php echo $disable?> id="email" size="50" /></td>
															</tr><tr>
															<td colspan="4"><?php echo $LANG['patients']['comments_for_label']?> <br />
																<input name="obs_etiqueta" value="<?php echo utf8_encode($row[obs_etiqueta])?>" type="text" class="form-control" <?php echo $disable?> id="obs_etiqueta" /></td>
															</tr>
														</table>
													</fieldset>
													<br /> 


        										<!--<fieldset>
        											<legend><span class="style1">Informações de acesso</span></legend>

        											<table class="table">
        												<tr>
        													<td>
        														<span>Usuário</span><br>
        														<input type="text" value="<?php echo utf8_encode($usuario[usuario]); ?>" class="form-control" id="usuario" name="usuario" required>
        													</td>
        													<td>
        														<span>Senha</span><br>
        														<input type="password" class="form-control" id="senha" name="senha" <?php echo $requer; ?>>
        													</td>
        													<td>
        														<span>Confirmar Senha</span><br>
        														<input type="password" class="form-control" id="csenha" name="cSenha" <?php echo $requer; ?>>
        													</td>
        												</tr>
        											</table>
        										</fieldset>
        										 <br />



        										<br>-->
        										<fieldset>
        											<legend><span class="style1"><?php echo $LANG['patients']['treatments_to_do']?></span></legend>

        											<table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
        												<tr>
        													<td>&nbsp;</td>
        													<td>&nbsp;</td>
        													<td>&nbsp;</td>
        													<td>&nbsp;</td>
        												</tr>
        												<tr>
        													<td><input name="tratamento[]" value="Ortodontia" <?php echo ((strpos($row[tratamento], 'Ortodontia')!== false)?'checked':'')?> type="checkbox" id="tra1" /><label for="tra1"> <?php echo $LANG['patients']['orthodonty']?></label></td>
        													<td><input name="tratamento[]" value="Implantodontia" <?php echo ((strpos($row[tratamento], 'Implantodontia')!== false)?'checked':'')?> type="checkbox" id="tra2" /><label for="tra2"> <?php echo $LANG['patients']['implantodonty']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Dentística" <?php echo ((strpos($row[tratamento], 'Dentística')!== false)?'checked':'')?> type="checkbox" id="tra3" /><label for="tra3"> <?php echo $LANG['patients']['dentistic']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Prótese" <?php echo ((strpos($row[tratamento], 'Prótese')!== false)?'checked':'')?> type="checkbox" id="tra4" /><label for="tra4"> <?php echo $LANG['patients']['prosthesis']?></label><br /></td>
        												</tr>
        												<tr>
        													<td><input name="tratamento[]" value="Odontopediatria" <?php echo ((strpos($row[tratamento], 'Odontopediatria')!== false)?'checked':'')?> type="checkbox" id="tra5" /><label for="tra5"> <?php echo $LANG['patients']['odontopediatry']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Cirurgia" <?php echo ((strpos($row[tratamento], 'Cirurgia')!== false)?'checked':'')?> type="checkbox" id="tra6" /><label for="tra6"> <?php echo $LANG['patients']['surgery']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Endodontia" <?php echo ((strpos($row[tratamento], 'Endodontia')!== false)?'checked':'')?> type="checkbox" id="tra7" /><label for="tra7"> <?php echo $LANG['patients']['endodonty']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Periodontia" <?php echo ((strpos($row[tratamento], 'Periodontia')!== false)?'checked':'')?> type="checkbox" id="tra8" /><label for="tra8"> <?php echo $LANG['patients']['periodonty']?></label>&nbsp;&nbsp;</td>
        												</tr>
        												<tr>
        													<td><input name="tratamento[]" value="Radiologia" <?php echo ((strpos($row[tratamento], 'Radiologia')!== false)?'checked':'')?> type="checkbox" id="tra9" /><label for="tra9"> <?php echo $LANG['patients']['radiology']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="DTM" <?php echo ((strpos($row[tratamento], 'DTM')!== false)?'checked':'')?> type="checkbox" id="tra10" /><label for="tra10"> <?php echo $LANG['patients']['dtm']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Odontogeriatria" <?php echo ((strpos($row[tratamento], 'Odontogeriatria')!== false)?'checked':'')?> type="checkbox" id="tra11" /><label for="tra11"> <?php echo $LANG['patients']['odontogeriatry']?></label>&nbsp;&nbsp;</td>
        													<td><input name="tratamento[]" value="Ortopedia" <?php echo ((strpos($row[tratamento], 'Ortopedia')!== false)?'checked':'')?> type="checkbox" id="tra12" /><label for="tra12"> <?php echo $LANG['patients']['orthopedy']?></label>&nbsp;&nbsp;</td>
        												</tr>
        												<tr>
        													<td>&nbsp;</td>
        													<td>&nbsp;</td>
        													<td>&nbsp;</td>
        													<td>&nbsp;</td>
        												</tr>
        											</table>
        										</fieldset>
        										 <br />
        										<fieldset>
        											<legend><span class="style1"><?php echo $LANG['patients']['professional_informations']?></span></legend>

        											<table class="table">

        												<tr>
        													<td><?php echo $LANG['patients']['professional_searched']?><br />
        														<label style="width:100%;"><select name="codigo_dentistaprocurado" class="form-control" <?php echo $disable?> id="codigo_dentistaprocurado">
        															<option></option>
        															<?php
        															$dentista = new TDentistas();
        															$lista = $dentista->ListDentistas();
        															for($i = 0; $i < count($lista); $i++) {
        																if($row[codigo_dentistaprocurado] == $lista[$i][codigo]) {
        																	echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].')</option>';
        																} else {
        																	echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
        																}
        															}
        															?>
        														</select>
        													</label>
        												</td><td>
        												<?php echo $LANG['patients']['answered_by']?><br />
        												<label style="width:100%;"><select name="codigo_dentistaatendido" class="form-control" <?php echo $disable?> id="codigo_dentistaatendido">
        													<option></option>
        													<?php
        													$dentista = new TDentistas();
        													$lista = $dentista->ListDentistas();
        													for($i = 0; $i < count($lista); $i++) {
        														if($row[codigo_dentistaatendido] == $lista[$i][codigo]) {
        															echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
        														} else {
        															echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
        														}
        													}
        													?>
        												</select>
        											</label>
        										</td><td>
        										<?php echo $LANG['patients']['forwarded_to']?><br />
        										<label style="width:100%;"><select name="codigo_dentistaencaminhado" class="form-control" <?php echo $disable?> id="codigo_dentistaencaminhado">
        											<option></option>
        											<?php
        											$dentista = new TDentistas();
        											$lista = $dentista->ListDentistas();
        											for($i = 0; $i < count($lista); $i++) {
        												if($row[codigo_dentistaencaminhado] == $lista[$i][codigo]) {
        													echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
        												} else {
        													echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
        												}
        											}
        											?>
        										</select>
        									</label>
        									<br />
        									<label></label></td>

        								</tr>
        							</table>
        						</fieldset>
        						 <br />
        						<fieldset>
        							<legend><span class="style1"><?php echo $LANG['patients']['familiar_information']?></span></legend>

        							<table class="table">

        								<tr>
        									<td><?php echo $LANG['patients']['father_name']?> <br />
        										<input name="nomepai" value="<?php echo utf8_encode($row[nomepai])?>" type="text" class="form-control" <?php echo $disable?> id="nomepai" size="50" maxlength="80" /></td>
        										<td><?php echo $LANG['patients']['birthdate']?><br />
        											<input name="nascimentopai" value="<?php echo $row[nascimentopai]?>" type="text" class="form-control" <?php echo $disable?> id="nascimentopai" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
        										</tr>
        										<tr>
        											<td><?php echo $LANG['patients']['father_profession']?> <br />
        												<input name="profissaopai" value="<?php echo $row[profissaopai]?>" type="text" class="form-control" <?php echo $disable?> id="profissaopai" size="50" maxlength="80" /></td>
        												<td><?php echo $LANG['patients']['telephone']?><br />
        													<input name="telefone1pais" value="<?php echo $row[telefone1pais]?>" type="text" class="form-control" <?php echo $disable?> id="telefone1pais" size="20" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
        												</tr>
        												<tr>
        													<td><br /><?php echo $LANG['patients']['mother_name']?><br />
        														<label style="width:100%">
        															<input name="nomemae" value="<?php echo utf8_encode($row[nomemae])?>" type="text" class="form-control" <?php echo $disable?> id="nomemae" size="50" maxlength="80" />
        														</label>
        														<br />
        														<label></label></td>
        														<td><br /><?php echo $LANG['patients']['birthdate']?><br />
        															<input name="nascimentomae" value="<?php echo $row[nascimentomae]?>" type="text" class="form-control" <?php echo $disable?> id="nascimentomae" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
        														</tr>
        														<tr>
        															<td><?php echo $LANG['patients']['mother_profession']?> <br />
        																<input name="profissaomae" value="<?php echo $row[profissaomae]?>" type="text" class="form-control" <?php echo $disable?> id="profissaomae" size="50" maxlength="80" /></td>
        																<td><?php echo $LANG['patients']['telephone']?><br />
        																	<input name="telefone2pais" value="<?php echo $row[telefone2pais]?>" type="text" class="form-control" <?php echo $disable?> id="telefone2pais" size="20" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
        																</tr>
        																<tr>
        																	<td colspan="2"><br /><?php echo $LANG['patients']['complete_address_in_case_of_be_different_from_personal']?><br />
        																		<input name="enderecofamiliar" value="<?php echo utf8_encode($row[enderecofamiliar])?>" type="text" class="form-control" <?php echo $disable?> id="endereco_familiar" size="78" maxlength="220" />                <br /></td>
        																	</tr>
        																</table>
        															</fieldset>
        															<br />
        															<fieldset>
        																<legend><span class="style1"><?php echo $LANG['patients']['extra_information']?> </span></legend>

        																<table class="table">


        																	<tr>
        																		<td><?php echo $LANG['patients']['record_date']?>  <br />

        																			<?php
        																			if($_GET[acao] == "editar") {
        																				?>
        																				<input name="datacad" disabled value="<?php echo $row[datacadastro]?>" type="text" class="form-control" <?php echo $disable?> id="datacad" size="20" maxlength="10" />

        																				<input name="datacadastro" class="form-control" value="<?php echo $row[datacadastro]?>" type="hidden" id="datacadastro" />
        																				<?php    			
        																			} else {
        																				?>				
        																				<input name="datacadastro" value="<?php echo date(d.'/'.m.'/'.Y)?>" type="text" class="form-control" <?php echo $disable?> id="datacadastro" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" />
        																				<input name="datacad" value="" class="form-control" type="hidden" id="datacad" />

        																				<?php
        																			}
        																			?>

        																			<label></label></td>
        																			<td ><?php echo $LANG['patients']['last_update']?>  <br />

        																				<input name="dataatua" disabled class="form-control" value="<?php echo converte_data($row[dataatualizacao], 2)?>" type="text" class="forms" <?php echo $disable?> id="dataatua" size="20" />
        																				<input name="dataatualizacao" value="<?php echo converte_data($row[dataatualizacao], 2)?>" type="hidden" id="dataatualizacao" />

        																				<label></label></td>

        																				<td><?php echo $LANG['patients']['patient_status']?> <br />
        																					<label style="width:100%"><select name="status" class="form-control" <?php echo $disable?> id="status">
        																						<?php
        																						$valores = array('Avaliação' => $LANG['patients']['evaluation'], 'Em tratamento' => $LANG['patients']['in_treatment'], 'Em revisão' => $LANG['patients']['in_revision'], 'Concluído' => $LANG['patients']['closed']);
        																						foreach($valores as $chave => $valor) {
        																							if($row[status] == $chave) {
        																								echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
        																							} else {
        																								echo '<option value="'.$chave.'">'.$valor.'</option>';
        																							}
        																						}
        																						?>       
        																					</select>
        																				</label></td>
        																			</tr>
        																			<tr>
        																				<td><?php echo $LANG['patients']['main_objective_of_the_consultation']?><br />
        																					<label style="width:100%">
        																						<textarea class="form-control" name="objetivo"><?php echo utf8_encode($row[objetivo])?></textarea>
        																					</label></td>
        																					<td colspan="2"><?php echo $LANG['patients']['comments']?><br />
        																						<label style="width:100%">
        																							<textarea class="form-control" name="observacoes"><?php echo utf8_encode($row[observacoes])?></textarea>
        																						</label></td>
        																					</tr>
        																					<tr>
        																						<td><label></label></td>
        																						<td>&nbsp;</td>
        																					</tr>
        																				</table>
        																			</fieldset>

        																			 <fieldset>
        																				<legend><span class="style1"><?php echo $LANG['patients']['plan_information']?></span></legend>

        																				<table class="table">
        																					<tr>
        																						<td><?php echo $LANG['patients']['select_plan']?><br />
        																							<select name="convenio" class="form-control" <?php echo $disable?> id="convenio">
        																								<option value=''>Nenhum</option>
        																								<?php
        																								$query1 = mysqli_query($conn, "SELECT * FROM convenios ORDER BY nomefantasia");
        																								while($row1 = mysqli_fetch_assoc($query1)) {
        																									if($row[codigo_convenio] == $row1['codigo']) {
        																										echo '<option value="'.$row1['codigo'].'" selected>'.utf8_encode($row1['nomefantasia']).'</option>';
        																									} else {
        																										echo '<option value="'.$row1['codigo'].'">'.utf8_encode($row1['nomefantasia']).'</option>';
        																									}
        																								}
        																								?>       
        																							</select> 
        																							<label>
        																							</label></td>

        																						</tr>
        																						<tr>
        																							<td><label style="width:100%;"><?php echo $LANG['patients']['card_number']?><br />
        																								<input name="matricula" value="<?php echo $row[matricula]?>" type="text" class="form-control" <?php echo $disable?> id="matricula" size="20" />

        																							</label></td>
        																							<td><?php echo $LANG['patients']['holder_name']?><br>
        																								<input name="titular" value="<?php echo utf8_encode($row[titular])?>" type="text" class="form-control" <?php echo $disable?> id="titular" size="40" /></td>

        																								<td><?php echo $LANG['patients']['good_thru']?> <br />
        																									<input name="validadeconvenio" value="<?php echo $row[validadeconvenio]?>" type="text" class="form-control" <?php echo $disable?> id="validadeconvenio" size="20" />
        																								</td>

        																							</tr>

        																						</table>
        																					</fieldset>	

        																					<div align="center"><br />
        																						<a href="relatorios/paciente.php?codigo=<?php echo $row['codigo']?>" target="_blank"><button class="btn btn-warning" type="button"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print_sheet']?></button></a>
        																						<button name="Salvar" type="submit" class="btn btn-success" <?php echo $disable?> id="Salvar"><span class="glyphicon glyphicon-ok"></span> <?php echo $LANG['patients']['save']?></button>
        																					</div>
        																				</form>      </td>

        																			</table>
        																		</div>
        																	</div>
        																	<script>
        																		document.getElementById('nom').focus();
        																	</script>
