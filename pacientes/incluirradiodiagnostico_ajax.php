<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
    $sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	//header("Content-type: image/jpeg", true);
	if(!checklog()) {
		die($frase_log);
	}
	if(!empty($_POST['texto'])) {
        $sql = "UPDATE radiografias SET diagnostico = '".utf8_decode ( htmlspecialchars( utf8_encode($_POST['texto']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') )."' WHERE codigo = ".$_GET['codigo'];
        mysqli_query($conn, $sql);
	}
?>
<script language="javascript" type="text/javascript">
alert('Radiodiagnóstico salvo com sucesso!');
</script>
