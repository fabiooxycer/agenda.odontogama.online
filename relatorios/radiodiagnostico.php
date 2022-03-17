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
	include "../timbre_head.php";
	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM radiografias WHERE codigo = ".$_GET['codigo']));
?>
<p align="center"><font size="3"><b><?php echo $LANG['patients']['radio_diagnosis']?></b></font></p>
<br />
<div align="center"><img src="../pacientes/verfoto_r.php?codigo=<?php echo $_GET['codigo']?>&tamanho=a4"  /></div>
<br />
<table width="650" align="center">
  <tr height="30" valign="top">
    <td width="15%"><b><?php echo $LANG['patients']['patient']?>:</b></td>
    <td width="85%"><?php echo encontra_valor('pacientes', 'codigo', $row['codigo_paciente'], 'nome')?></td>
  </tr>
  <tr height="30" valign="top">
    <td><b><?php echo $LANG['patients']['date']?>:</b></td>
    <td><?php echo converte_data($row['data'], 2)?></td>
  </tr>
  <tr height="30" valign="top">
    <td><b><?php echo $LANG['patients']['legend']?>:</b></td>
    <td><?php echo $row['legenda']?></td>
  </tr>
  <tr height="30" valign="top">
    <td colspan="2"><b><?php echo $LANG['patients']['radio_diagnosis']?>:</b><br />
      <?php echo nl2br($row['diagnostico'])?></td>
  </tr>
</table>
<?php
    include "../timbre_foot.php";
?>
<script>
window.print();
</script>
