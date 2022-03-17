<?php
  
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}

  $sistema = new sistema();

    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);
?>
<script>
  function muda_valor(input) {
    if(input.value == 'Sim') {
      input.value = 'Não';
    } else {
      input.value = 'Sim';
    }
  }

  $(function(){

    $("div#selectStatus").click(function(){

      var classe = $(this).attr("class");

      $(".corpo_"+classe).fadeIn(500);
      return false;

    });

    $("div#corpoSelect").click(function(){

      return false;

    });

    $("body, div.marcar").click(function(){

      $("div#corpoSelect").fadeOut(300);

    });

    $("div.marcar").click(function(){

      var status = new Array("", "", "", "", "", "");

      status[0] = "<div id=\"compareceu\" class=\"oStatus\"></div> Compareceu";
      status[1] = "<div id=\"falta\" class=\"oStatus\"></div> Faltou";
      status[2] = "<div id=\"desmarcou\" class=\"oStatus\"></div> Desmarcou";
      status[3] = "<div id=\"reagendou\" class=\"oStatus\"></div> Reagendou";
      status[4] = "<div id=\"remarcado\" class=\"oStatus\"></div> Remarcado";
      status[5] = "<div id=\"compromisso\" class=\"oStatus\"></div> Compromisso";

      var idStatus = $(this).attr("idStatus");
      var idHorario = $(this).attr("idHorario");

      var idHora = $(this).attr("hora");
      var idMedico = $(this).attr("idMedico");
      var idData = $(this).attr("idData");

      $.post("agenda/atualiza_ajax.php", {hora: idHora, idMedico: idMedico, data: idData, status: idStatus}).done(function(dados){

        if(dados == "sucesso")
        {
          alert("Status atualizado com sucesso!");
        }else{
          alert("Ocorreu um problema ao atualizar o status, tente novamente!");
        }

      });
      $("#visu_"+idHorario).html("");
      $("#visu_"+idHorario).html(status[idStatus]);
 

      //alert("K");
    });

  });
</script>



<!-- Latest compiled and minified JavaScript -->

<style type="text/css">
.label{
  line-height: 2;
}

#falta{
  background: red;
  height: 20px;
  border-radius: 3px;
  width: 20px;
  float: left;
  margin-right: 5px;
  margin-top: -1px;
}

#desmarcou{
  background: blue;
  height: 20px;
  border-radius: 3px;
  width: 20px;
  float: left;
  margin-right: 5px;
  margin-top: -1px;
}

#remarcado{
  background: violet;
  height: 20px;
  border-radius: 3px;
  width: 20px;
  float: left;
  margin-right: 5px;
  margin-top: -1px;
}

#reagendou{
  background: orange;
  height: 20px;
  border-radius: 3px;
  width: 20px;
  float: left;
  margin-right: 5px;
  margin-top: -1px;
}

#compromisso{
  background: silver;
  height: 20px;
  border-radius: 3px;
  width: 20px;
  float: left;
  margin-right: 5px;
  margin-top: -1px;
}

#compareceu{
  background: green;
  height: 20px;
  border-radius: 3px;
  width: 20px;
  float: left;
  margin-right: 5px;
  margin-top: -1px;
}

#selectStatus{
  width:100%;
  background:#fff;
  border:1px solid silver;
  border-radius:3px;padding:5px;
  cursor: pointer;
}

#selectStatus:hover{
  background: #efeeee;
}

.marcar{
  margin-bottom: 5px;
  padding: 3px;
}

.marcar:hover{
  background: #efeeee;
}

#corpoSelect{
  width:200px; 
  background:#fff;
  position:absolute;
  z-index:5;
  border-radius:5px;
  border:1px solid silver;
  margin-top:10px;
  margin-left:-6px;
  padding:5px;
  display: none;
}

</style>
<body>
  <table class="table">
    <thead>
      <th><?php echo $LANG['calendar']['time']?></th>
      <th><?php echo $LANG['calendar']['patient']?></th>
      <th><?php echo $LANG['calendar']['procedure']?></th>
      <th>Status</th>
      <th><?php echo $LANG['calendar']['time']?></th>
      <th><?php echo $LANG['calendar']['patient']?></th>
      <th><?php echo $LANG['calendar']['procedure']?></th>
      <th>Status</th>
    </thead>
	<tr>

