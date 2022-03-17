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
    $nome_dentista = encontra_valor('dentistas', 'codigo', $_GET['codigo_dentista'], 'nome');
    $sexo_dentista = encontra_valor('dentistas', 'codigo', $_GET['codigo_dentista'], 'sexo');
?>
<font size="3"><?php echo $LANG['reports']['schedule_of'].' '.(($sexo_dentista == 'Masculino')?'<b>Dr.':'<b>Dra.').' '.utf8_decode(utf8_encode($nome_dentista))?></b> <?php echo $LANG['reports']['for_the_date']?> <b><?php echo converte_data($_GET['data'], 2).' ('.ucwords(utf8_decode(utf8_encode(nome_semana($_GET['data'])))).')'?></font><br /><br />
  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr style="font-size: 11px">
      <th width="8%" align="left" style="font-size: 11px">&nbsp;<?php echo $LANG['reports']['time']?></th>
      <th width="30%" align="left" style="font-size: 11px"><?php echo $LANG['reports']['patient']?></th>
      <th width="12%" align="left" style="font-size: 11px;"><?php echo $LANG['reports']['procedure']?></th>
      <th width="12%" align="left" style="font-size: 11px;">Status</th>
      <th width="8%" align="left" style="font-size: 11px;">&nbsp;<?php echo $LANG['reports']['time']?></th>
      <th width="30%" align="left" style="font-size: 11px"><?php echo $LANG['reports']['patient']?></th>
      <th width="12%" align="left" style="font-size: 11px"><?php echo $LANG['reports']['procedure']?></th>
      <th width="12%" align="left" style="font-size: 11px">Status</th>
    </tr>
    <tr class="td_even">
<?php
	if(is_date($_GET['data']) && $_GET['codigo_dentista'] != "") {
        //$sql = "SELECT * FROM agenda_obs WHERE data = '" . $_GET['data'] . "' codigo_dentista = " . $_GET['codigo_dentista'];
        //$obs = mysqli_fetch_assoc ( mysqli_query ( $sql ) );
		$agenda = new TAgendas();
		for($i = 7; $i <= 22; $i++) {
			if(strlen($i) < 2) {
				$horas[] = "0".$i.":";
			} else {
				$horas[] = $i.":";
			}
		}
		$minutos = array('00', '15', '30', '45');
		foreach($horas as $hora) {
			foreach($minutos as $minuto) {
				$horario[] = $hora.$minuto;
			}
		}
		$j = 0;
		for($i = 0; $i < count($horario); $i++) {
			if($j % 2 == 0) {
				$td_class = 'td_even';
			} else {
				$td_class = 'td_odd';
			}
			if($i % 2 == 0) {
				if($i !== 0) {
					echo '</tr> <tr class="'.$td_class.'">';
				}
				$j++;
                //$styles = 'style="border-right: 1px; border-right-color=: #CCCCCC; border-right-style: solid"';
			} else {
                $styles = '';
			}
			$agenda->LoadAgenda($_GET[data], $horario[$i], $_GET[codigo_dentista]);
			if(!$agenda->ExistHorario()) {
				$agenda->SalvarNovo();
			}
?>
      <td align="center" height="23">&nbsp;<?php echo $horario[$i]?></td>
      <td align="left"><?php echo utf8_encode($agenda->RetornaDados('descricao'))?>&nbsp;</td>
      <td align="left" <?php echo $styles?>><?php echo utf8_encode($agenda->RetornaDados('procedimento'))?>&nbsp;</td>
      <td align="left" <?php echo $styles?>>

      	<?php
      	$status[0] = "<div id=\"compareceu\" class=\"oStatus\"></div> Compareceu";
      	$status[1] = "<div id=\"falta\" class=\"oStatus\"></div> Faltou";
      	$status[2] = "<div id=\"desmarcou\" class=\"oStatus\"></div> Desmarcou";
      	$status[3] = "<div id=\"reagendou\" class=\"oStatus\"></div> Reagendou";
      	$status[4] = "<div id=\"remarcado\" class=\"oStatus\"></div> Remarcado";
      	$status[5] = "<div id=\"compromisso\" class=\"oStatus\"></div> Comprimisso";
      	
      	echo $status[$agenda->RetornaDados('status')]; ?>&nbsp;</td>
<?php
            $j++;
		}
	}
?>
  </tr>
</table>
<?php/*<div align="justify">
    <strong><?php echo $LANG['calendar']['comments_of_day']?></strong>:<br />
    <?php echo $obs['obs']?>
</div>*/?>
<script>
window.print();
</script>
<?php
    include "../timbre_foot.php";
?>
