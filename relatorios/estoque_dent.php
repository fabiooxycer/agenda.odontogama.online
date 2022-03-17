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
    $nome_dentista = $_SESSION['nome'];
    $sexo_dentista = $_SESSION['sexo'];
?>
<font size="3"><?php echo $LANG['reports']['professional_stock_report'].' '.(($sexo_dentista == 'Masculino')?'do <b>Dr.':'da <b>Dra.').' '.$nome_dentista?></font><br /><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th width="80%" align="left"><?php echo $LANG['reports']['description']?>
    </th>
    <th width="20%" align="left"><?php echo $LANG['reports']['qunatity']?>
    </th>
  </tr>
<?php
    $i = 0;
    $sql = stripslashes($_GET['sql']);
    $query = mysqli_query($conn, $sql) or die('Line 58: '.mysqli_error());
    while($row = mysqli_fetch_array($query)) {
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
