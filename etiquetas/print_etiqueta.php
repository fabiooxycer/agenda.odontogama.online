<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	
	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
?><html>
<head></head>
<body topmargin="0" leftmargin="0" bgcolor="#F0F0F0" style="font-family: Verdana">
<?php
	$sql = htmlentities($_GET['sql']);
	$query = mysqli_query($conn, $sql) or die('Erro: '.mysqli_error());
	while($row = mysqli_fetch_array($query)) {
		$nome = $row[nome];
		if($nome == '') {
            $nome = $row['nomefantasia'];
		}
		$end = $row['endereco'];
		$bairro = $row['bairro'];
		$cidade = $row['cidade'];
		$estado = $row['estado'];
		$cep = $row['cep'];
		$obs = $row['obs_etiqueta'];
?>
			  <font size="2" face="Roman 17cpi"><?php echo utf8_encode($nome);?> <?php echo isset($_GET['nasc']) ? '(' .converte_data($row['nascimento'],2). ')' : ''?><br>
              <font size="1" face="Roman 17cpi"><?php echo utf8_encode($end)?> - <?php echo utf8_encode($bairro);?><br>
              <font size="1" face="Roman 17cpi"><?php echo utf8_encode($cidade)?> - <?php echo utf8_encode($estado)?> - <?php echo $LANG['reports']['zip']?>: <?php echo $cep?><br>
              <font size="2" face="Roman 17cpi"><?php echo utf8_encode($obs)?><br>
              <font size="1" face="Roman 17cpi"><br><br><br>


<?php
	}
?>
<script>
window.print();
</script>
</body>
</html>
