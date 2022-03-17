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
?>
<p align="center"><font size="3"><b><?php echo $LANG['reports']['cash_flow']?></b></font></p><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th width="15%" align="center"><?php echo $LANG['reports']['date']?></th>
    <th width="40%" align="left"><?php echo $LANG['reports']['description']?></th>
    <th width="15%" align="center"><?php echo $LANG['reports']['debit']?></th>
    <th width="15%" align="center"><?php echo $LANG['reports']['credit']?></th>
    <th width="15%" align="center"><?php echo $LANG['reports']['total']?></th>
  </tr>
<?php
    $i = $saldo = 0;
    $saldoc = $saldod = 0;
	$sql = stripslashes($_GET['sql']);
	$query = mysqli_query($conn, $sql) or die('Line 57: '.mysqli_error());
    while($row = mysqli_fetch_array($query)) {
        if($i % 2 === 0) {
            $td_class = 'td_even';
        } else {
            $td_class = 'td_odd';
        }
        if($row['dc'] == "-") {
            $debito = $LANG['general']['currency'].' '.number_format($row['valor'], 2, ',', '.');
            $credito = '';
        } else {
            $debito = '';
            $credito = $LANG['general']['currency'].' '.number_format($row['valor'], 2, ',', '.');
        }
        if($row['dc'] == '-') {
            $saldo -= $row['valor'];
            $saldod += $row['valor'];
        } else {
            $saldo += $row['valor'];
            $saldoc += $row['valor'];
        }
        if($saldo < 0) {
            $cor = "FF0000";
        } else {
            $cor = "000000";
        }
?>
  <tr class="<?php echo $td_class?>" style="font-size: 12px">
    <td align="center"><?php echo converte_data($row['data'], 2)?></td>
    <td><?php echo $row['descricao']?></td>
    <td align="right"><font color="#FF0000"><?php echo $debito?></font></td>
    <td align="right"><font color="#000000"><?php echo $credito?></font></td>
    <td align="right"><font color="#<?php echo $cor?>"><?php echo $LANG['general']['currency'].' '.number_format($saldo, 2, ',', '.')?></font></td>
  </tr>
<?php
        $i++;
    }
?>
  <tr height="7">
    <td colspan="5"></td>
  </tr>
  <tr class="<?php echo $td_class?>" style="font-size: 12px">
    <td align="center" colspan="2"><b><?php echo $LANG['reports']['total']?></b></td>
    <td align="right"><font color="#FF0000"><b><?php echo $LANG['general']['currency'].' '.number_format($saldod, 2, ',', '.')?></b></font></td>
    <td align="right"><font color="#000000"><b><?php echo $LANG['general']['currency'].' '.number_format($saldoc, 2, ',', '.')?></b></font></td>
    <td align="right"><font color="#<?php echo $cor?>"><b><?php echo $LANG['general']['currency'].' '.number_format($saldo, 2, ',', '.')?></b></font></td>
  </tr>
</table>
<?php
    include "../timbre_foot.php";
?>
<script>
window.print();
</script>
