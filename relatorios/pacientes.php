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
<p align="center"><font size="3"><b><?php echo $LANG['reports']['patients_report']?></b></font></p><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th width="15%" align="left"><?php echo $LANG['reports']['clinical_sheet']?>
    </th>
    <th width="38%" align="left"><?php echo $LANG['reports']['name']?>
    </th>
    <th width="25%" align="left"><?php echo $LANG['reports']['city']?>
    </th>
    <th width="20%" align="left"><?php echo $LANG['reports']['telephone']?>
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
?>
  <tr class="<?php echo $td_class?>" style="font-size: 12px">
    <td><?php echo $row['codigo']?>
    </td>
    <td><?php echo utf8_encode($row['nome'])?>
    </td>
    <td><?php echo utf8_encode($row['cidade'].'/'.$row['estado'])?>
    </td>
    <td><?php echo utf8_encode($row['telefone1'])?>
    </td>
  </tr>
<?php
        $i++;
    }
?>
</table>
<?php
    include "../timbre_foot.php";
?>
<script>
window.print();
</script>
