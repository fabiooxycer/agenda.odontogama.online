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
	mysqli_query($conn, "DELETE FROM radiografias WHERE codigo = '".$_GET['codigo_foto']."'") or die(mysqli_error($conn));
?>
<script language="javascript" type="text/javascript">
window.parent.location.href="javascript:Ajax('pacientes/radio', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&modelo=<?php echo $_GET['modelo']?>&acao=editar')";
</script>
