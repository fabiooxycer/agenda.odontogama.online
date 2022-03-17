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
    if($_GET['codigo'] != '') {
        $paciente = new TPacientes();
        $paciente->LoadPaciente($_GET['codigo']);
        $dentista = new TDentistas();
        $dentista->LoadDentista($paciente->RetornaDados('codigo_dentistaprocurado'));
        $dentista_procurado = (($dentista->RetornaDados('nome') != '')?$dentista->RetornaDados('titulo').' '.$dentista->RetornaDados('nome'):'');
        $dentista->LoadDentista($paciente->RetornaDados('codigo_dentistaatendido'));
        $dentista_atendido = (($dentista->RetornaDados('nome') != '')?'<b>'.$dentista->RetornaDados('titulo').' '.$dentista->RetornaDados('nome'):'');
        $dentista->LoadDentista($paciente->RetornaDados('codigo_dentistaencaminhado'));
        $dentista_encaminhado = (($dentista->RetornaDados('nome') != '')?'<b>'.$dentista->RetornaDados('titulo').' '.$dentista->RetornaDados('nome'):'');
    }
?>
<p align="center"><font size="3"><b><?php echo $LANG['reports']['patient_sheet']?></b></font></p><br />
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
            <?php echo $LANG['reports']['clinical_sheet']?>:<br />
            <b><?php echo $paciente->RetornaDados('codigo')?></b>&nbsp;
          </td>
          <td width="23%">
            &nbsp;
          </td>
          <td width="26%" rowspan="12" valign="top" align="center">
<?php
    if($paciente->RetornaDados('foto') != '') {
		echo '<img src="../pacientes/verfoto_p.php?codigo='.$paciente->RetornaDados('codigo').'" border="0">';
	} else {
		echo '<img src="../pacientes/verfoto_p.php?codigo='.$paciente->RetornaDados('codigo').'&padrao=no_photo" border="0">';
	}
