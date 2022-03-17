<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
if(!checklog()) {
  echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
  die();
}

$sistema = new sistema(); 
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

if($_GET[confirm_del] == "delete") {
  mysqli_query($conn, "DELETE FROM `contasreceber` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
}
if(isset($_POST[Salvar])) {
  $obrigatorios[1] = 'datavencimento';
  $obrigatorios[] = 'descricao';
  $obrigatorios[] = 'valor';
  $i = $j = 0;
  foreach($_POST as $post => $valor) {
   $i++;
   if(array_search($post, $obrigatorios) && $valor == "") {
     $j++;
     $r[$j] = '<font color="#FF0000">';
   }
 }
 if($j == 0) {
   $caixa = new TContas('clinica', 'receber');
   $caixa->SetDados('datavencimento', converte_data($_POST[datavencimento], 1));
   $caixa->SetDados('descricao', $_POST[descricao]);
   $caixa->SetDados('valor', $_POST[valor]);
   $caixa->SalvarNovo();
   $caixa->Salvar();
 }
}
?>

<style type="text/css">

  #txtOrdem, #txtPaciente, #txtDentista{
    display: none
  }

  #proc{
    border-top: 1px solid transparent;
    border-collapse: none;
  }

  .pesqProcedimento{

    padding: 10px;
    background: #fff;
    height: auto;
    position: absolute;
    border: 1px solid #a6d2ff;
    margin-top: -1px;
    z-index: 2;
    display: none;
  }

  .table > tbody + tbody {

    border-top: 2px solid transparent;

  }

  #table label{
    font-weight: bold;
  }

  #fundoEscuro{
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
    display: none;
  }

  #pesqPaciente{
    display: block;
    padding: 0;
  }

</style>

<script type="text/javascript">

  var idPaciente = "";

  function selecionarPaciente(id, nome)
  {
    $("#campo").val(nome);
    idPaciente = id;
    $("#pesqPaciente").html("");
  }

  $(function(){

    $("#pesquisar").click(function(){

      Ajax("contasreceber/pesquisa", "pesquisa", $("#frmPesquisar").serialize());
      $("#fundoEscuro, .pesqProcedimento").hide();

    });

    $("#campo").keyup(function(){

      var valor = $(this).val();
      if(valor == "")
      {
        $("#pesqPaciente").html("");
        return false;
      }

      var pesquisa = $(this).val();

      $.post("pacientes/nova_ordem_ajax.php", {tipo: 'pac', 'texto': pesquisa}).done(function(dados){

        $("#pesqPaciente").html(dados);

      });

    });

    $("input#dataInicial, input#dataFinal").datepicker({
      dateFormat: 'dd/mm/yy',
      dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
      dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
      dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
      monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
      monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
      onSelect: function(date){ 

      }
    });

    $("#dataInicial, #dataFinal").mask("00/00/0000");

    $("#avancado").click(function(){

      $("#fundoEscuro, .pesqProcedimento").show();

    });

    $("#fechar").click(function(){

      $("#fundoEscuro, .pesqProcedimento").hide();

    });

  });

