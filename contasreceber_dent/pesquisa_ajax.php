<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';

header("Content-type: text/html; charset=UTF-8", true);

/*if(!checklog()) {
  die($frase_log);
}*/
?>

<script type="text/javascript">

  function excluir(cod)
  {
    swal({

      title: "Confirmar exclusão",
      type: "question",
      text: "Deseja realmente exluir esse registro?",

      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText:
      '<i class=" glyphicon glyphicon-ok"></i> Confirmar',
      cancelButtonText:
      '<i class="glyphicon glyphicon-remove"></i> Cancelar'

    }).then(function(dismiss){

      if(dismiss === true)
      {
        $.get("contasreceber/extrato_ajax.php", {"codigo": cod, "confirm_del": "delete"}).done(function(){
          swal({
            type: 'success',
            title: 'Sucesso!',
            text: 'Registro excluido com sucesso!'
          });

          Ajax('contasreceber/pesquisa', 'pesquisa', 'pesquisa=');

        });

        
      }

    });
  }

  $(function(){



    $("#dataPagamento, #dtVencimento").mask("00/00/0000");

    $("a#recibo").click(function(){

      var id = $(this).attr("cid");

      window.open("comprovantes/recibo.php?id="+id, id, "width=306, height=349");

    });

    $("#dataPagamento, #dtVencimento").datepicker({
      dateFormat: 'dd/mm/yy',
      dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
      dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
      dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
      monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
      monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
      onSelect: function(date){ 

      }
    });

    $("a#baixar").click(function(){

      var codigo = $(this).attr("tid");

      swal({

        title: "Baixa de contas a receber",
        type: "",
        html: 
        '<table class="table" style="text-align: left;">'+
        '<tr>'+
        '<td>'+
        'Data do pagamento:<br>'+
        '<input type="text" class="form-control" id="dataPagamento" value="<?php echo date("d/m/Y"); ?>">'+
        '</td>'+
        '</tr>'+
        '</table>',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: '<i class=" glyphicon glyphicon-ok"></i> Confirmar',
        cancelButtonText: '<i class="glyphicon glyphicon-remove"></i> Cancelar'

      }).then(function(dismiss){

        if(dismiss === true)
        {
          Ajax('contasreceber/atualiza', 'conta_atualiza', 'codigo='+codigo+'&datapagamento='+$("#dataPagamento").val());
          
          swal({
            type: 'success',
            title: 'Sucesso!',
            text: 'Baixa efetuada com sucesso!'
          }).then(function(){

            swal({

              title: "Recibo de pagamento",
              type: "question",
              text: 'Deseja imprimir o comprovante de pagamento agora?',
              showCloseButton: true,
              showCancelButton: true,
              confirmButtonText: 'Imprimir',
              cancelButtonText: 'Cancelar'

            }).then(function(retorno){

              if(retorno === true)
              {
                window.open("comprovantes/recibo.php?id="+codigo, codigo, "width=306, height=349");
              }

            });

          });

          Ajax('contasreceber/pesquisa', 'pesquisa', 'pesquisa=');
        }

      });

    });


    $("a#vencimento").click(function(){

      var codigo = $(this).attr("tid");

      swal({

        title: "Alterar vencimento",
        type: "",
        html: 
        '<table class="table" style="text-align: left;">'+
        '<tr>'+
        '<td>'+
        'Nova data de vencimento:<br>'+
        '<input type="text" class="form-control" id="dtVencimento">'+
        '</td>'+
        '</tr>'+
        '</table>',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText:
        '<i class=" glyphicon glyphicon-ok"></i> Confirmar',
        cancelButtonText:
        '<i class="glyphicon glyphicon-remove"></i> Cancelar'

      }).then(function(dismiss){

        if(dismiss === true)
        {
          Ajax('contasreceber/atualiza', 'conta_atualiza', 'codigo='+codigo+'&datavencimento='+$("#dtVencimento").val());
          
          swal({
            type: 'success',
            title: 'Sucesso!',
            text: 'Data alterada com sucesso'
          });

          Ajax('contasreceber/pesquisa', 'pesquisa', 'pesquisa=');
        }

      });

    });

  });

</script>

