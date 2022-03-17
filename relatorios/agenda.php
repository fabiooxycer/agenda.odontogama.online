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
	$sql = "SELECT nome FROM pacientes WHERE codigo = ".$_GET['codigo'];
	$query = mysqli_query($conn, $sql) or die('Line 40: '.mysqli_error());
	$row = mysqli_fetch_array($query);
?>
<font size="3"><?php echo $LANG['reports']['scheduled_consultations_of']?> <b><?php echo utf8_encode($row['nome'])?> [<?php echo utf8_encode($_GET['codigo'])?>]</b></font><br /><br />
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th width="15%" align="left"><?php echo $LANG['reports']['date']?>
    </th>
    <th width="15%" align="left"><?php echo $LANG['reports']['time']?>
    </th>
    <th width="25%" align="left"><?php echo $LANG['reports']['procedure']?>
    </th>
    <th width="30%" align="left"><?php echo $LANG['reports']['professional']?>
    </th>
    <th width="15%" align="left"><?php echo $LANG['reports']['missed']?>
    </th>
  </tr>
<?php
    $i = 0;
    $sql = "SELECT * FROM v_agenda WHERE codigo_paciente = ".$_GET['codigo']." ORDER BY data ASC";
    $query = mysqli_query($conn, $sql) or die('Line 60: '.mysqli_error());
    while($row = mysqli_fetch_array($query)) {
        if($i % 2 === 0) {
            $td_class = 'td_even';
        } else {
            $td_class = 'td_odd';
        }
?>
  <tr class="<?php echo $td_class?>" style="font-size: 12px">
    <td><?php echo converte_data($row['data'], 2)?>
    </td>
    <td><?php echo substr($row['hora'], 0, 5)?>
    </td>
    <td><?php echo utf8_encode($row['procedimento'])?>
    </td>
    <td><?php echo (($row['sexo_dentista'] == 'Masculino')?'Dr.':'Dra.').' '.utf8_encode($row['nome_dentista'])?>
    </td>
    <td>
    <?php
        $status[0] = "<div id=\"compareceu\" class=\"oStatus\"></div> Compareceu";
        $status[1] = "<div id=\"falta\" class=\"oStatus\"></div> Faltou";
        $status[2] = "<div id=\"desmarcou\" class=\"oStatus\"></div> Desmarcou";
        $status[3] = "<div id=\"reagendou\" class=\"oStatus\"></div> Reagendou";
        $status[4] = "<div id=\"remarcado\" class=\"oStatus\"></div> Remarcado";
        $status[5] = "<div id=\"compromisso\" class=\"oStatus\"></div> Comprimisso";

        echo $status[$row['faltou']];

    ?>
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
