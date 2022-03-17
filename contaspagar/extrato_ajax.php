<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
/*if(!checklog()) {
  echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
  die();
}*/


$sistema = new sistema(); 
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

if($_GET[confirm_del] == "delete") {
  mysqli_query($conn, "DELETE FROM `contaspagar` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
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

  $forma_pagamento = $_POST["forma_pagamento"];
  $fornecedor = $_POST["fornecedor"];
  $vencimento = $_POST["dataInicial"];
  $total = $_POST["total"];
  $entrada = $_POST["entrada"];
  $parcelas = $_POST["parcelas"];
  $data = $_POST["data"];
  $descricao = $_POST["descricao"];
  $pagamento_entrada = $_POST["pagamento_entrada"];

  $total = str_replace(",", ".", $total);

    if($_POST['status'] == "calcular") // calcula as parecelas do cheque e permite alteração das datas.
    {

      $moeda = new moeda();

      echo "<form action='javascript:;' id='frmVencimentos'>";

      echo "<input type='hidden' name='forma_pagamento' value='$forma_pagamento'>";
      echo "<input type='hidden' name='total' value='$total'>";
      echo "<input type='hidden' name='entrada' value='$entrada'>";
      echo "<input type='hidden' name='parcelas' value='$parcelas'>";
      echo "<input type='hidden' name='fornecedor' value='$fornecedor'>";
      echo "<input type='hidden' name='status' value='add'>";
      echo "<input type='hidden' name='Salvar' value=''>";
      echo "<input type='hidden' name='descricao' value='$descricao'>";
      echo "<input type='hidden' name='pagamento_entrada' value='$pagamento_entrada'>";

      echo "<table class=\"table\" style=\"text-align: left; font-size: 10pt;\">";
      echo "<thead>";
      echo "<th>Parcela</th>";
      echo "<th>Valor</th>";
      echo "<th>Vencimento</th>";
      echo "<th>|</th>";
      echo "<th>Parcela</th>";
      echo "<th>Valor</th>";
      echo "<th>Vencimento</th>";
      echo "</thead>";
      echo "<tbody>";

      $hoje = date("Y-m-d");

      $data = new DateTime($hoje);

      if($parcelas < 2)
      {
        $vlParcelas = ($total-$entrada);
      }else{
        $vlParcelas = round(($total-$entrada)/$parcelas, 2);
      }

      //cho $v
      
      for($i = 1; $i <= $parcelas; $i++)
      {
        $data->modify("+1 month");
        $dataParcela = $data->format("d/m/Y");

        if($i%2 == 1) echo "<tr>"; // verifica se é impar

        echo "<td>".$i."/".$parcelas."</td>";
        echo "<td>R$ ".$moeda->formatar(round($vlParcelas, 2))."</td>";
        echo "<td><input type='text' class='form-control' value='$dataParcela' id='data' name='data[]' style='width: 100px;'></td>";
        
        if($i%2 == 1) echo "<td>|</td>";
        if($i%2 == 0) echo "</tr>"; //verifica se é impar

      }

      echo "</tbody>";
      echo "<input type='hidden' name='comissao' value='$_POST[comissao]'>";
      echo "</table>";

      echo "</form>";
      exit;
    }

    if($_POST["status"] == "add")
    {
      if($parcelas < 2)
      {
        $vlParcelas = ($total-$entrada);
      }else{
        $vlParcelas = round(($total-$entrada)/$parcelas, 2);
      }

      if($parcelas > 0)
      {

        for($i = 0; $i < $parcelas; $i++) // Salva parcelas no contas a pagar
        {
          $nova_descricao = $descricao." ".($i+1)."/$parcelas";
          echo $nova_descricao." ".$data[$i]." - ".$vlParcelas."\n";

          $caixa = new TContas('clinica');
          $caixa->SetDados('datavencimento', converte_data($data[$i], 1));
          $caixa->SetDados('descricao', utf8_encode($nova_descricao));
          $caixa->SetDados('valor', $vlParcelas);
          $caixa->setDados('fornecedor', $fornecedor);
          $caixa->setDados('valor_entrada', $entrada);
          $caixa->setDados('forma_pagamento', $forma_pagamento);
          $caixa->SalvarNovo();
          $caixa->Salvar();
        }

      }else{ // pagamento a vista
        $caixa = new TContas('clinica');
        $caixa->SetDados('datavencimento', converte_data($vencimento, 1));
        $caixa->SetDados('descricao', $descricao);
        $caixa->SetDados('valor', $total);
        $caixa->setDados('fornecedor', $fornecedor);
        $caixa->setDados('status', '1');
        $caixa->setDados('datapagamento', date("Y-m-d"));
        $caixa->setDados('forma_pagamento', $forma_pagamento);
        $caixa->SalvarNovo();
        $caixa->Salvar();

        # SALVAR NO CAIXA O HISTÓRICO DE ENTRADA
        $fluxo = new TCaixa();
        $fluxo->setDados("data", date("Y-m-d"));
        $fluxo->setDados("dc", "-");
        $fluxo->setDados("valor", $total);
        $fluxo->setDados("descricao", "Pagamento: ".$descricao);
        $fluxo->setDados("modo_pagamento", $forma_pagamento);

        $fluxo->SalvarNovo();
        $fluxo->Salvar();
      }

      if($entrada > 0) // Salva no fluxo de caixa a entrada já paga em contas a pagar (Negativa saldo).
      {

        # SALVA NO CONTAS A PAGAR O VALOR JÁ PAGO PELA ENTRADA #
        $caixa = new TContas('clinica');
        $caixa->SetDados('datavencimento', date("Y-m-d"));
        $caixa->SetDados('descricao', "Pagamento de entrada");
        $caixa->SetDados('valor', $entrada);
        $caixa->setDados('fornecedor', $fornecedor);
        $caixa->setDados('datapagamento', date("Y-m-d"));
        $caixa->setDados('status', '1');
        //$caixa->setDados('valor_entrada', $entrada);
        $caixa->setDados('forma_pagamento', $pagamento_entrada);
        $caixa->SalvarNovo();
        $caixa->Salvar();

        # SALVAR NO CAIXA O HISTÓRICO DE ENTRADA
        $fluxo = new TCaixa();
        $fluxo->setDados("data", date("Y-m-d"));
        $fluxo->setDados("dc", "-");
        $fluxo->setDados("valor", $entrada);
        $fluxo->setDados("descricao", "Entrada: ".$descricao);
        $fluxo->setDados("modo_pagamento", $pagamento_entrada);

        $fluxo->SalvarNovo();
        $fluxo->Salvar();
      }

      exit;
    }
  }
}

