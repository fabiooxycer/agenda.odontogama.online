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
<p align="center"><font size="3"><b><?php echo $LANG['reports']['fee_table_report']?><br />
  <?php echo encontra_valor('convenios', 'codigo', $_GET['codigo_convenio'], 'nomefantasia')?></b></font></p><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr style="font-size: 11px">
    <th width="7%" align="center" style="font-size: 11px"><?php echo $LANG['reports']['code']?>
    </th>
    <th width="45%" align="center" style="font-size: 11px"><?php echo $LANG['reports']['procedure']?>
    </th>
    <th width="12%" align="center" style="font-size: 11px"><?php echo $LANG['reports']['private_value']?>
    </th>
    <th width="12%" align="center" style="font-size: 11px"><?php echo $LANG['reports']['plan_value']?>
    </th>
    <th width="24%" align="center" style="font-size: 11px" colspan="2"><?php echo $LANG['reports']['differences']?>
    </th>
  </tr>
<?php
    $i = 0;
	$sql = stripslashes($_GET['sql']);
	$query = mysqli_query($conn, $sql) or die('Line 57: '.mysqli_error());
    while($row = mysqli_fetch_array($query)) {
        if($i % 2 === 0) {
            $td_class = 'td_even';
        } else {
            $td_class = 'td_odd';
        }
		$valor_particular = encontra_valor('honorarios_convenios', 'codigo_convenio = 1 AND codigo_procedimento', $row['codigo'], 'valor');
		$valor_convenio = encontra_valor('honorarios_convenios', 'codigo_convenio = '.$_GET['codigo_convenio'].' AND codigo_procedimento', $row['codigo'], 'valor');
?>
  <tr class="<?php echo $td_class?>" style="font-size: 11px">
    <td><?php echo $row['codigo']?>
    </td>
    <td><?php echo utf8_encode($row['procedimento'])?>
    </td>
    <td align="right"><?php echo $LANG['general']['currency'].' '.number_format($valor_particular, 2, ',', '.')?>
    </td>
    <td align="right"><?php echo $LANG['general']['currency'].' '.number_format($valor_convenio, 2, ',', '.')?>
    </td>
    <td align="right"><?php echo $LANG['general']['currency'].' '.number_format(($valor_particular - $valor_convenio), 2, ',', '.')?>
    </td>
    <td align="right"><?php echo @number_format((100 - ($valor_convenio / $valor_particular * 100)), 2, ',', '.')?> %
    </td>
  </tr>
<?php
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