</script>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
  <div class="panel-body">
    <form action="javascript:;" id="frmPesquisar">
      <table class="table">
      <!--<tr>
        <td>
          <input type="hidden" name="peri" id="peri" value="mesatual">
          <input type="radio" name="pesq" id="pesqdia" value="dia" onclick="document.getElementById('peri').value='dia'"><label for="pesqdia"> <?php echo $LANG['accounts_receivable']['day_month_year']?></label>&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          <input type="radio" name="pesq" id="pesqmes" value="mes" onclick="document.getElementById('peri').value='mes'"><label for="pesqmes"> <?php echo $LANG['accounts_receivable']['month_year']?></label>&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          <input type="radio" name="pesq" id="pesqmesatual" checked="checked" value="mesatual" onclick="javascript:Ajax('contasreceber/pesquisa', 'pesquisa', 'peri=mesatual')"><label for="pesqmesatual"> <?php echo $LANG['accounts_receivable']['current_month']?></label>&nbsp;&nbsp;&nbsp;
        </td>
      </tr>-->
      <tr>
        <td colspan="3">
          <input name="procurar" placeholder="Pesquisar por nome do paciente, cpf, rg ou descrição." id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('contasreceber/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&peri='+document.getElementById('peri').value)" onKeypress="return Ajusta_DMA(this, event, document.getElementById('peri').value);" onclick="if(document.getElementById('pesqdia').checked) {abreCalendario(this);}">
          
          <div id="fundoEscuro"></div>
          <div class="pesqProcedimento" style="width: 650px;">

            <table class="table" id="table">
              <tr>
                <td>
                  <label for="situacao">Situação</label>
                  <select id="situacao" name="situacao" class="form-control">
                    <option value="">Selecione</option>
                    <option value="0">Abertos</option>
                    <option value="1">Recebido</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label>Data de vencimento</label>
                  <input tupe="text" id="dataInicial" name="dataInicial" class="form-control" placeholder="__/__/____">
                </td>
                <td>
                  <label><br></label>
                  <input tupe="text" id="dataFinal" name="dataFinal" class="form-control" placeholder="__/__/____">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <label>Paciente</label>
                  <input type="text" id="campo" name="campo" class="form-control" placeholder="Nome do paciente">
                  <div class="pesqProcedimento" style="width:519px;" id="pesqPaciente"></div>
                </td>
              </tr>
              <tr>
                <td align="right">
                  <button class="btn btn-primary" id="pesquisar">
                    <span class="glyphicon glyphicon-search"></span> Pesquisar
                  </button>
                </td>
                <td>
                  <button class="btn btn-default" id="fechar">
                    <span class="glyphicon glyphicon-remove"></span> Fechar
                  </button>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </td>
      <td>
        <a href="javascript:;" id="avancado">
          <span class="glyphicon glyphicon-plus"></span> Busca avançada
        </a>
      </td>
    </tr>
  </table>
</div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="  glyphicon glyphicon-circle-arrow-down"></span> <b>Contas a Receber da Clínica</b></div>
  <div class="panel-body">

    <?php
    if(verifica_nivel('contas_receber', 'I')) {
      ?>
      <form id="form2" name="form2" method="POST" action="contasreceber/extrato_ajax.php" onsubmit="formSender(this, 'conteudo'); this.reset(); return false;">
        <div class="panel panel-default" id="conteudo_central">
          <div class="panel-heading"><span class="glyphicon glyphicon-asterisk"></span> <b>Nova conta a receber</b></div>
          <div class="panel-body">
            <table class="table">
              <tr>
                <td width="4%">
                </td>
                <td width="12%"><?php echo $LANG['accounts_receivable']['deadline']?> <br />
                  <input type="text" size="13" value="<?php echo converte_data(hoje(), 2)?>" name="datavencimento" id="datavencimento" class="form-control">
                </td>
                <td width="58%"><?php echo $LANG['accounts_receivable']['description']?> <br />
                  <input type="text" size="80" name="descricao" id="descricao" class="form-control">
                </td>
                <td width="16%"><?php echo $LANG['accounts_receivable']['value']?> <br />
                  <input type="text" size="20" name="valor" id="valor" class="form-control" onKeypress="return Ajusta_Valor(this, event);">
                </td>
                <td width="10%"> <br />
                  <button type="submit" name="Salvar" id="Salvar" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?php echo $LANG['accounts_receivable']['save']?></button>
                </td>
                <td width="3%">
                </td>
              </tr>
            </table>
          </div>
        </div>
      </form>
      <?php
    }
    ?>

    <div id="pesquisa"></div>
    <script>
      Ajax('contasreceber/pesquisa', 'pesquisa', 'pesquisa=');
    </script>
  </div>
</div>