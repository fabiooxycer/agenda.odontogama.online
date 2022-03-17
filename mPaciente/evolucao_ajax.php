<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
	if(!checklog()) {
		die($frase_log);
	}
	$paciente = new TEvolucao();
	if($_GET['confirm_del'] == 'delete' && $_GET['codigo_evolucao'] != '') {
        $paciente->LoadEvolucao($_GET['codigo_evolucao']);
        $paciente->ApagaDados();
	}
	if(isset($_POST[Salvar])) {
		/*if(is_array($_POST[procexecutado])) {
			foreach($_POST[procexecutado] as $codigo => $procexecutado) {
				$procprevisto = $_POST[procprevisto][$codigo];
				$codigo_dentista = $_POST[codigo_dentista][$codigo];
				$data = converte_data($_POST[data][$codigo], 1);
				$paciente->LoadEvolucao($codigo);
				$paciente->SetDados('procexecutado', $procexecutado);
				$paciente->SetDados('procprevisto', $procprevisto);
				$paciente->SetDados('codigo_dentista', $codigo_dentista);
				$paciente->SetDados('data', $data);
				$paciente->Salvar();
			}
		}*/
		if(!empty($_POST[procexecutado_new]) && !empty($_POST[procprevisto_new]) && !empty($_POST[data_new])) {
			$paciente->SetDados('codigo_paciente', $_GET[codigo]);
			$paciente->SetDados('procexecutado', $_POST[procexecutado_new]);
			$paciente->SetDados('procprevisto', $_POST[procprevisto_new]);
			$paciente->SetDados('codigo_dentista', $_POST[codigo_dentista_new]);
			$paciente->SetDados('data', converte_data($_POST[data_new], 1));
			$paciente->SalvarNovo();
			$paciente->Salvar();
		}
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$acao = '&acao=editar';
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome');
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>


<div class="panel panel-default">
		<div class="panel-body">
			<?php include('submenu.php'); ?>
		</div>
	</div>
<div id="conteudo"></div>
<div class="panel panel-default">
		<div class="panel-heading"><B><?php echo $LANG['patients']['manage_patients']?> - <?php echo $strLoCase?></b></div>
		<div class="panel-body">

  <table class="table">
    
    <tr>
      <td>&nbsp;<?php echo $LANG['patients']['treatment_evolution']?> </td>
    </tr>
  </table>
  <table class="table">
    <tr>
      <td>
        <table class="table table-hover table-bordered">
          <thead>
            <th><?php echo $LANG['patients']['executed_procedure']?></div></th>
            <th><div align="center" class="style4"><?php echo $LANG['patients']['previwed_procedure']?></div></th>
            <th><div align="center" class="style4"><?php echo $LANG['patients']['professional']?> </div></th>
            <th><div align="center" class="style4"><?php echo $LANG['patients']['date']?></div></th>
          </thaed>
          <tbody>
<?php
	$paciente->SetDados('codigo_paciente', $_GET[codigo]);
	$lista = $paciente->ListEvolucao();
	if(is_array($lista)) {
		foreach($lista as $chave => $codigo) {
			$paciente->LoadEvolucao($codigo);
			if($chave % 2 == 0) {
				$td = 'td_even';
			} else {
				$td = 'td_odd';
			}
?>
          <tr>
            <td><div align="left"><input type="text" value="<?php echo $paciente->RetornaDados('procexecutado')?>" size="25" class="form-control" id="procexecutado<?php echo $chave?>" onblur="Ajax('pacientes/atualiza_evolucao', 'evolucao_atualiza', 'codigo=<?php echo $paciente->RetornaDados('codigo')?>&procexecutado='+this.value+'&procprevisto='+document.getElementById('procprevisto<?php echo $chave?>').value+'&data='+document.getElementById('data<?php echo $chave?>').value)" <?php echo ((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'':'readonly="readonly"')?> <?php echo $disable?> /></div></td>
            
            <td><div align="left"><input type="text" value="<?php echo $paciente->RetornaDados('procprevisto')?>" size="25" class="form-control" id="procprevisto<?php echo $chave?>" onblur="Ajax('pacientes/atualiza_evolucao', 'evolucao_atualiza', 'codigo=<?php echo $paciente->RetornaDados('codigo')?>&procexecutado='+document.getElementById('procexecutado<?php echo $chave?>').value+'&procprevisto='+this.value+'&data='+document.getElementById('data<?php echo $chave?>').value)" <?php echo ((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'':'readonly="readonly"')?> <?php echo $disable?> /></div></td>
            <td><div align="left">
<?php
			$dentista = new TDentistas();
			$lista = $dentista->LoadDentista($paciente->RetornaDados('codigo_dentista'));
			$nome = explode(' ', $dentista->RetornaDados('nome'));
			$nome = $nome[0].' '.$nome[count($nome) - 1];
			echo $dentista->RetornaDados('titulo').' '.$nome;
?>
            </td>
            <td><div align="center"><input type="text" value="<?php echo converte_data($paciente->RetornaDados('data'), 2)?>" class="form-control" id="data<?php echo $chave?>" onblur="Ajax('pacientes/atualiza_evolucao', 'evolucao_atualiza', 'codigo=<?php echo $paciente->RetornaDados('codigo')?>&procexecutado='+document.getElementById('procexecutado<?php echo $chave?>').value+'&procprevisto='+document.getElementById('procprevisto<?php echo $chave?>').value+'&data='+this.value)" <?php echo ((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'':'readonly="readonly"')?> <?php echo $disable?> size="12" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></div></td>
            <td><div align="center"><?php echo ((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'<a href="javascript:Ajax(\'pacientes/evolucao\', \'conteudo\', \'cosdigo='.$_GET['codigo'].'&acao=editar&codigo_evolucao='.$paciente->RetornaDados('codigo').'" onclick="return confirmLink(this)"><button class="btn btn-danger" type="button" name="confirm_del" title="Exluir"><span class="glyphicon glyphicon-trash"></span></button></a>':'')?></div></td>
          </tr>
<?php
		}
	}
	if($td == "td_odd") {
		$td = 'td_even';
	} else {
		$td = 'td_odd';
	}
?>
          
        </table>
        <br />
      </fieldset>
        <br />
        <div align="center">
       
       <a href="relatorios/evolucao.php?codigo=<?php echo $_GET['codigo']?>" target="_blank"><button class="btn btn-warning">
                <span class="glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print_evolution']?></button></a>
      </div>
      </td>
    </tr>
  </table>
  <div id="evolucao_atualiza">&nbsp;</div>
<script>
document.getElementById('procexecutado_new').focus();
</script>
