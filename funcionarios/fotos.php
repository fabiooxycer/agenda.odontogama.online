<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	$caminho = "fotos/".$_GET[codigo].".jpg";
	if($_GET[confirm_del] == "delete\')") {
        $sql = "UPDATE `funcionarios` SET `foto` = '' WHERE `codigo` = '".$_GET['codigo']."'";
        mysqli_query($conn, $sql) or die(mysqli_error());
	}
	if(isset($_POST[send])) {
		if($_FILES['foto']['name'] != "" && ($_FILES['foto']['type'] == 'image/gif' || $_FILES['foto']['type'] == 'image/pjpeg' || $_FILES['foto']['type'] == 'image/jpeg' || $_FILES['foto']['type'] == 'image/png')) {
            //$caminho = $_FILES['foto']['name'];
			//move_uploaded_file($_FILES['foto']['tmp_name'], $caminho);
			$foto = imagecreatefromall($_FILES['foto']['tmp_name'], $_FILES['foto']['name']);
			$siz_x = 106;
			$siz_y = 140;
			$imagem = imagecreatetruecolor($siz_x, $siz_y);
			$white = imagecolorallocate($imagem, 255, 255, 255);
			imagecopyresized($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto));
            imagejpeg($imagem, 'teste.jpg');
            $img_data = addslashes(file_get_contents('teste.jpg'));
            $sql = "UPDATE `funcionarios` SET `foto` = '".$img_data."' WHERE `codigo` = '".$_GET['codigo']."'";
            unlink('teste.jpg');
            mysqli_query($conn, $sql) or die(mysqli_error());
		}
	}
	$disable = '';
	$href = 'href=';
	$onclick = 'onclick=';
	if(checknivel('Dentista') || checknivel('Funcionario') || $_GET['disabled'] == 'yes') {
		$disable = 'disabled';
		$href = '';
		$onclick = '';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gerenciador Clínico Odontológico Smile Odonto - Administração Odontológica Em Suas Mãos</title>
<link href="../css/smile.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../lib/script.js.php"></script>
</head>
<body style="background-color: #F0F0F0"><center>
<?php
    $sql = "SELECT `foto` FROM `funcionarios` WHERE `codigo` = '".$_GET['codigo']."'";
    $query = mysqli_query($conn, $sql) or die('Erro: '. mysqli_error());
    $row = mysqli_fetch_array($query);
	if($row['foto'] != '') {
		echo '<img src="verfoto_p.php?codigo='.$_GET['codigo'].'" border="0">';
	}  else {
		echo '<img src="verfoto_p.php?codigo='.$_GET['codigo'].'&padrao=no_photo" border="0">';
	}
?><br><br>
<form action="fotos.php?codigo=<?php echo $_GET[codigo]?>" method="POST" enctype="multipart/form-data" target="_self">
<input type="file" <?php echo $disable?> name="foto" size="5" class="forms"><br>
<input type="submit" <?php echo $disable?> class="forms" value="<?php echo $LANG['employee']['send']?>" name="send">
</form>
<br>
<a <?php echo $href?>"fotos.php?codigo=<?php echo $_GET[codigo]?>" <?php echo $onclick?>"return confirmLink(this)"><?php echo $LANG['employee']['delete_photo']?></a>
</center>
</body>
</html>
