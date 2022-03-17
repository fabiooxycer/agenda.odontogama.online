<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: image/jpeg", true);

    $sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	if(!checklog()) {
		die($frase_log);
	}
	$sql = "SELECT * FROM radiografias WHERE codigo = '".$_GET['codigo']."'";
	$query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($query);
    switch($_GET['tamanho']) {
        case '':
        case 'original': {
            echo $row['foto'];
        }; break;
        case 'thumb': {
            $fd = fopen('img_tmp.jpg', 'w+');
            fwrite($fd, $row['foto']);
            fclose($fd);
            $foto = imagecreatefromjpeg('img_tmp.jpg');
            unlink('img_tmp.jpg');
            if(imagesx($foto) > 222) {
                $ratio = imagesx($foto) / imagesy($foto);
                $siz_x = 222;
                $siz_y = $siz_x / $ratio;
            } else {
                $siz_x = imagesx($foto);
                $siz_y = imagesy($foto);
            }
            $imagem = imagecreatetruecolor($siz_x, $siz_y);
            $white = imagecolorallocate($imagem, 255, 255, 255);
            imagecopyresampled($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto));
            imagejpeg($imagem, '', 100);
        }; break;
        case 'a4': {
            $fd = fopen('img_tmp.jpg', 'w+');
            fwrite($fd, $row['foto']);
            fclose($fd);
            $foto = imagecreatefromjpeg('img_tmp.jpg');
            unlink('img_tmp.jpg');
            if(imagesx($foto) > 650) {
                $ratio = imagesx($foto) / imagesy($foto);
                $siz_x = 650;
                $siz_y = $siz_x / $ratio;
            } else {
                $siz_x = imagesx($foto);
                $siz_y = imagesy($foto);
            }
            $imagem = imagecreatetruecolor($siz_x, $siz_y);
            $white = imagecolorallocate($imagem, 255, 255, 255);
            imagecopyresampled($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto));
            imagejpeg($imagem, '', 100);
        }; break;
    }
?>
