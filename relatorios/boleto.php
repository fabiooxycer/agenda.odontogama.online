<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	if(!checklog()) {
		die($frase_log);
	}
	include "../timbre_head.php";
	$sql = "SELECT * FROM v_orcamento WHERE codigo_orcamento = ".$_GET['codigo'];
	$query = mysqli_query($conn, $sql) or die('Line 40: '.mysqli_error());
	$row = mysqli_fetch_array($query);
?>
<font size="3"><?php echo $LANG['reports']['patient']?>: <b><?php echo utf8_encode($row['paciente']).' ['.$row['codigo_paciente'].']'?></b><br /></font><font style="font-size: 3px;">&nbsp;<br /></font>
<font size="2"><?php echo $LANG['reports']['plots_for_odontological_treatment']?> <b><?php echo (($row['sexo_dentista'] == 'Masculino')?'Dr.':'Dra.').' '.utf8_encode($row['dentista'])?></b></font><br /><br />
<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse">
<?php
    $i = 1;
	$sql = "SELECT * FROM v_orcamento WHERE codigo_orcamento = ".$_GET['codigo']." ORDER BY codigo_parcela LIMIT ".$row['parcelas'];
    $query = mysqli_query($conn, $sql) or die('Line 48: '.mysqli_error());
    while($row = mysqli_fetch_array($query)) {
?>
  <tr style="font-size: 12px">
    <td width="38%" align="center" valign="middle">
      <font size="5"><?php echo $i?></font>&nbsp;&nbsp;<img align="middle" src="codigo_barra.php?codigo=<?php echo (completa_zeros($row['codigo_parcela'], ZEROS))?>" border="0">
    </td>
    <td width="32%" align="center" valign="middle">
      <table width="95%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width="38%" valign="top" align="left" height="20"><font style="font-size: 11px;">
            Valor: </font>
          </td>
          <td width="62%" valign="top" align="left"><font style="font-size: 11px;">
            <b><?php echo $LANG['general']['currency'].' '.money_form($row['valor'])?></b>
          </td>
        </tr>
        <tr>
          <td align="left"><font style="font-size: 11px;">
            <?php echo $LANG['reports']['payment_due']?>: </font>
          </td>
          <td align="left"><font style="font-size: 11px;">
            <b><?php echo converte_data($row['data'], 2)?></b>
          </td>
        </tr>
      </table>
    </td>
    <td width="30%" align="center" valign="top">
      <font style="font-size: 8px;"><?php echo $LANG['reports']['employee_signature']?></font>
    </td>
  </tr>
<?php
        flush();
        $i++;
    }
?>
</table>
<script>
window.print();
</script>
<?php
    include "../timbre_foot.php";
?>
