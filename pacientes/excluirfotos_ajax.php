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
	$caminho = "fotos/".$_GET[codigo]."/".$_GET[codigo_foto].".jpg";
	mysqli_query($conn, "DELETE FROM `fotospacientes` WHERE `codigo` = '".$_GET[codigo_foto]."'") or die(mysqli_error());
	unlink($caminho);
?>
<script language="javascript" type="text/javascript">
window.parent.location.href="javascript:Ajax('pacientes/fotos', 'conteudo', 'codigo=<?php echo $_GET[codigo]?>&acao=editar')";
</script>
