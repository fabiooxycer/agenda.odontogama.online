<?php
  
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

    if(!checklog()) {
        die($frase_log);
    }
    include "../timbre_head.php";
    $paciente = new TPacientes();
	$clinica = new TClinica();
	$laudo = new TLaudo();
	$laudo->Codigo_Paciente = $_GET['codigo'];
	$laudo->LoadInfo();
	if($laudo->Laudo == '') {
        $laudo->SalvarNovo();
	}
	if(isset($_POST['send'])) {
        $laudo->Laudo = utf8_decode ( htmlspecialchars( utf8_encode($_POST['laudo']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
        $laudo->Salvar();
	}
    $paciente->LoadPaciente($_GET['codigo']);
	$clinica->LoadInfo();
	$laudo->LoadInfo();
?>
<br />
<div align="center"><font size="4"><b><?php echo $LANG['reports']['dental_opinion']?></b></font></div><br /><br />
<font size="2"><?php echo $LANG['reports']['patient']?>:<br />
<b><?php echo $paciente->RetornaDados('nome')?></b><br /></font><br /><br />
<br />
<?php
    if($_GET['acao'] == 'editar') {
?>
<div align="center">
<form action="laudo.php?codigo=<?php echo $_GET['codigo']?>" method="POST">
<textarea name="laudo" class="forms" cols="130" rows="30"><?php echo $laudo->Laudo?></textarea><br />
<br />
<input type="submit" name="send" value="<?php echo $LANG['reports']['send']?>" class="forms">
</form>
</div>
<?php
    } else {
?>
<div align="justify">
<?php echo nl2br($laudo->Laudo)?>
</div>
<script>
window.print();
</script>
<?php
    }
?>
<div align="center">
<br /><br /><br /><br /><br /><br /><br /><br />
<?php echo $clinica->Cidade.'/'.$clinica->Estado.', '.date('d').' '.$LANG['reports']['of'].' '.nome_mes(date('m')).' '.$LANG['reports']['of'].' '.date('Y')?>
<br /><br /><br /><br /><br /><br />
<?php echo (($_SESSION['sexo'] == 'Masculino')?'Dr.':'Dra.').' '.$_SESSION['nome']?><br />
<?php echo $_SESSION['conselho_tipo'].'/'.$_SESSION['conselho_estado'].' '.$_SESSION['conselho_numero']?>
</div>
<?php
    include "../timbre_foot.php";
?>
