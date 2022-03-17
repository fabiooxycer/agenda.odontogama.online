<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

	$sistema = new sistema(); 
	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	/*if(!checklog()) {
		die($frase_log);
	}*/
	if(($_GET['codigo'] != '' && !verifica_nivel('profissionais', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('profissionais', 'I'))) {
		$disable = 'disabled';
		$disable2 = $disable;
		if($_GET['codigo'] == $_SESSION['codigo']) {
			$disable2 = '';
		}
	}
	$dentista = new TDentistas();
	if(isset($_POST[Salvar])) {
		if ($_POST[sosenha] == 'true') {
			if($_POST[senha] != '') {
				if($_POST[senha] != $_POST[confsenha]) {
					$j++;
					$r[23] = '<font color="#FF0000">';
					$r[24] = '<font color="#FF0000">';
				}
				$senha = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `dentistas` WHERE `codigo` = '".$_GET[codigo]."'"));
				if(md5($_POST[senhaatual]) != $senha[senha] && (checknivel('Dentista') || checknivel('Funcionario'))) {
					$j++;
					$r[22] = '<font color="#FF0000">';
				}
			}
			if($j == 0) {
				$dentista->LoadDentista($_GET[codigo]);
				$strScrp = "Ajax('dentistas/gerenciar', 'conteudo', '');";
				if($_POST[senha] != "") {
					$dentista->SetDados('senha', md5($_POST[senha]));
				}
				$dentista->Salvar();
			}
		} else {
			$obrigatorios[1] = 'nom';
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
				$r[22] = '<font color="#FF0000"> *';
				$r[23] = '<font color="#FF0000"> *';
			}
			if($_POST[senha] != '' && $_GET[acao] == 'editar') {
				if($_POST[senha] != $_POST[confsenha]) {
					$j++;
					$r[23] = '<font color="#FF0000">';
					$r[24] = '<font color="#FF0000">';
				}
				$senha = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `dentistas` WHERE `codigo` = '".$_GET[codigo]."'"));
				if(md5($_POST[senhaatual]) != $senha[senha] && (checknivel('Dentista') || checknivel('Funcionario'))) {
					$j++;
					$r[22] = '<font color="#FF0000">';
				}
			}
			if($j == 0) {
				if($_GET[acao] == "editar") {
					$dentista->LoadDentista($_GET[codigo]);
					//$strScrp = "Ajax('dentistas/gerenciar', 'conteudo', '');";
				}
				$dentista->SetDados('nome', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nom']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
				$dentista->SetDados('cpf', $_POST[cpf]);
				if($_POST[senha] != "") {
					$dentista->SetDados('senha', md5($_POST[senha]));
				}
				$dentista->SetDados('endereco', $_POST['endereco']);
				$dentista->SetDados('bairro', utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
				$dentista->SetDados('cidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
				$dentista->SetDados('estado', $_POST[estado]);
				$dentista->SetDados('pais', $_POST[pais]);
				$dentista->SetDados('cep', $_POST[cep]);
				$dentista->SetDados('nascimento', converte_data($_POST[nascimento], 1));
				$dentista->SetDados('telefone1', $_POST[telefone1]);
				$dentista->SetDados('celular', $_POST[celular]);
				$dentista->SetDados('telefone2', $_POST[telefone2]);
				$dentista->SetDados('sexo', $_POST[sexo]);
				$dentista->SetDados('nomemae', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomemae']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
				$dentista->SetDados('rg', $_POST[rg]);
				$dentista->SetDados('email', $_POST[email]);
				$dentista->SetDados('comissao', $_POST[comissao]);
				$dentista->SetDados('codigo_areaatuacao1', $_POST[codigo_areaatuacao1]);
				$dentista->SetDados('codigo_areaatuacao2', $_POST[codigo_areaatuacao2]);
				$dentista->SetDados('codigo_areaatuacao3', $_POST[codigo_areaatuacao3]);
				$dentista->SetDados('conselho_tipo', $_POST[conselho_tipo]);
				$dentista->SetDados('conselho_estado', $_POST[conselho_estado]);
				$dentista->SetDados('conselho_numero', $_POST[conselho_numero]);
				$dentista->SetDados('ativo', $_POST[ativo]);
				$dentista->SetDados('data_inicio', converte_data($_POST[data_inicio], 1));
				$dentista->SetDados('data_fim', converte_data($_POST[data_fim], 1));
				$dentista->SetDados('usuario', $_POST[usuario]);
				if($_GET[acao] != "editar") {
					$dentista->SalvarNovo();
					$_GET['codigo'] = mysqli_insert_id($conn);
				}
				$dentista->Salvar();


                // Horário de atendimento
                for ( $i = 0 ; $i <= 6 ; $i++ ) {

                    $ativo = $_POST['weekday'][$i] == 1 ? 1 : 0;
                    $hora_inicio = $_POST['hora_inicio'][$i];
                    $hora_fim = $_POST['hora_fim'][$i];

					$sql = "UPDATE dentista_atendimento SET codigo_dentista='$_GET[codigo]', hora_inicio='$hora_inicio', hora_fim='$hora_fim', ativo='$ativo' WHERE dia_semana='$i' AND codigo_dentista='$_GET[codigo]'";
                    mysqli_query($conn, $sql);
                    echo mysqli_error($conn) ? $sql . '; ' . mysqli_error($conn) . '<br/> ' : '';

                }


                $strScrp = "Ajax('dentistas/gerenciar', 'conteudo', 'codigo=".$_GET['codigo']."&acao=editar');";
			}
		}
	}
	if($_GET[acao] == "editar") {
		$strLoCase = $LANG['professionals']['editing'];
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$dentista->LoadDentista($_GET[codigo]);
		$row = $dentista->RetornaTodosDados();
		$row[nascimento] = converte_data($row[nascimento], 2);
	} else {
		/*
        if(checknivel('Dentista') || checknivel('Funcionario')) {
			die('<script>alert(\''.substr($frase_adm, 12).'\'); Ajax(\'dentistas/gerenciar\', \'conteudo\', \'\')</script>');
		}
		*/
		if($j == 0) {
			$row = "";
			$r[21] = '*';
			$r[22] = '*';
		} else {
			$row = $_POST;
			$row[nome] = $_POST[nom];
		}
		$strLoCase = $LANG['professionals']['including'];
	}
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>

<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/mask.js"></script>

<style type="text/css">
.table tbody tr td{border-top:0;}
</style>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-user"></span><b> <?php echo $LANG['professionals']['manage_professionals']?> - <?php echo $strLoCase?></b></div>
  <div class="panel-body">
      
    
 <form id="form2" name="form2" method="POST" action="dentistas/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset> 
<div>
  <table class="table">
    <tr>
      <td style="border-top:0;">
        
        <span class="style1">Informações pessoais</span>
      </td>
    </tr>
  </table>

        <table class="table">
          
          <tr>
            <td><?php echo $r[1]?>* <?php echo $LANG['professionals']['name']?><br />
                <label>
                  <input required name="nom" value="<?php echo utf8_encode($row[nome]); ?>" <?php echo $disable?> type="text" class="form-control" id="nom" size="50" maxlength="80" />
                </label>
                <br />
            </td>
            <td><?php echo $r[2]?><?php echo $LANG['professionals']['document1']?><br />
              <input required name="cpf" <?php echo $disable?> value="<?php echo $row[cpf]?>" type="text" class="form-control" id="cpf" maxlength="50"/>
			     </td>
            <!--<td>
    		<!--<iframe height="300" scrolling="No" width="150" name="foto_frame" id="foto_frame" src="dentistas/fotos.php?codigo=<?php echo $row[codigo]?><?php echo (($_GET[acao] != "editar")?'&disabled=yes':'')?>" frameborder="0"></iframe>-->
            <!--</td>-->
          
<?php
	if($_GET[acao] == "editar") {
		$msg = '<i>Preencher somente se for alterar</i>';
	}
?>
          <td><?php echo $LANG['professionals']['zip']?><br />
              <input required name="cep" value="<?php echo $row[cep]?>" <?php echo $disable?> type="text" class="form-control" id="cep" size="10" onKeypress="return Ajusta_CEP(this, event);" /></td>
            <td>Endereço<br />
              <input required name="endereco" value="<?php echo utf8_encode($row[endereco]); ?>" <?php echo $disable?> type="text" class="form-control" id="endereco" size="50" maxlength="150" /></td>
            </tr>
            <tr>
            <td><?php echo $LANG['professionals']['address2']?><br />
              <input required name="bairro" value="<?php echo utf8_encode($row[bairro]); ?>" <?php echo $disable?> type="text" class="form-control" id="bairro" /></td>
          
            <td><?php echo $LANG['professionals']['city']?><br />
                <input required name="cidade" value="<?php echo utf8_encode($row[cidade]); ?>" <?php echo $disable?> type="text" class="form-control" id="cidade" size="30" maxlength="50" />
              <br /></td>
            <td><?php echo $LANG['professionals']['state']?><br />
                <input required name="estado" value="<?php echo utf8_encode($row[estado]); ?>" <?php echo $disable?> type="text" class="form-control" id="estado" maxlength="50" />
            </td>
          
            <td>País<br />
                <input required name="pais" value="<?php echo utf8_encode($row[pais]); ?>" <?php echo $disable?> type="text" class="form-control" id="pais" size="30" maxlength="50" />
            </td>
            
          </tr>
          <tr>
            
            <td><?php echo $LANG['professionals']['birthdate']?><br />
              <input required name="nascimento" value="<?php echo $row[nascimento]?>" <?php echo $disable?> type="text" class="form-control" id="nascimento" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
          
            <td><?php echo $LANG['professionals']['phone1']?><br />
              <input required name="telefone1" value="<?php echo $row[telefone1]?>" <?php echo $disable?> type="text" class="form-control" id="telefone1" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?php echo $LANG['professionals']['cellphone']?><br />
              <input name="celular" value="<?php echo $row[celular]?>" <?php echo $disable?> type="text" class="form-control" id="celular" onKeypress="return Ajusta_Telefone(this, event);" /></td>
         
            <td><?php echo $LANG['professionals']['phone2']?><br />
              <input name="telefone2" id="telefone2" value="<?php echo $row[telefone2]?>" <?php echo $disable?> type="text" class="form-control" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            </tr><tr>
            <td><?php echo $LANG['professionals']['gender']?><br />
              <select name="sexo" <?php echo $disable?> class="form-control" id="sexo">
<?php
	$valores = array('Masculino' => $LANG['professionals']['male'], 'Feminino' => $LANG['professionals']['female']);
	foreach($valores as $chave => $valor) {
		if($row[sexo] == $chave) {
			echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
		} else {
			echo '<option value="'.$chave.'">'.$valor.'</option>';
		}
	}
?>       
			 </select></td>
          
            <td><?php echo $LANG['professionals']['parents_name']?><br />
                <label>
                  <input required name="nomemae" value="<?php echo utf8_encode($row[nomemae]); ?>" <?php echo $disable?> type="text" class="form-control" id="nomemae" size="50" maxlength="80" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><?php echo $LANG['professionals']['document2']?><br />
              <input required name="rg" value="<?php echo $row[rg]?>" <?php echo $disable?> type="text" class="form-control" id="rg" maxlength="15"/></td>
          
            <td><?php echo $LANG['professionals']['email']?><br />
              <input name="email" value="<?php echo $row[email]?>" <?php echo $disable?> type="email" class="form-control" id="email" size="50" /></td>
            
            </tr><tr>
            <td>Comissão (%)<br />
              <input required name="comissao" value="<?php echo $row[comissao]?>" <?php echo $disable?> type="text" class="form-control" id="comissao" onKeypress="return Ajusta_Valor(this, event);" /></td>
          
          </tr>
        </table>
        
        
        <table class="table">
          <tr>
            <td>
              <span class="style1">Informações do profissional</span>
            </td>
          </tr>
        </table>

        <table class="table">
          <tr>
            <td><?php echo $LANG['professionals']['area_of_expertise_specialty_1']?><br />
                 <select required name="codigo_areaatuacao1" <?php echo $disable?> class="form-control" id="codigo_areaatuacao1">
                 <option></option>
<?php
	$especialidades = new TEspecialidades();
	$lista = $especialidades->ListEspecialidades();
	for($i = 0; $i < count($lista); $i++) {
		if($lista[$i][codigo] == $row[codigo_areaatuacao1]) {
			echo '<option value="'.$lista[$i][codigo].'" selected>'.utf8_encode($lista[$i][descricao]).'</option>';
		} else {
			echo '<option value="'.$lista[$i][codigo].'">'.utf8_encode($lista[$i][descricao]).'</option>';
		}
	}
?>
                 </select>
              </td>
            
            <td><?php echo $LANG['professionals']['area_of_expertise_specialty_2']?><br />
              <select name="codigo_areaatuacao2" <?php echo $disable?> class="form-control" id="codigo_areaatuacao2">
                 <option></option>
<?php
	$especialidades = new TEspecialidades();
	$lista = $especialidades->ListEspecialidades();
	for($i = 0; $i < count($lista); $i++) {
		if($lista[$i][codigo] == $row[codigo_areaatuacao2]) {
			echo '<option value="'.$lista[$i][codigo].'" selected>'.utf8_encode($lista[$i][descricao]).'</option>';
		} else {
			echo '<option value="'.$lista[$i][codigo].'">'.utf8_encode($lista[$i][descricao]).'</option>';
		}
	}
?>
              </select>
            </td>
            <td>Área de espec. 3<br />
              <select style="max-width:150px;" name="codigo_areaatuacao3" <?php echo $disable?> class="form-control" id="codigo_areaatuacao3">
                 <option></option>
<?php
	$especialidades = new TEspecialidades();
	$lista = $especialidades->ListEspecialidades();
	for($i = 0; $i < count($lista); $i++) {
		if($lista[$i][codigo] == $row[codigo_areaatuacao3]) {
			echo '<option value="'.$lista[$i][codigo].'" selected>'.utf8_encode($lista[$i][descricao]).'</option>';
		} else {
			echo '<option value="'.$lista[$i][codigo].'">'.utf8_encode($lista[$i][descricao]).'</option>';
		}
	}
?>
              </select>
              <br />
              <br /></td>
          
            <td>Doc. Dentista<br />
              <div class="input-group" style="display:flex;">
                
                <input required name="conselho_tipo" style="max-width:45px;" value="<?php echo $row[conselho_tipo]?>" <?php echo $disable?> type="text" class="form-control" maxlength="10" />
                <input required name="conselho_estado" style="max-width:45px;" value="<?php echo $row[conselho_estado]?>" <?php echo $disable?> type="text" class="form-control" maxlength="2" />
                <input required name="conselho_numero" style="max-width:45px;" value="<?php echo $row[conselho_numero]?>" <?php echo $disable?> type="text" class="form-control" maxlength="30" />
                
               
               </div>             
            </td>
          </tr><tr>
            <td><?php echo $LANG['professionals']['professional_active_on_clinic']?><br />
                <select name="ativo" <?php echo $disable?> class="form-control" id="ativo">
<?php
  $valores = array('Sim' => $LANG['professionals']['yes'], 'Não' => $LANG['professionals']['no']);
  foreach($valores as $chave => $valor) {
    if($row[ativo] == $chave) {
      echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
    } else {
      echo '<option value="'.$chave.'">'.$valor.'</option>';
    }
  }
?>
       </select></td>
            <td><?php echo $LANG['professionals']['start_date_of_activities_on_clinic']?><br />
              <input name="data_inicio" value="<?php echo (($row['data_inicio'] != '')?converte_data($row['data_inicio'], 2):'')?>" <?php echo $disable?> type="text" class="form-control" id="data_inicio" maxlength="10" onKeypress="return Ajusta_Data(this, event);" />
              <br />
              <br /></td>

          
            <td  colspan="2"><?php echo $LANG['professionals']['end_date_of_activities_on_clinic']?><br />
              <input name="data_fim" value="<?php echo (($row['data_fim'] != '')?converte_data($row['data_fim'], 2):'')?>" <?php echo $disable?> type="text" class="form-control" id="data_fim" maxlength="10" onKeypress="return Ajusta_Data(this, event);" />
            
            </td>
          </tr>
          

      <br />
      
          <table class="table">
            <tr>
              <td>
                <span class="style1"><?php echo $LANG['professionals']['personal_access_information']?> </span>
              </td>
            </tr>
          </table>

          <table class="table">
              
              <tr>
                  <td><?php echo $r[27]?>* <?php echo $LANG['professionals']['login']?> <br />
                      <input required name="usuario" value="<?php echo utf8_encode($row[usuario]); ?>" <?php echo $disable?> type="text" class="form-control" id="usuario" maxlength="15" />
                  </td>
              
              <?php
              $x = 22;
              if($disable == 'disabled' && $disable2 == '') {
                  echo '<input type="hidden" name="sosenha" value="true">';
              }
              if($_GET[acao] == 'editar' && (checknivel('Dentista') || checknivel('Funcionario'))) {
                  $nova = "Nova ";
                  ?>
                  
                      <td width="287"><?php echo $r[22]?><?php echo $LANG['professionals']['current_password']?> <br />
                          <input name="senhaatual" value="" <?php echo $disable2?> type="password" class="form-control" id="senhaatual" maxlength="32" />
                      </td>
                  
                  <?php
                  $x++;
              }
              ?>
                  <td><?php echo $r[$x]?><?php echo $LANG['professionals']['new_password']?> <br />
                      <input name="senha" value="" <?php echo $disable2?> type="password" class="form-control" id="senha" maxlength="32" />
                  </td>
              
                  <td><?php echo $r[($x+1)]?><?php echo $LANG['professionals']['retype_new_password']?><br />
                      <input name="confsenha" value="" <?php echo $disable2?> type="password" class="form-control" id="confsenha" maxlength="32" />
                      <br />
                  </td>
              </tr>
              
          </table>


      <br />


       <fieldset>
          <legend><span class="style1"><?php echo $LANG['professionals']['service_hours']?> </span></legend>

          <table class="table">
              <tr style="margin:3px 0;">
                  <td width="15%" align="center"><?php echo $LANG['func']['sunday']?></td>
                  <td width="14%" align="center"><?php echo $LANG['func']['monday']?></td>
                  <td width="14%" align="center"><?php echo $LANG['func']['tuesday']?></td>
                  <td width="14%" align="center"><?php echo $LANG['func']['wednesday']?></td>
                  <td width="14%" align="center"><?php echo $LANG['func']['thursday']?></td>
                  <td width="14%" align="center"><?php echo $LANG['func']['friday']?></td>
                  <td width="15%" align="center"><?php echo $LANG['func']['saturday']?></td>
              </tr>
<?php
    if ( isset ( $_GET['codigo'] ) ) {
        for ( $i = 0 ; $i <= 6 ; $i++ ) {
            $exists = mysqli_num_rows ( mysqli_query ($conn, "SELECT * FROM dentista_atendimento WHERE codigo_dentista = " . $_GET['codigo'] . " AND dia_semana = " . $i ) );
            if ($exists <= 0) {
                mysqli_query ($conn, "INSERT INTO dentista_atendimento ( codigo_dentista , dia_semana , hora_inicio , hora_fim , ativo ) VALUES
                 ( " . $_GET['codigo'] . " , " . $i . " , '07:00:00' , '22:45:00' , 1 )" );
            }
        }
    }

    for ( $i = 0 ; $i <= 6 ; $i++ ) {
        $weekday[$i] = mysqli_fetch_assoc ( mysqli_query ($conn, "SELECT * FROM dentista_atendimento WHERE codigo_dentista = " . $_GET['codigo'] . " AND dia_semana = " . $i ) );
    }

?>
              <tr style="margin-top:3px;">
                  <?php
                  for ( $i = 0 ; $i <= 6 ; $i++ ) {
                  ?>
                      <td align="center"><input type="checkbox" name="weekday[<?php echo $i?>]" value="1" <?php echo (!isset($_GET['codigo']) || $weekday[$i]['ativo'] == 1 ) ? 'checked="checked"' : ''?>/></td>
                  <?php
                  }
                  ?>
              </tr>
              <tr>
                  <?php
                  for ( $i = 0 ; $i <= 6 ; $i++ ) {
                  ?>
                      <td align="center">
                          <select name="hora_inicio[<?php echo $i?>]" class="form-control" style="margin-top:3px;">
                              <?php
                              for ( $j = 7 ; $j <= 22 ; $j++ ) {
                                  for ( $k = 0 ; $k < 60 ; $k += 15 ) {
                                      $hora = str_pad ( $j , 2 , '0' , STR_PAD_LEFT ) . ':' . str_pad ( $k , 2 , '0' , STR_PAD_LEFT ) . ':00';
                                      ?>
                                      <option value="<?php echo $hora?>" <?php echo ( $weekday[$i]['hora_inicio'] == $hora || ( $weekday[$i]['hora_inicio'] == '' && $hora == "07:00:00" ) ) ? 'selected' : '' ?>><?php echo $hora?></option>
                                  <?php
                                  }
                              }
                              ?>
                          </select> <br />
                          <select name="hora_fim[<?php echo $i?>]" class="form-control" style="margin-top:3px;">
                              <?php
                              for ( $j = 7 ; $j <= 22 ; $j++ ) {
                                  for ( $k = 0 ; $k < 60 ; $k += 15 ) {
                                      $hora = str_pad ( $j , 2 , '0' , STR_PAD_LEFT ) . ':' . str_pad ( $k , 2 , '0' , STR_PAD_LEFT ) . ':00';
                                      ?>
                                      <option value="<?php echo $hora?>" <?php echo ( $weekday[$i]['hora_fim'] == $hora || ( $weekday[$i]['hora_fim'] == '' && $hora == "22:45:00" ) ) ? 'selected' : '' ?>><?php echo $hora?></option>
                                  <?php
                                  }
                              }
                              ?>
                          </select> <br />
                  <?php
                  }
                  ?>
                </td>
              </tr>
          </table>
      </fieldset>

        <div align="left"><br>
          <button name="Salvar" type="submit" <?php echo $disable2?> class="btn btn-primary" id="Salvar" value=""><span class=" glyphicon glyphicon-ok"></span> <?php echo $LANG['professionals']['save']?></button>
          <a href="relatorios/dentista.php?codigo_dentista=<?php echo $row['codigo']?>" target="_blank"><button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['professionals']['print_sheet']?></button></a>
          
        </div>
      </form>
      </td>
    </tr>
  </table>
 </div></div> <br><br><Br>
<script>
document.getElementById('nom').focus();
</script>