<?php
	if(is_date(converte_data($_GET[pesquisa], 1)) && $_GET[codigo_dentista] != "") {
		$agenda = new TAgendas();

		$par = "F0F0F0";
		$impar = "F8F8F8";
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

        $weekday = date( 'w' , converte_data ( converte_data($_GET['pesquisa'] , 1) , 3));
        $sql = "SELECT * FROM dentista_atendimento WHERE codigo_dentista = " . $_GET['codigo_dentista'] . " AND dia_semana = " . $weekday;
        $atend = mysqli_fetch_assoc ( mysqli_query ($conn, $sql ) );

        $j = 0;
		for($i = 0; $i < count($horario); $i++) {
			if($j % 2 == 0) {
				$odev = $par;
			} else {
				$odev = $impar;
			}
			if($i % 2 == 0) {
				if($i !== 0) {
					echo '</tr> <tr bgcolor="#'.$odev.'" onmouseout="style.background=\'#'.$odev.'\'" onmouseover="style.background=\'#DDE1E6\'">';
				}
				$j++;
				$style = 'style="border-right: 1px; border-right-color=: #CCCCCC; border-right-style: solid; border-color:silver;"';
			} else {
				$style = '';
			}
			$agenda->LoadAgenda(converte_data($_GET[pesquisa], 1), $horario[$i], $_GET[codigo_dentista]);

			if(!$agenda->ExistHorario()) {
				$agenda->SalvarNovo();
			}

      //echo "OK";

			if((converte_data($_GET[pesquisa], 1) < date(Y.'-'.m.'-'.d)) || ($_GET[codigo_dentista] != $_SESSION[codigo] && $_SESSION[nivel] == 'Dentista') || !verifica_nivel('agenda', 'E')) {
				$blur = 'onblur';
                $disable_obs = $disable = 'disabled';
			} else {
				$blur = '';
                $disable_obs = $disable = '';
			}
            if($agenda->RetornaDados('faltou') == 'Sim') {
                $chk = 'checked';
                $val_chk = 'Não';
            } else {
                $chk = '';
                $val_chk = 'Sim';
            }

            if ( $atend['ativo'] <= 0 ) {
                $disable_obs = $disable = 'disabled';
            } else {
                if ( $horario[$i].':00' < $atend['hora_inicio'] || $horario[$i].':00' > $atend['hora_fim'] ) {
                    $disable = 'disabled';
                    $disable_obs = '';
                }
            }

            //echo $disable;

?>
      <td width="7%" align="center" height="23">&nbsp;<?php echo $horario[$i]?></td>
      <td width="15%" align="left">
        <input type="text" size="30" maxlength="90" name="descricao" onkeyup="searchSuggest(this, 'codigo_pac<?php echo $i?>', 'search<?php echo $i?>');" id="descricao<?php echo $i?>" value="<?php echo $agenda->RetornaDados('descricao')?>" <?php echo $disable?> onblur="Ajax('agenda/atualiza', 'agenda_atualiza', 'data=<?php echo $agenda->RetornaDados('data')?>&hora=<?php echo $agenda->RetornaDados('hora')?>:00&descricao='+this.value+'&codigo_dentista=<?php echo $agenda->RetornaDados('codigo_dentista')?>&codigo_paciente='+document.getElementById('codigo_pac<?php echo $i?>').value);"
        onfocus="esconde_itens('searches')" onkeypress="document.getElementById('codigo_pac<?php echo $i?>').value=''" class="form-control" autocomplete="off"><BR>
        <input type="hidden" id="codigo_pac<?php echo $i?>" value="<?php echo $agenda->RetornaDados('codigo_paciente')?>">
        <div class="search" id='search<?php echo $i?>' style="position: absolute"></div>
      </td>
      <td width="6%" align="left"><input type="text" size="13" maxlength="15" name="procedimento" id="procedimento" value="<?php echo utf8_encode($agenda->RetornaDados('procedimento'));?>" <?php echo $disable?> onblur="Ajax('agenda/atualiza', 'agenda_atualiza', 'data=<?php echo $agenda->RetornaDados('data')?>&hora=<?php echo $agenda->RetornaDados('hora')?>:00&procedimento='+this.value+'&codigo_dentista=<?php echo $agenda->RetornaDados('codigo_dentista')?>')" class="form-control" onfocus="esconde_itens('searches')"></td>
      
      <td width="15%" align="left" <?php echo $style?>><!--<input type="checkbox" name="faltou" id="faltou" value="<?php echo $val_chk?>" <?php echo $disable.' '.$chk?> onclick="Ajax('agenda/atualiza', 'agenda_atualiza', 'data=<?php echo $agenda->RetornaDados('data')?>&hora=<?php echo $agenda->RetornaDados('hora')?>:00&faltou='+this.value+'&codigo_dentista=<?php echo $agenda->RetornaDados('codigo_dentista')?>'); muda_valor(this);" onfocus="esconde_itens('searches')"></td>-->

        <div id="selectStatus" class="<?php echo $i; ?>">

          <?php

          $status = $agenda->RetornaDados('status');

          $infoStatus[0] = "<div id=\"compareceu\" class=\"oStatus\"></div> Compareceu";
          $infoStatus[1] = "<div id=\"falta\" class=\"oStatus\"></div> Faltou";
          $infoStatus[2] = "<div id=\"desmarcou\" class=\"oStatus\"></div> Desmarcou";
          $infoStatus[3] = "<div id=\"reagendou\" class=\"oStatus\"></div> Reagendou";
          $infoStatus[4] = "<div id=\"remarcado\" class=\"oStatus\"></div> Remarcado";
          $infoStatus[5] = "<div id=\"compromisso\" class=\"oStatus\"></div> Comprimisso";

        
          if($status == "")
          {
            echo "<span id='visu_$i'>".$infoStatus[0]."</span>";
          }else{
            echo "<span id='visu_$i'>".$infoStatus[$status]."</span>";
          }

          ?>


          <!--<div id="compareceu"></div> Compareceu-->

          

          <div style="float:right;padding-left:5px;font-size:8pt;margin-top:4px;">
            <span class="glyphicon glyphicon-triangle-bottom"></span>
          </div>

          <div id="corpoSelect" class="corpo_<?php echo $i; ?>">
            <div class="marcar" idStatus="0" idHorario="<?php echo $i; ?>" idMedico="<?php echo $agenda->RetornaDados('codigo_dentista'); ?>" hora="<?php echo $agenda->RetornaDados('hora').':00'; ?>" idData="<?php echo $agenda->RetornaDados('data'); ?>"><div id="compareceu" class="oStatus"></div> Compareceu</div>
            <div class="marcar" idStatus="1" idHorario="<?php echo $i; ?>" idMedico="<?php echo $agenda->RetornaDados('codigo_dentista'); ?>" hora="<?php echo $agenda->RetornaDados('hora').':00'; ?>" idData="<?php echo $agenda->RetornaDados('data'); ?>"><div id="falta" class="oStatus"></div> Faltou</div>
            <div class="marcar" idStatus="2" idHorario="<?php echo $i; ?>" idMedico="<?php echo $agenda->RetornaDados('codigo_dentista'); ?>" hora="<?php echo $agenda->RetornaDados('hora').':00'; ?>" idData="<?php echo $agenda->RetornaDados('data'); ?>"><div id="desmarcou" class="oStatus"></div> Desmarcou</div>
            <div class="marcar" idStatus="3" idHorario="<?php echo $i; ?>" idMedico="<?php echo $agenda->RetornaDados('codigo_dentista'); ?>" hora="<?php echo $agenda->RetornaDados('hora').':00'; ?>" idData="<?php echo $agenda->RetornaDados('data'); ?>"><div id="reagendou" class="oStatus"></div> Reagendou</div>
            <div class="marcar" idStatus="4" idHorario="<?php echo $i; ?>" idMedico="<?php echo $agenda->RetornaDados('codigo_dentista'); ?>" hora="<?php echo $agenda->RetornaDados('hora').':00'; ?>" idData="<?php echo $agenda->RetornaDados('data'); ?>"><div id="remarcado" class="oStatus"></div> Remarcado</div>
            <div class="marcar" idStatus="5" idHorario="<?php echo $i; ?>" idMedico="<?php echo $agenda->RetornaDados('codigo_dentista'); ?>" hora="<?php echo $agenda->RetornaDados('hora').':00'; ?>" idData="<?php echo $agenda->RetornaDados('data'); ?>"><div id="compromisso" class="oStatus"></div> Comprimisso</div>
          </div>

        </div>

      </td>

<?php
		}
    $sql = "SELECT `data`, `obs` FROM agenda_obs WHERE data = '".converte_data($_GET['pesquisa'], 1)."' AND codigo_dentista = '".$_GET['codigo_dentista']."'";
    $query = mysqli_query($conn, $sql) or die('Line 128: '.mysqli_error());
    $row = mysqli_fetch_array($query);
    if($row['data'] == '') {
        mysqli_query($conn, "INSERT INTO agenda_obs (data, codigo_dentista) VALUES ('".converte_data($_GET['pesquisa'], 1)."', '".$_GET['codigo_dentista']."')") or die('Line 116: '.mysqli_error());
        $sql = "SELECT data, obs FROM agenda_obs WHERE data = ".converte_data($_GET['pesquisa'], 1);
        $query = mysqli_query($conn, $sql) or die('Line 118: '.mysqli_error());
        $row = mysqli_fetch_array($query);
    }
?>
	</tr>
  </table>
  <BR>
  <table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr style="background: #F8F8F8">
      <td align="center">
        <b><?php echo $LANG['calendar']['comments_of_day']?></b><BR>
        <textarea class="form-control" name="observacoes" cols="100" rows="6" style="overflow:hidden" <?php echo $disable_obs?> onblur='Ajax("agenda/atualizaobs", "agenda_atualiza", "data=<?php echo converte_data($_GET['pesquisa'], 1)?>&codigo_dentista=<?php echo $_GET['codigo_dentista']?>&obs="+this.value.replace(/\n/g, "<br>"))'><?php echo ereg_replace('<br>', "\n", $row['obs'])?></textarea>
      </td>
    </tr>
  </table>
    <br>
  <table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr style="background: #F8F8F8">
      <td width="140" align="center"><a href="relatorios/agenda_consultas.php?data=<?php echo converte_data($_GET[pesquisa], 1)?>&codigo_dentista=<?php echo $_GET[codigo_dentista]?>" target="_blank"><button class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> <?php echo $LANG['calendar']['print_calendar']?></button></a></td>
    </tr>
  </table>
</body>
  <div id="agenda_atualiza"></div>
<?php
	}
?>
