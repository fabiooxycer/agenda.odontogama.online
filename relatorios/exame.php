<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
    header("Content-type: text/html; charset=ISO-8859-1", true);
    if(!checklog()) {
        die($frase_log);
    }
    include "../timbre_head.php";
    $paciente = new TPacientes();
	$clinica = new TClinica();
	$exame = new TExame();
	$exame->Codigo_Paciente = $_GET['codigo'];
	$exame->LoadInfo();
	if($exame->Exame == '') {
        $exame->SalvarNovo();
	}
    if(isset($_POST['send'])) {
        $exame->Exame = utf8_decode ( htmlspecialchars( utf8_encode($_POST['exame']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
        $exame->Salvar();
	}
    $paciente->LoadPaciente($_GET['codigo']);
	$clinica->LoadInfo();
	$exame->LoadInfo();
?>
<br />
<div align="center"><font size="4"><b><?php echo $LANG['reports']['request_for_examination']?></b></font></div><br /><br />
<font size="2"><?php echo $LANG['reports']['patient']?>:<br />
<b><?php echo $paciente->RetornaDados('nome')?></b><br /></font><br /><br />
<br />
<?php
    if($_GET['acao'] == 'editar') {
?>
<div align="center">
<form action="exame.php?codigo=<?php echo $_GET['codigo']?>" method="POST">
<textarea name="exame" class="forms" cols="130" rows="30"><?php echo $exame->Exame?></textarea><br />
<br />
<input type="submit" name="send" value="<?php echo $LANG['reports']['send']?>" class="forms">
</form>
</div>
<?php
    } else {
?>
<div align="justify">
<?php echo nl2br($exame->Exame)?>
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
