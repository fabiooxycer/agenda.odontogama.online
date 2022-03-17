<?php
    
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
	$sistema = new sistema(); 
  	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	if($_GET[confirm_del] == "delete\')") {
        $sql = "UPDATE `pacientes` SET `foto` = '' WHERE `codigo` = '".$_GET['codigo']."'";
        mysqli_query($conn, $sql) or die(mysqli_error());
	}
	if(isset($_POST['send'])) {
		if($_FILES['foto']['name'] != "" && ($_FILES['foto']['type'] == 'image/gif' || $_FILES['foto']['type'] == 'image/pjpeg' || $_FILES['foto']['type'] == 'image/jpeg' || $_FILES['foto']['type'] == 'image/png')) {
            //$caminho = $_FILES['foto']['name'];
			//move_uploaded_file($_FILES['foto']['tmp_name'], $caminho);
			$foto = imagecreatefromall($_FILES['foto']['tmp_name'], $_FILES['foto']['name']);
			$ratio = imagesx($foto) / imagesy($foto);
			$siz_x = 106;
			$siz_y = $siz_x / $ratio;
			$imagem = imagecreatetruecolor($siz_x, $siz_y);
			$white = imagecolorallocate($imagem, 255, 255, 255);
			if(!imagecopyresampled($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto))) {
				echo '<script>alert("Favor enviar apenas fotos com\ntamanho menor que 1MB!")</script>'; die();
			}
            imagejpeg($imagem, 'teste.jpg', 100);
            $img_data = addslashes(file_get_contents('teste.jpg'));
            $check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `pacientes` WHERE `codigo` = '".$_GET['codigo']."'"));
            if($check > 0) {
                $sql = "UPDATE `pacientes` SET `foto` = '".$img_data."' WHERE `codigo` = '".$_GET['codigo']."'";
            } else {
                $sql = "INSERT INTO `pacientes` (`codigo`, `foto`) VALUES ('".$_GET['codigo']."', '".$img_data."')";
            }
            unlink('teste.jpg');
            mysqli_query($conn, $sql) or die(mysqli_error());
		}
	}
	$disable = '';
	$href = 'href=';
	$onclick = 'onclick=';
	if($_GET['disabled'] == 'yes') { //if(checknivel('Dentista') || checknivel('Funcionario')) {
		$disable = 'disabled';
		$href = '';
		$onclick = '';
	}
?>



  <div class="panel panel-default">
  
  <div class="panel-body">

<?php
    $sql = "SELECT `foto` FROM `pacientes` WHERE `codigo` = '".$_GET['codigo']."'";
    $query = mysqli_query($conn, $sql) or die('Erro: '. mysqli_error());
    $row = mysqli_fetch_array($query);
	if($row['foto'] != '') {
		echo '<img src="verfoto_p.php?codigo='.$_GET['codigo'].'" border="0"  style="width: 100px;"">';
	} else {
		echo '<img src="verfoto_p.php?codigo='.$_GET['codigo'].'&padrao=no_photo" border="0" style="width: 100px;">';
	}
?><br><br>
<form action="fotos.php?codigo=<?php echo $_GET['codigo']?>" method="POST" enctype="multipart/form-data" target="_self">
<input type="file" <?php echo $disable?> name="foto" size="5" class="forms"><br>
<input type="submit" <?php echo $disable?> class="forms" value="<?php echo $LANG['patients']['save']?>" name="send">
</form>
<br>
<!--
<a <?php echo $href?>"fotos.php?codigo=<?php echo $_GET['codigo']?>" <?php echo $onclick?>"return confirmLink(this)"><?php echo $LANG['patients']['delete_photo']?></a>
-->
</div></div>