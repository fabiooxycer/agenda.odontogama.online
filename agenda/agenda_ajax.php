<?php
  
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=UTF-8", true);
	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('agenda', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }

    $sistema = new sistema();

    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);
?>

<script type="text/javascript">

$(function(){

  $("input#procurar, input#dataInicial, input#dataFinal").datepicker({
    dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    onSelect: function(date){ 

      var idDentista = document.getElementById('codigo_dentista').options[document.getElementById('codigo_dentista').selectedIndex].value;

      if(idDentista == "") return false;

      Ajax('agenda/pesquisa', 'pesquisa', 'pesquisa='+date+'&codigo_dentista='+idDentista);

    }
  });

});

</script>

<!--<link rel="stylesheet" href="css/calendario.css">


<div id='calendario' name='calendario' style='display:none;position:absolute;'>
<?php
	//include "../lib/calendario.inc.php";
?>
</div>-->

<div class="panel panel-default">
  <div class="panel-body">
    <span class="h3"><span class="glyphicon glyphicon-briefcase"></span> <?php echo $LANG['calendar']['manage_calendar']?></span>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <table class="table">
      <tr>
        <td>
          <span>Dentista</span><br>
          <select class="form-control" name="codigo_dentista" class="forms" id="codigo_dentista" onchange="javascript:Ajax('agenda/pesquisa', 'pesquisa', 'pesquisa='+getElementById('procurar').value+'&codigo_dentista='+this.options[this.selectedIndex].value)">
            <option></option>
              <?php
                  $dentista = new TDentistas();
                  $lista = $dentista->ListDentistas("SELECT * FROM `dentistas` WHERE `ativo` = 'Sim' ORDER BY `nome` ASC");
                  for($i = 0; $i < count($lista); $i++) {
                    if($_SESSION[cpf] == $lista[$i][cpf]) {
                      echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.utf8_decode(utf8_encode($lista[$i][nome])).'</option>';
                    } else {
                      echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.utf8_decode(utf8_encode($lista[$i][nome])).'</option>';
                    }
                  }
              ?>     
            </select>
          </td>
          <td>
            <?php echo $LANG['calendar']['date']?><br><input name="procurar" id="procurar" value="<?php echo converte_data(hoje(), 2)?>" type="text" size="20" maxlength="40"
          onkeyup="javascript:Ajax('agenda/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&codigo_dentista='+getElementById('codigo_dentista').options[getElementById('codigo_dentista').selectedIndex].value)"
          onfocus="javascript:Ajax('agenda/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&codigo_dentista='+getElementById('codigo_dentista').options[getElementById('codigo_dentista').selectedIndex].value)"
          
          class="form-control">
        </td>
      </tr>
    </table>
  </div>
</div>


<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-briefcase"></span> Gerenciar Agenda</div>
  <div class="panel-body">
    <div id="pesquisa"></div>
  </div>
</div>
  
  <script>
  	function atualizaData() {
  		Ajax('agenda/pesquisa', 'pesquisa', 'pesquisa=<?php echo converte_data(hoje(), 2)?>&codigo_dentista=<?php echo $_SESSION[codigo]?>');
  	}
<?php
  	if($_SESSION[nivel] == 'Dentista') {
  		echo 'atualizaData();';
  	}
?>
  </script>

