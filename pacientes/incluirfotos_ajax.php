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
		$codigo = next_autoindex('fotospacientes');
		//$caminho = "fotos/".$_GET[codigo]."/".$codigo.".jpg";
		$foto = imagecreatefromall($_FILES['arquivo']['tmp_name'], $_FILES['arquivo']['name']);
        $ratio = imagesx($foto) / imagesy($foto);
        $siz_x = 222;
		$siz_y = $siz_x / $ratio;
		$imagem = imagecreatetruecolor($siz_x, $siz_y);
		$white = imagecolorallocate($imagem, 255, 255, 255);
		if(!imagecopyresampled($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto))) {
			echo '<script>alert("Favor enviar apenas fotos com\ntamanho menor que 1MB!")</script>'; die();
		}
		imagejpeg($imagem, 'teste.jpg');
        $img_data = addslashes(file_get_contents('teste.jpg'));
        $sql = "INSERT INTO `fotospacientes` (`codigo_paciente`, `foto`, `legenda`) VALUES ('".$_GET['codigo']."', '".$img_data."', '".utf8_decode ( htmlspecialchars( utf8_encode($_POST['legenda']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') )."')";
        unlink('teste.jpg');
        mysqli_query($conn, $sql) or die(mysqli_error());
	}
?>
<script language="javascript" type="text/javascript">
window.parent.location.href="javascript:Ajax('pacientes/fotos', 'conteudo', 'codigo=<?php echo $_GET[codigo]?>&acao=editar')";
</script>
