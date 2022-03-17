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
if(!verifica_nivel('pacientes', 'L')) {
	echo $LANG['general']['you_tried_to_access_a_restricted_area'];
	die();
}

if(isset($_POST[tipo]))
{
	if($_POST[tipo] == "confirmar")
	{
		$os = new os();

		if($os->autorizarPagamento($_POST[id]) == true)
		{
			echo "sucesso";
		}
	}

	exit;
}

?>

<style type="text/css">

	#txtOrdem, #txtPaciente, #txtDentista{
		display: none
	}

</style>

<script type="text/javascript">

	function pagar(id, valor, dentista) {

		swal({
			title: "Atenção!",
			text: "Você confirma o pagamento de R$ "+valor.replace(".", ",")+" para o dentista "+dentista+" referente a esta comissão?",
			type: "question",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Confirmar",
			cancelButtonText: "Cancelar",
			closeOnConfirm: false
		}).then(function(resul){

			if(resul == true) {

				$.post("dentistas/comissao_ajax.php", {'tipo': 'confirmar', 'id': id}).done(function(dados) {

					if(dados == "sucesso")
					{
						swal({
							title: "Sucesso!",
							text: "Pagamento de comissão confirmado!",
							type: "success",
							closeOnConfirm: true
						}).then(function(){

							swal({
								title: "Comprovante de pagamento!",
								text: "Deseja imprimir o comprovante de pagamento agora?",
								type: "question",
								showCancelButton: true,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Imprimir",
								cancelButtonText: "Cancelar",
								closeOnConfirm: false
							}).then(function(retorno){

								if(retorno === true)
								{
									window.open("comprovantes/comissao.php?id="+id, id, "width=306, height=349");
								}

							});

						});

						var filtro = $("#filtro").val();
						var dataI = $("#dataInicial").val();
						var dataF = $("#dataFinal").val();

						if(filtro == "ordem") texto = $("#txtOrdem").val();
						if(filtro == "paciente") texto = $("#txtPaciente").val();
						if(filtro == "dentista") texto = $("#txtDentista").val();
						if(filtro == "") texto = "";

						pesquisar(filtro, texto, dataI, dataF);

					}else{
						swal({
							title: "Ops!",
							text: "Não foi possível confirmar o pagamento!",
							type: "error",
							closeOnConfirm: true
						});
					}



				});

			}

		});

	}

	function pesquisar(filtro, texto, dataI, dataF)
	{
		$("#resultado").html("Carregando...");

		$.post("dentistas/comissao_pesquisa.php", {'filtro': filtro, 'texto': texto, 'dataI': dataI, 'dataF': dataF}).done(function(dados){

			if(dados == "") return $("#resultado").html("Nenhum resultado encontrado!");

			$("#resultado").html(dados);

		});
	}

	$(function(){

		pesquisar('', '', '', '');

		$("#txtOrdem, #txtPaciente, #txtDentista").keyup(function(){

			var texto = $(this).val();
			var filtro = $("#filtro").val();
			var dataI = $("#dataInicial").val();
			var dataF = $("#dataFinal").val();

			pesquisar(filtro, texto, dataI, dataF);

		});

		$("#filtro").change(function(){

			var filtro = $(this).val();

			$("#txtOrdem, #txtPaciente, #txtDentista").slideUp(300);

			if(filtro == "ordem") $("#txtOrdem").slideDown(300);
			if(filtro == "paciente") $("#txtPaciente").slideDown(300);
			if(filtro == "dentista") $("#txtDentista").slideDown(300);

		});

		$("input#dataInicial, input#dataFinal").datepicker({

			dateFormat: 'dd/mm/yy',
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
			dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
			onSelect: function(date){ 

				var filtro = $("#filtro").val();
				var dataI = $("#dataInicial").val();
				var dataF = $("#dataFinal").val();

				if(filtro == "ordem") texto = $("#txtOrdem").val();
				if(filtro == "paciente") texto = $("#txtPaciente").val();
				if(filtro == "dentista") texto = $("#txtDentista").val();
				if(filtro == "") texto = "";

				pesquisar(filtro, texto, dataI, dataF);

			}
		});

	});

</script>

<div class="panel panel-default">
	<div class="panel-body">
		<table class="table">
			<tr>
				<td>
					<h4>Comissão dos dentistas</h4>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
	<div class="panel-body">
		<table class="table">
			<tr>
				<td>
					<select class="form-control" id="filtro">
						<option value=""> -- Pesquisar por --</option>
						<option value="paciente">Nome do paciente</option>
						<option value="dentista">Nome do dentista</option>
						<option value="ordem">Número da OS</option>
					</select>
				</td>
				<td id="numeroOrdem">
					<input type="text" class="form-control" id="txtOrdem" placeholder="Número da ordem de serviço">
					<input type="text" class="form-control" id="txtPaciente" placeholder="Informe o nome do paciente">
					<input type="text" class="form-control" id="txtDentista" placeholder="Informe o nome do dentista">
				</td>
				<td>
					<input type="text" class="form-control" id="dataInicial" placeholder="Data inicial">
				</td>
				<td>
					<input type="text" class="form-control" id="dataFinal" placeholder="Data final">
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">Resultado das pesquisas</div>
	<div class="panel-body" id="resultado">
		<span></span>
	</div>
</div>