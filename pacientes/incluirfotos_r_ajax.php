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
	if(!empty($_FILES['arquivo']['name'])) {
        $sql = "INSERT INTO radiografias (codigo_paciente, foto, legenda, data, modelo) VALUES ('".$_GET['codigo']."', '".addslashes(file_get_contents($_FILES['arquivo']['tmp_name']))."', '".utf8_decode ( htmlspecialchars( utf8_encode($_POST['legenda']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') )."', '".converte_data($_POST['data'], 1)."', '".$_POST['modelo']."')";
        mysqli_query($conn, $sql);
	}
?>
<script language="javascript" type="text/javascript">
window.parent.location.href="javascript:Ajax('pacientes/radio', 'conteudo', 'codigo=<?php echo $_GET['codigo']?>&modelo=<?php echo $_POST['modelo']?>&acao=editar')";
</script>
