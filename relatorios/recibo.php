<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

	if(!checklog()) {
		die($frase_log);
	}

	$sistema = new sistema(); 
	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

?><html>
<head></head>
<body topmargin="0" leftmargin="0">
<?php
    $query = mysqli_query($conn, "SELECT * FROM v_orcamento WHERE codigo_parcela = ".$_GET['codigo_parcela']) or die('Line 42: '.mysqli_error());
	$row = mysqli_fetch_array($query);
?>
<br />
<font size="3" face="Courier New">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u><b><?php echo $LANG['reports']['receipt']?></b></u></font><br />
<font size="1" face="Courier New">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LANG['reports']['no_fiscal_validity']?></font><br /><br />
<font size="2" face="Courier New">&nbsp;&nbsp;<?php echo $LANG['reports']['patient']?>: <b><?php echo $row['paciente']?></b><br />
&nbsp;&nbsp;<?php echo $LANG['reports']['professional']?>: <b><?php echo (($row['sexo_dentista'] == 'Masculino')?'Dr. ':'Dra. ').$row['dentista']?></b><br />
&nbsp;&nbsp;<?php echo $LANG['reports']['value']?>: <b><?php echo $LANG['general']['currency'].' '.money_form($row['valor'])?></b><br />
&nbsp;&nbsp;<?php echo $LANG['reports']['due_date']?>: <b><?php echo converte_data($row['data'], 2)?></b><br />
&nbsp;&nbsp;<?php echo $LANG['reports']['payment_date']?>: <b><?php echo converte_data($row['datapgto'], 2)?></b><br /><br /><br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;______________________________</font><br />
<font size="1" face="Courier New">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo utf8_decode($LANG['reports']['employee_signature']);?></font><br />

<script>
window.print();
</script>
</body>
</html>