?>
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['name']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('nome'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['document1']?>:<br />
            <b><?php echo $paciente->RetornaDados('cpf')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['document2']?>:<br />
            <b><?php echo $paciente->RetornaDados('rg')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['relationship_status']?>:<br />
            <b>
<?php
    switch($paciente->RetornaDados('estadocivil')) {
        case 'solteiro': echo $LANG['reports']['single']; break;
        case 'casado': echo $LANG['reports']['married']; break;
        case 'divorciado': echo $LANG['reports']['divorced']; break;
        case 'viuvo': echo $LANG['reports']['widowed']; break;
    }
?>
            </b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['gender']?>:<br />
            <b><?php echo (($paciente->RetornaDados('sexo') == 'Masculino')?$LANG['reports']['male']:$LANG['reports']['female'])?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['ethnicity']?>:<br />
            <b>
<?php
    switch($paciente->RetornaDados('etnia')) {
        case 'africano': echo $LANG['reports']['african']; break;
        case 'asiatico': echo $LANG['reports']['asian']; break;
        case 'caucasiano': echo $LANG['reports']['caucasian']; break;
        case 'latino': echo $LANG['reports']['latin']; break;
        case 'orientemedio': echo $LANG['reports']['middle_eastern']; break;
        case 'multietnico': echo $LANG['reports']['multi_ethnic']; break;
    }
?>
            </b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['profession']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('profissao'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['naturality']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('naturalidade'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['nationality']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('nacionalidade'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['birthdate']?>:<br />
            <b><?php echo converte_data($paciente->RetornaDados('nascimento'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['address1']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('endereco'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['address2']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('bairro'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['city']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('cidade'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['state']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('estado'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['patients']['country']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('pais'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['patients']['dead']?>:<br />
            <b><?php echo $paciente->RetornaDados('falecido')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['zip']?>:<br />
            <b><?php echo $paciente->RetornaDados('cep')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['cellphone']?>:<br />
            <b><?php echo $paciente->RetornaDados('celular')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['residential_phone']?>:<br />
            <b><?php echo $paciente->RetornaDados('telefone1')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['comercial_phone']?>:<br />
            <b><?php echo $paciente->RetornaDados('telefone2')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['hobby']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('hobby'))?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['indicated_by']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('indicadopor'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $LANG['reports']['email']?>:<br />
            <b><?php echo $paciente->RetornaDados('email')?></b>&nbsp;
          </td>
          <td>
            <?php echo $LANG['reports']['comments_for_label']?>:<br />
            <b><?php echo utf8_encode($paciente->RetornaDados('obs_etiqueta'))?></b>&nbsp;
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
    <th align="left"><?php echo $LANG['reports']['treatments_to_do']?>
    </th>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Ortodontia') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['orthodonty'])?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Implantodontia') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['implantodonty'])?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Dentística') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['dentistic'])?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Prótese') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['prosthesis'])?>
          </td>
        </tr>
        <tr>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Odontopediatria') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['odontopediatry'])?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Cirurgia') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['surgery'])?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Endodontia') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['endodonty'])?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Periodontia') !== false)?'checked':'')?>><?php echo utf8_encode($LANG['reports']['periodonty'])?>
          </td>
        </tr>
        <tr>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Radiologia') !== false)?'checked':'')?>><?php echo $LANG['reports']['radiology']?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'DTM') !== false)?'checked':'')?>><?php echo $LANG['reports']['dtm']?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Odontogeriatria') !== false)?'checked':'')?>><?php echo $LANG['reports']['odontogeriatry']?>
          </td>
          <td><input type="checkbox" disabled <?php echo ((strpos($paciente->RetornaDados('tratamento'), 'Ortopedia') !== false)?'checked':'')?>><?php echo $LANG['reports']['orthopedy']?>
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
          <td><?php echo $LANG['reports']['professional_searched']?>:<br />
          <b><?php echo utf8_encode($dentista_procurado)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['answered_by']?>:<br />
          <b><?php echo utf8_encode($dentista_atendido)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['forwarded_to']?>:<br />
          <b><?php echo utf8_encode($dentista_encaminhado)?></b>&nbsp;
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
          <td width="60%"><?php echo $LANG['reports']['fathers_name']?>:<br />
          <b><?php echo utf8_encode($paciente->RetornaDados('nomepai'))?></b>&nbsp;
          </td>
          <td width="40%"><?php echo $LANG['reports']['birthdate']?>:<br />
          <b><?php echo converte_data($paciente->RetornaDados('nascimentopai'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['profession']?>:<br />
          <b><?php echo utf8_encode($paciente->RetornaDados('profissaopai'))?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['telephone']?>:<br />
          <b><?php echo $paciente->RetornaDados('telefone1pais')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['mothers_name']?>:<br />
          <b><?php echo utf8_encode($paciente->RetornaDados('nomemae'))?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['birthdate']?>:<br />
          <b><?php echo converte_data($paciente->RetornaDados('nascimentomae'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['profession']?>:<br />
          <b><?php echo utf8_encode($paciente->RetornaDados('profissaomae'))?></b><br />&nbsp;
          </td>
          <td><?php echo $LANG['reports']['telephone']?>:<br />
          <b><?php echo $paciente->RetornaDados('telefone2pais')?></b><br />&nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $LANG['reports']['complete_address']?>:<br />
          <b><?php echo utf8_encode($paciente->RetornaDados('enderecofamiliar'))?></b>&nbsp;
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
    <th align="left"><?php echo $LANG['reports']['extra_information']?>
    </th>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td width="50%"><?php echo $LANG['reports']['record_date']?>:<br />
          <b><?php echo converte_data($paciente->RetornaDados('datacadastro'), 2)?></b>&nbsp;
          </td>
          <td width="50%"><?php echo $LANG['reports']['last_update']?>:<br />
          <b><?php echo converte_data($paciente->RetornaDados('dataatualizacao'), 2)?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $LANG['reports']['status']?>:<br />
          <b>
<?php
    switch($paciente->RetornaDados('status')) {
        case 'Avaliação': echo utf8_encode($LANG['reports']['evaluation']); break;
        case 'Em tratamento': echo utf8_encode($LANG['reports']['in_treatment']); break;
        case 'Em revisão': echo utf8_encode($LANG['reports']['in_revision']); break;
        case 'Concluído': echo utf8_encode($LANG['reports']['closed']); break;
    }
?>
            </b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['main_objective_of_the_consultation']?>:<br />
          <b><?php echo nl2br(utf8_encode($paciente->RetornaDados('objetivo')))?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['comments']?>:<br />
          <b><?php echo nl2br(utf8_encode($paciente->RetornaDados('observacoes')))?></b>&nbsp;
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
    <th align="left"><?php echo $LANG['reports']['plan_information']?>
    </th>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td width="40%"><?php echo $LANG['reports']['plan']?>:<br />
<?php
    $convenio = encontra_valor('convenios', 'codigo', $paciente->RetornaDados('convenio'), 'nomefantasia');
?>
          <b><?php echo (($convenio != '')?$convenio:$LANG['patients']['private'])?></b>&nbsp;
          </td>
          <td width="60%"><?php echo $LANG['reports']['others']?>:<br />
          <b><?php echo $paciente->RetornaDados('outros')?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td><?php echo $LANG['reports']['card_number']?>:<br />
          <b><?php echo $paciente->RetornaDados('matricula')?></b>&nbsp;
          </td>
          <td><?php echo $LANG['reports']['holder_name']?>:<br />
          <b><?php echo utf8_encode($paciente->RetornaDados('titular'))?></b>&nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $LANG['reports']['good_thru']?>:<br />
          <b><?php echo $paciente->RetornaDados('validadeconvenio')?></b>&nbsp;
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
