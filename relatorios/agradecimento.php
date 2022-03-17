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
    include "../timbre_head.php";
    $paciente = new TPacientes();
	$clinica = new TClinica();
	$agradecimento = new TAgradecimento();
	$agradecimento->Codigo_Paciente = $_GET['codigo'];
	$agradecimento->LoadInfo();
	if($agradecimento->Agradecimento == '') {
        $agradecimento->SalvarNovo();
	}
	if(isset($_POST['send'])) {
        $agradecimento->Agradecimento = utf8_decode ( htmlspecialchars( utf8_encode($_POST['agradecimento']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
        $agradecimento->Salvar();
	}
    $paciente->LoadPaciente($_GET['codigo']);
	$clinica->LoadInfo();
	$agradecimento->LoadInfo();
?>
<br />
<div align="center"><font size="4"><b><?php echo $LANG['reports']['thanks']?></b></font></div><br /><br />
<font size="2"><?php echo $LANG['reports']['patient']?>:<br />
<b><?php echo $paciente->RetornaDados('nome')?></b><br /></font><br /><br />
<br />
<?php
    if($_GET['acao'] == 'editar') {
?>
<div align="center">
<form action="agradecimento.php?codigo=<?php echo $_GET['codigo']?>" method="POST">
<textarea name="agradecimento" class="forms" cols="130" rows="30"><?php echo $agradecimento->Agradecimento?></textarea><br />
<br />
<input type="submit" name="send" value="<?php echo $LANG['reports']['send']?>" class="forms">
</form>
</div>
<?php
    } else {
?>
<div align="justify">
<?php echo nl2br($agradecimento->Agradecimento)?>
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
