<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);

if(!checklog()) {
	die($frase_log);
}

$fornecedor = new TFornecedores();
$dados_fornecedor = $fornecedor->ListFornecedores();

?>

<script type="text/javascript">

	$(function(){

		$("#fluxo").change(function(){

			var valor = $(this).val();

			if(valor == "-" || valor == "AP")
			{
				$("#aparecer").show();
			}else{
				$("#aparecer").hide();
			}

		});

		$("#pesquisar").click(function(){

			var valores = $("#frmPesquisa").serialize();
			$.get("relatorios/financeiro_pesquisa.php", valores).done(function(dados){

				$("#resultado").html(dados);

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
	});

</script>

<div class="panel panel-default">
	<div class="panel-body">
		<h4>Relatório financeiro</h4>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><b>Pesquisa</b></div>
	<div class="panel-body">

		<form action="javascript:;" id="frmPesquisa">
			<table class="table" id="sem">
				<tr>
					<td>
						<label for="fluxo">Movimentação</label><br>
						<select class="form-control" id="fluxo" name="fluxo">
							<option value="-">Contas pagas</option>
							<option value="+">Contas recebidas</option>
							<option value="AP">Contas a pagar</option>
							<option value="AR">Contas a receber</option>
							<!--<option value=".">Todos</option>-->
						</select>
					</td>
					<td>
						<label for="fluxo">Forma de pagamento</label><br>
						<select class="form-control" id="forma_pagamento" name="forma_pagamento">
							<option value="1">Dinheiro</option>
							<option value="3">Cheque</option>
							<option value="2">Cartão de débito</option>
							<option value="6">Cartão de crédito</option>
							<option value="4">Promissória</option>
							<option value="5">Boleto</option>
							<option value="T">Todos</option>
						</select>
					</td>
					<td>
						<label for="dataInicial">Data inicial</label><br>
						<input type="text" class="form-control" id="dataInicial" name="dataInicial" placeholder="__/__/____" readonly="true">
					</td>
					<td>
						<label for="dataFinal">Data final</label><br>
						<input type="text" class="form-control" id="dataFinal" name="dataFinal" placeholder="__/__/____" readonly="true">
					</td>
					<td>
						<label style="color: #fff;">.</label><br>
						<button class="btn btn-success" id="pesquisar">Pesquisar</button>
					</td>
				</tr>
				<tr>
                <td colspan="2" id="aparecer">
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
			</table>
		</form>

	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><b>Resultado da busca</b></div>
	<div class="panel-body">
		<div id="resultado">
			<span>Resultado das buscas</span>
		</div>
	</div>
</div>