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
	$sql = "SELECT nome FROM pacientes WHERE codigo = ".$_GET['codigo'];
	$query = mysqli_query($conn, $sql) or die('Line 40: '.mysqli_error());
	$row = mysqli_fetch_array($query);
?>
<font size="3"><?php echo $LANG['reports']['treatment_evolution_of']?> <b><?php echo utf8_encode($row['nome'])?> [<?php echo $_GET['codigo']?>]</b></font><br /><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th width="30%" align="left"><?php echo $LANG['reports']['executed_procedure']?>
    </th>
    <th width="30%" align="left"><?php echo $LANG['reports']['previwed_procedure']?>
    </th>
    <th width="30%" align="left"><?php echo $LANG['reports']['professional']?>
    </th>
    <th width="10%" align="left"><?php echo $LANG['reports']['date']?>
    </th>
  </tr>
<?php
    $i = 0;
    $sql = "SELECT * FROM v_evolucao WHERE codigo_paciente = ".$_GET['codigo']." ORDER BY data ASC";
    $query = mysqli_query($conn, $sql) or die('Line 58: '.mysqli_error());
    while($row = mysqli_fetch_array($query)) {
        if($i % 2 === 0) {
            $td_class = 'td_even';
        } else {
            $td_class = 'td_odd';
        }
?>
  <tr class="<?php echo $td_class?>" style="font-size: 12px">
    <td><?php echo utf8_encode($row['executado'])?>
    </td>
    <td><?php echo utf8_encode($row['previsto'])?>
    </td>
    <td><?php echo (($row['sexo_dentista'] == 'Masculino')?'Dr.':'Dra.').' '.$row['dentista']?>
    </td>
    <td><?php echo converte_data($row['data'], 2)?>
    </td>
  </tr>
<?php
        $i++;
    }
?>
</table>
<script>
//window.print();
</script>
<?php
    include "../timbre_foot.php";
?>
