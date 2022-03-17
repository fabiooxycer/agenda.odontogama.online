<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);

	$sistema = new sistema(); 
	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

	$caminho = "logo.jpg";
	if($_GET[confirm_del] == "delete") {
        $sql = "UPDATE `dados_clinica` SET `logomarca` = ''";
        mysqli_query($conn, $sql) or die(mysqli_error());
	}
	if(isset($_POST[send])) {
		if($_FILES['foto']['name'] != "" && ($_FILES['foto']['type'] == 'image/gif' || $_FILES['foto']['type'] == 'image/pjpeg' || $_FILES['foto']['type'] == 'image/jpeg' || $_FILES['foto']['type'] == 'image/png')) {
			$foto = imagecreatefromall($_FILES['foto']['tmp_name'], $_FILES['foto']['name']);
			$factor = imagesx($foto)/imagesy($foto);
            $siz_x = 100;
			$siz_y = round($siz_x/$factor);
			$imagem = imagecreate($siz_x, $siz_y);
			$white = imagecolorallocatealpha($imagem, 255, 255, 255, 127);
			imagecopyresized($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto));
            imagejpeg($imagem, 'logo.jpg');
            $img_data = addslashes(file_get_contents('logo.jpg'));
            $sql = "UPDATE `dados_clinica` SET `logomarca` = '".$img_data."'";
            unlink('logo.jpg');
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
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/css/responsivo.css">
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/bootstrap.min.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript" src="../lib/script.js.php"></script>
</head>
<body style="background-color: #F0F0F0"><center>
<?php
    $sql = "SELECT `logomarca` FROM `dados_clinica`";
    $query = mysqli_query($conn, $sql) or die('Erro: '. mysqli_error());
    $row = mysqli_fetch_array($query);
	if($row['logomarca'] != '') {
		echo '<img src="verfoto_p.php" style="margin-top:5px; border-radius:3px;border:1px solid silver;">';
	}
?><br><br>
<form action="logo.php" method="POST" enctype="multipart/form-data" target="_self">
<table class="table">
    <tr>
        <td>
        
            <!--<button class="btn btn-primary" type="button">
                <span class="glyphicon glyphicon-upload"></span> <b>Incluir Arquivo</b>-->
                <input type="file" name="foto" style="/*opacity:0;*/width:118px;/*margin-top:-20px;margin-left: -55px;height:20px;position:absolute;*/" id="arquivo" onchange="getElementById('filename').value=this.value">
            <!--</button>-->

        </td>
        <td>
        <button name="send" type="submit" class="btn btn-primary" id="Salvar" <?php echo $disable2?>><span class=" glyphicon glyphicon-ok"></span> Salvar</button>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <a <?php echo $href?>"logo.php?confirm_del=delete" <?php echo $onclick?>"return confirmLink(this)"><button style="width:100%;" class="btn btn-danger" type="button"><?php echo $LANG['clinic_information']['delete_image']?></button></a>
        </td>
    </tr>
</table>
        </form>
</body>
</html>