<table class="table table-hover">
  <thead>
    <th>Status</th>
    <th align="left"><?php echo $LANG['accounts_receivable']['deadline']?></th>
    <th align="left"><?php echo $LANG['accounts_receivable']['description']?></th>
    <th align="center"><?php echo $LANG['accounts_receivable']['value']?></th>
    <th>Paciente</th>
    <th>Dentista</th>
    <th align="center">Ações</th>
  </thead>
  <?php
  $conta = new TContas('clinica', 'receber');
  $data = converte_data($_GET[pesquisa], 1);


  $complemento = "WHERE codigo!='' ";

  if(isset($_GET[procurar]))
  {

    $tipo = $_GET["situacao"];
    $dataInicial = date("Y-m-d", strtotime(str_replace("/", "-", $_GET["dataInicial"])));
    $dataFinal = date("Y-m-d", strtotime(str_replace("/", "-", $_GET["dataFinal"])));
    $campo = $_GET["campo"];

    //echo $dataFinal;

    if($campo != "") $complemento="INNER JOIN pacientes ON contasreceber.paciente=pacientes.codigo AND pacientes.nome LIKE '%$campo%' ";

    if($tipo != "") $complemento.="AND contasreceber.status='$tipo' ";
    if($dataInicial != "1969-12-31") $complemento.="AND contasreceber.datavencimento>='$dataInicial' ";
    if($dataFinal != "1969-12-31") $complemento.="AND contasreceber.datavencimento<='$dataFinal'";

    //if($campo != "") $complemento.=" contasreceber.codigo AS teste";
    
  }

  $sql = "SELECT contasreceber.* FROM contasreceber $complemento ORDER BY ordem DESC, datavencimento ASC";

  //echo $sql;

  if($_GET['pg'] != '') {
    $limit = ($_GET['pg']-1)*PG_MAX;
  } else {
    $limit = 0;
    $_GET['pg'] = 1;
  }

  $total_regs = $conta->ListConta($sql);
  $lista = $conta->ListConta($sql.' LIMIT '.$limit.', '.PG_MAX);

  $par = "F0F0F0";
  $impar = "F8F8F8";
  $saldo = 0;
  for($i = 0; $i < count($lista); $i++) {
    if($i % 2 == 0) {
     $odev = $par;
   } else {
     $odev = $impar;
   }

   $conta->LoadConta($lista[$i][codigo]);
   $saldo += $conta->RetornaDados('valor');

   $pacientes = new TPacientes();
   $dentistas = new TDentistas();

   $pacientes->LoadPaciente($conta->RetornaDados('paciente'));
   $dentistas->LoadDentista($conta->RetornaDados('dentista'));

   ?>
   <tr>
    <td width="20%"><?php echo $conta->statusReceber($conta->RetornaDados("status")); ?></td>
    <td width="11%" height="23" align="left"><?php echo converte_data($conta->RetornaDados('datavencimento'), 2)?></td>
    <td width="25%" align="left"><?php echo $conta->RetornaDados('descricao');?></td>
    <td width="2%" align="left"><?php echo $LANG['general']['currency'].' '.money_form($conta->RetornaDados('valor'))?></td>
    <td width="15%"><?php echo mb_convert_case($pacientes->RetornaDados('nome'), MB_CASE_UPPER, "UTF-8"); ?></td>
    <td width="15%"><?php echo mb_convert_case($dentistas->RetornaDados('nome'), MB_CASE_UPPER, "UTF-8"); ?></td>
    <!--<td width="21%" align="left"><input type="text" class="form-control" size="13" name="datapagamento" id="datapagamento" value="<?php echo converte_data($conta->RetornaDados('datapagamento'), 2)?>" onblur="" onKeypress="return Ajusta_Data(this, event);" <?php echo ((!verifica_nivel('contas_receber', 'E'))?'disabled':'')?>></td>-->
    
    <td width="5%" align="center">

      <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          Ação <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">

          <?php

          if($conta->RetornaDados("status") == 0)
          {
            echo"<li><a href=\"javascript:;\" id=\"baixar\" tid='".$conta->RetornaDados('codigo')."'>Dar baixa</a></li>";
            echo"<li><a href=\"javascript:;\" id=\"vencimento\" tid='".$conta->RetornaDados('codigo')."'>Alterar vencimento</a></li>";
          }

          if($conta->RetornaDados("status") == 1)
          {
            echo"<li><a href=\"javascript:;\" id=\"recibo\" cid='".$conta->RetornaDados('codigo')."'>Imprimir recibo</a></li>";
          }

          echo ((verifica_nivel('contas_receber', 'A'))?'<a href="javascript:;"><li><a href="#" onclick="excluir('.$conta->RetornaDados('codigo').');">Excluir</a></li></a>':''); 
          ?>
        </ul>
      </div>    
    </td>
  </tr>
  <?php
}
if($odev == $impar) {
  $odev = $par;
} else {
  $odev = $impar;
}	
?>
<tr>
  <td height="23" align="left" colspan="5">&nbsp;</td>
</tr>
<tr bgcolor="#<?php echo $odev?>" onmouseout="style.background='#<?php echo $odev?>'" onmouseover="style.background='#DDE1E6'">
  <td width="61%" colspan="3" height="23" align="center"><b><?php echo $LANG['accounts_receivable']['total']?></b></td></td>
  <td width="13%" align="right"><font color="#<?php echo $cor?>"><b><?php echo $LANG['general']['currency'].' '.money_form($saldo)?></b></font></td>
  <td width="13%" colspan="3" align="right"></td>
</tr>
</table>
<br>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr bgcolor="#<?php echo $odev?>" onmouseout="style.background='#<?php echo $odev?>'" onmouseover="style.background='#DDE1E6'">
    <td width="100%" align="center">
      <?php
      $pg_total = ceil(count($total_regs)/PG_MAX);
      $i = $_GET['pg'] - 5;
      if($i <= 1) {
        $i = 1;
        $reti = '';
      } else {
        $reti = '...&nbsp;&nbsp;';
      }
      $j = $_GET['pg'] + 5;
      if($j >= $pg_total) {
        $j = $pg_total;
        $retf = '';
      } else {
        $retf = '...';
      }
      echo $reti;
      while($i <= $j) {
        if($i == $_GET['pg']) {
          echo $i.'&nbsp;&nbsp;';
        } else {
          echo '<a href="javascript:;" onclick="javascript:Ajax(\'contasreceber/pesquisa\', \'pesquisa\', \'pesquisa=\'%2BgetElementById(\'procurar\').value%2B\'&peri=\'%2BgetElementById(\'peri\').value%2B\'&pg='.$i.'\')">'.$i.'</a>&nbsp;&nbsp;';
        }
        $i++;
      }
      echo $retf;
      ?>
    </td>
  </tr>
</table>
<div id="conta_atualiza"></div>
