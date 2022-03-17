<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);

if(!checklog()) {
	die($frase_log);
}

$dentistas = new TDentistas();
$dadosDentistas = $dentistas->ListDentistas();

?>

<script type="text/javascript">

	$(function(){

		$("#fluxo").change(function(){

			var valor = $(this).val();

			if(valor == "-")
			{
				$("#aparecer").show();
			}else{
				$("#aparecer").hide();
			}

		});

		$("#pesquisar").click(function(){

			var valores = $("#frmPesquisa").serialize();
			$.get("relatorios/dentistas_pesquisa.php", valores).done(function(dados){

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
		<h4>Relatório de ganhos por dentistas</h4>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><b>Pesquisa</b></div>
	<div class="panel-body">

		<form action="javascript:;" id="frmPesquisa">
			<table class="table" id="sem">
				<tr>
					<td>
						<label for="dentista">Dentista</label><br>
						<select class="form-control" id="dentista" name="dentista">
							<option value="T">TODOS</option>
							<?php

							for($i = 0; $i < count($dadosDentistas); $i++)
							{
								echo "<option value='".$dadosDentistas[$i]["codigo"]."'>".mb_convert_case($dadosDentistas[$i]["nome"], MB_CASE_UPPER, "UTF-8")."</option>\n";
							}

							?>
						</select>
					</td>
					<td>
					<!--
						<label for="tipo">Tipo</label><br>
						<select name="tipo" id="tipo" class="form-control">
							<option value="0">A pagar</option>
							<option value="1">Já pagas</option>
						</select>
					<td>--> 
						<label for="dataInicial">Data inicial</label><br>
						<input type="text" class="form-control" id="dataInicial" name="dataInicial" placeholder="__/__/____" readonly="true">
					</td>
					<td>
						<label for="dataFinal">Data final</label><br>
						<input type="text" class="form-control" id="dataFinal" name="dataFinal" placeholder="__/__/____" readonly="true">
					</td>
					<td>
						<label style="color: #fff;">.</label><br>
						<button class="btn btn-success" id="pesquisar"><span class="glyphicon glyphicon-search"></span> Pesquisar</button>
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