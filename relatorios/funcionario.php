<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-15", true);
	if(!checklog()) {
		die($frase_log);
	}
	include "../timbre_head.php";
    $funcionario = new TFuncionarios();
    $funcionario->LoadFuncionario($_GET['codigo']);
?>
<p align="center"><font size="3"><b><?php echo $LANG['reports']['employee_sheet']?></b></font></p><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th align="left"><?php echo $LANG['reports']['personal_information']?>
    </th>
  </tr>
  <tr style="font-size: 12px">
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td width="51%">
            <?php echo $LANG['reports']['name']?>:<br />
            <b><?php echo utf8_encode($funcionario->RetornaDados('nome'))?></b>&nbsp;
          </td>
          <td width="23%">
            <?php echo $LANG['reports']['document1']?>:<br />
            <b><?php echo $funcionario->RetornaDados('cpf')?></b>&nbsp;
          </td>
          <td width="26%" rowspan="8" valign="top" align="center">
<?php
    if($funcionario->RetornaDados('foto') != '') {
		echo '<img src="../funcionarios/verfoto_p.php?codigo='.$funcionario->RetornaDados('codigo').'" border="0">';
	} else {
		echo '<img src="../funcionarios/verfoto_p.php?codigo='.$funcionario->RetornaDados('codigo').'&padrao=no_photo" border="0">';
	}
?>
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['document2']?>:<br />
            <b><?php echo $funcionario->RetornaDados('rg')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['relationship_status']?>:<br />
            <b>
<?php
    switch($funcionario->RetornaDados('estadocivil')) {
        case 'solteiro': echo $LANG['reports']['single']; break;
        case 'casado': echo $LANG['reports']['married']; break;
        case 'divorciado': echo $LANG['reports']['divorced']; break;
        case 'viuvo': echo $LANG['reports']['widowed']; break;
    }
?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['address1']?>:<br />
            <b><?php echo utf8_encode($funcionario->RetornaDados('endereco'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['address2']?>:<br />
            <b><?php echo utf8_encode($funcionario->RetornaDados('bairro'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['city']?>:<br />
            <b><?php echo utf8_encode($funcionario->RetornaDados('cidade'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['state']?>:<br />
            <b><?php echo utf8_encode($funcionario->RetornaDados('estado'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['zip']?>:<br />
            <b><?php echo $funcionario->RetornaDados('cep')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['birthdate']?>:<br />
            <b><?php echo converte_data($funcionario->RetornaDados('nascimento'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['phone1']?>:<br />
            <b><?php echo $funcionario->RetornaDados('telefone1')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['phone2']?>:<br />
            <b><?php echo $funcionario->RetornaDados('telefone2')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['cellphone']?>:<br />
            <b><?php echo $funcionario->RetornaDados('celular')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['gender']?>:<br />
            <b><?php echo (($funcionario->RetornaDados('sexo') == 'Masculino')?$LANG['reports']['male']:$LANG['reports']['female'])?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['email']?>:<br />
            <b><?php echo $funcionario->RetornaDados('email')?></b>&nbsp;
          </td>
          <td>
            <br />
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>&nbsp;
    </td>
  </tr>
  <tr>
    <th align="left"><?php echo $LANG['reports']['familiar_information']?>
    </th>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td><?php echo $LANG['reports']['mothers_name']?>:<br />
          <b><?php echo utf8_encode($funcionario->RetornaDados('nomemae'))?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['birthdate']?>:<br />
          <b><?php echo converte_data($funcionario->RetornaDados('nascimentomae'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td width="60%"><?php echo $LANG['reports']['fathers_name']?>:<br />
          <b><?php echo utf8_encode($funcionario->RetornaDados('nomepai'))?></b>&nbsp;
          </td>
          <td width="40%"><?php echo $LANG['reports']['birthdate']?>:<br />
          <b><?php echo converte_data($funcionario->RetornaDados('nascimentopai'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $LANG['reports']['complete_address']?>:<br />
          <?php echo utf8_encode($funcionario->RetornaDados('enderecofamiliar'))?>&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>&nbsp;
    </td>
  </tr>
  <tr>
    <th align="left"><?php echo $LANG['reports']['professional_information']?>
    </th>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td width="50%"><?php echo $LANG['reports']['main_function']?>:<br />
          <b><?php echo utf8_encode($funcionario->RetornaDados('funcao1'))?></b>&nbsp;
          </td>
          <td width="50%"><?php echo $LANG['reports']['secondary_function']?>:<br />
          <b><?php echo utf8_encode($funcionario->RetornaDados('funcao2'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['admission_date']?>:<br />
          <b><?php echo converte_data($funcionario->RetornaDados('admissao'), 2)?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['resignation_date']?>:<br />
          <b><?php echo converte_data($funcionario->RetornaDados('demissao'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['comments']?>:<br />
          <b><?php echo nl2br(utf8_encode($funcionario->RetornaDados('observacoes')))?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['active_on_clinic']?><br />
          <b><?php echo nl2br($funcionario->RetornaDados('ativo'))?></b>&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<script>
window.print();
</script>
<?php
    include "../timbre_foot.php";
?>
