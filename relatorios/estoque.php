<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	include "../timbre_head.php";
?>
<font size="3"><?php echo $LANG['reports']['clinic_stock_report']?></font><br /><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th width="80%" align="left"><?php echo $LANG['reports']['description']?>
    </th>
    <th width="20%" align="left"><?php echo $LANG['reports']['quantity']?>
    </th>
  </tr>
<?php
    $i = 0;
    $sql = stripslashes($_GET['sql']);
    $query = mysql_query($sql) or die('Line 58: '.mysql_error());
    while($row = mysql_fetch_array($query)) {
        if($i % 2 === 0) {
            $td_class = 'td_even';
        } else {
            $td_class = 'td_odd';
        }
?>
  <tr class="<?php echo $td_class?>" style="font-size: 12px">
    <td><?php echo utf8_encode($row['descricao'])?>
    </td>
    <td><?php echo $row['quantidade']?>
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