$fornecedor = new TFornecedores();
$dados_fornecedor = $fornecedor->ListFornecedores();
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

  .cadastrar{
    background: #fff;
    height: auto;
    border: 1px solid #a6d2ff;
    z-index: 2;
    margin: 0 auto;
    display: block;
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

  #fEscuro{
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

  .aparecer{
    /*display: none;*/
  }

</style>

<script type="text/javascript">

  var vlEntrada = "";
  var qtParcela = "0";
  var resultado = 0;
  var total = 0;
  var tipo = "vista";

  function getTotal(obj)
  {
    var valor = $(obj).val();

    valor = valor.replace(",", ".");
    total = valor;
  }

  function entrada_1(obj)
  {
    var tipo = $(obj).attr("tipo");
    var valor = $(obj).val();

    if(valor == "")
    {
      $("#entrada").attr("readonly", "true");
    }else{
      $("#entrada").removeAttr("readonly");
    }
  }

  function calParcela(tipo, obj)
  {
    var obj = $(obj).val();

    if(tipo == "entrada") vlEntrada = obj;
    if(tipo == "parcela") qtParcela = obj;

    if(eval(vlEntrada) > eval(total)) return $("span#resultado").html("<b><span style='color: red;'>O valor de entrada não pode ser maior que o valor total.</span></b>");

    if(qtParcela == 1 || qtParcela == "1")
    {
      resultado = eval(total-vlEntrada);
      resultado.toFixed(2);

      $("span#resultado").html("<b>R$ "+vlEntrada+" + "+qtParcela+"x R$ "+resultado+"</b>");
      return false;

    }else{ // Calculo Parcelado

      resultado = eval((total-vlEntrada)/qtParcela);
      resultado = resultado.toFixed(2);

      $("span#resultado").html("<b>R$ "+vlEntrada+" + "+qtParcela+"x R$ "+resultado+"</b>");
      return false;
    }
  }

  $(function(){

    $("#entrada").mask("000000000");

    $("#btCadastrar").click(function(){

      swal({

        type: '',
        title: 'Aguarde',
        text: 'processando informações...',
        showCancelButton: false,
        showConfirmButton: false

      });

      var tipo = $("#forma_pagamento_cadastro").val();
      var complemento = "";

      //if(tipo == "3" || tipo == "5")
      if(qtParcela != "0")
      {
        complemento = "&status=calcular";
      }else{
        complemento = "&status=add";
      }

      var dados = $("#frmCadastro").serialize()+complemento;
      $.post("contaspagar/extrato_ajax.php", dados).done(function(dados){

        //if(tipo != "3" && tipo != "5")
        if(qtParcela == "0")
        {

          swal({

            type: 'success',
            title: 'Sucesso',
            text: 'Contas a pagar cadastradas com sucesso!',

            showCloseButton: true,
            showCancelButton: false,

            confirmButtonText: 'Concluir'

          }).then(function(){

            $("#fEscuro").hide();
            $("#frmCadastro input").val("");

            Ajax("contaspagar/pesquisa", "pesquisa", $("#frmPesquisar").serialize());
            $("#fundoEscuro, .pesqProcedimento").hide();

          });

          return false;
        }

        swal({

          type: '',
          title: 'Parcelas',
          html: dados,

          showCloseButton: true,
          showCancelButton: true,

          confirmButtonText: 'Confirmar',
          calcelButtonText: 'Cancelar'

        }).then(function(dados){

          if(dados === true) // cofirmou o cadastro
          {
            var frm = $("#frmVencimentos").serialize();

            swal({

              type: '',
              title: 'Aguarde',
              text: 'Cadastrando informações...',
              showCancelButton: false,
              showConfirmButton: false

            });

            $.post("contaspagar/extrato_ajax.php", frm).done(function(dados){

              swal({

                type: 'success',
                title: 'Sucesso',
                text: 'Contas a pagar cadastradas com sucesso!',

                showCloseButton: true,
                showCancelButton: false,

                confirmButtonText: 'Concluir'

              }).then(function(){

                $("#fEscuro").hide();
                $("#frmCadastro input").val("");

                Ajax("contaspagar/pesquisa", "pesquisa", $("#frmPesquisar").serialize());
                $("#fundoEscuro, .pesqProcedimento").hide();


              });

            });
          } 

        });

      });


    });

    $("#novaConta").click(function(){

      $("#fEscuro").show();

    });

    $("#pesquisar").click(function(){

      var valor = $(this).val();

      Ajax("contaspagar/pesquisa", "pesquisa", $("#frmPesquisar").serialize());
      $("#fundoEscuro, .pesqProcedimento").hide();

    });

    $("input#dataInicial, input#dataFinal, input#dataVencimento").datepicker({
      dateFormat: 'dd/mm/yy',
      dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
      dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
      dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
      monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
      monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
      onSelect: function(date){ 

      }
    });

    $("#forma_pagamento_cadastro").change(function(){

      var valor = $(this).val();

      if(valor == "3" || valor == "5")
      {
        $("td.aparecer, span.aparecer").show();
      }else{
        $("td.aparecer, span.aparecer").hide();
      }

    });

    $("#dataInicial, #dataFinal").mask("00/00/0000");

    $("#avancado, #procurar").click(function(){

      $("#fundoEscuro, .pesqProcedimento").show();

    });

    $("button#fechar").click(function(){

      $("#fundoEscuro, .pesqProcedimento, #fEscuro").hide();

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
          <input name="procurar" placeholder="Pesquisar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('contasreceber/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&peri='+document.getElementById('peri').value)" onKeypress="return Ajusta_DMA(this, event, document.getElementById('peri').value);" onclick="if(document.getElementById('pesqdia').checked) {abreCalendario(this);}">
          
          <div id="fundoEscuro"></div>
          <div class="pesqProcedimento" style="width: 650px;">

            <table class="table" id="table">
              <tr>
                <td>
                  <label for="situacao">Situação</label>
                  <select id="situacao" name="situacao" class="form-control">
                    <option value="">Selecione</option>
                    <option value="0">Abertos</option>
                    <option value="1">Pago</option>
                  </select>
                </td>
                <td>
                  <label for="forma_pagamento">Forma de pagamento</label>
                  <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                    <option value="">-- Forma de pagamento --</option>
                    <option value="1">Dinheiro</option>
                    <option value="3">Cheque</option>
                    <option value="2">Cartão de débito</option>
                    <option value="6">Cartão de crédito</option>
                    <option value="5">Boleto</option>
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
                  <label for="campo">Fornecedor</label>
                  <select id="campo" name="campo" class="form-control">
                    <option value="">--- Fornecedores ---</option>
                    <?php

                    for($i = 0; $i < count($dados_fornecedor); $i++)
                    {
                      echo "<option value=\"".$dados_fornecedor[$i]["codigo"]."\">".$dados_fornecedor[$i]["nome"]."</option>";
                    }

                    ?>
                  </select>
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

<form action="javascript:;" id="frmCadastro">
  <input type="hidden" name="Salvar" value="">
  <table id="fEscuro">
    <tr>
      <td>

        <div class="panel panel-default cadastrar" style="width: 650px;">
          <div class="panel-heading"><b>Cadastrar nova conta</b></div>
          <div class="panel-body">

            <table class="table" id="table">
              <tr>
                <td>
                  <label for="forma_pagamento">Forma de pagamento</label>
                  <select id="forma_pagamento_cadastro" name="forma_pagamento" required class="form-control">
                    <option value="1">Dinheiro</option>
                    <option value="3">Cheque</option>
                    <option value="2">Cartão de débito</option>
                    <option value="6">Cartão de crédito</option>
                    <option value="5">Boleto</option>
                  </select>
                </td>
                <td>
                  <label for="fornecedor">Fornecedor</label>
                  <select id="fornecedor" name="fornecedor" class="form-control">
                    <option value="">--- Fornecedores ---</option>
                    <?php

                    for($i = 0; $i < count($dados_fornecedor); $i++)
                    {
                      echo "<option value=\"".$dados_fornecedor[$i]["codigo"]."\">".$dados_fornecedor[$i]["nome"]."</option>";
                    }

                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label>Data de vencimento</label>
                  <input tupe="text" id="dataVencimento" required name="dataInicial" class="form-control" placeholder="__/__/____">
                </td>

                <td>
                  <label>Valor total</label>
                  <input tupe="text" id="total" name="total" required class="form-control" placeholder="Ex: 500" onKeyUp="getTotal(this);">
                </td>

              </tr>
              <tr>
                <td class="aparecer">
                  <label>Forma de entrada:</label>
                  <select id="pagamento_entrada" class="form-control" name="pagamento_entrada" onChange="entrada_1(this);">
                    <option value="">Sem entrada</option>
                    <option value="1">Dinheiro</option>
                    <!--<option value="4">Parcelado (Promissória)</option>-->
                    <option value="2">Cartão de débito</option>
                    <option value="6">Cartão de crédito</option>
                    <option value="3">Cheque</option>
                  </select>
                </td>
                <td class="aparecer">
                  <label>Valor de entrada</label>
                  <input tupe="text" id="entrada" name="entrada" class="form-control" placeholder="Ex: 150" onKeyUp="calParcela('entrada', this);" readonly="">
                </td>
              </tr>
              <tr>
                <td class="aparecer" colspan="2">
                  <label for="parcelas">Quantidade de parcelas</label>
                  <select id="parcelas" name="parcelas" class="form-control" onChange="calParcela('parcela', this);">
                    <option value="0">-- sem parcelas --</option>
                    <?php 

                    for($i = 1; $i <= 20; $i++)
                    {
                      echo "<option value=\"$i\">".$i."x</option>";
                    }

                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <label for="descricao">Descrição da conta</label>
                  <input type="text" class="form-control" name="descricao" id="descricao">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <span id="resultado" class="aparecer"></span>
                </tr>
                <tr>
                  <td align="right">
                    <button class="btn btn-primary" id="btCadastrar">
                      <span class="glyphicon glyphicon-ok"></span> Cadastrar
                    </button>
                  </td>
                  <td>
                    <button class="btn btn-danger" id="fechar">
                      <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </form>

  <div class="panel panel-default" id="conteudo_central">
    <div class="panel-heading"><span class="  glyphicon glyphicon-share-alt"></span> <b><?php echo $LANG['accounts_payable']['clinic_accounts_payable']?></b></div>
    <div class="panel-body">


      <?php
      if(verifica_nivel('contas_pagar', 'I')) {
        ?>
        
        <button class="btn btn-success" id="novaConta"><b>Cadastrar nova conta</b></button>

        <?php
      }
      ?>


      <div id="pesquisa"></div>
      <script>
        Ajax('contaspagar/pesquisa', 'pesquisa', 'pesquisa=');
      </script>
    </div>
  </div>
