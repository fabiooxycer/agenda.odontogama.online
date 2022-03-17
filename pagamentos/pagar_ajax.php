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

			encontrarDebito();

		});

		$("button#btn_imprimir").click(function(){

			var id = $(this).attr("cid");

			window.open("comprovantes/recibo.php?id="+id, id, "width=806, height=349");

		});

		$("button#btn_pagar").click(function(){

			var codigo = $(this).attr("tid");
			var modo = $(this).attr("modo");

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
				'<tr>'+
				'<td>'+
				'Forma de pagamento:<br>'+
				'<select class="form-control" id="formaPagamento">'+
				'<option value="1">Dinheiro</option>'+
				'<option value="2">Cartão de débito</option>'+
				'<option value="6">Cartão de crédito</option>'+
				'<option value="3">Cheque</option>'+
				'<option value="4">Promissória</option>'+
				'</select>'+
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
					Ajax('contasreceber/atualiza', 'conta_atualiza', 'codigo='+codigo+'&datapagamento='+$("#dataPagamento").val()+'&formaPagamento='+$("#formaPagamento").val());

					swal({
						type: 'success',
						title: 'Sucesso!',
						text: 'Baixa efetuada com sucesso!'
					}).then(function(){

						swal({

							title: 'Recibo de pagamento',
							type: 'question',
							text: 'Deseja imprimir o comprovante de pagamento agora?',
							showCloseButton: true,
							showCancelButton: true,
							confirmButtonText: 'Imprimir',
							cancelButtonText: 'Cancelar'

						}).then(function(retorno){

							if(retorno === true)
							{
								window.open("comprovantes/recibo.php?id="+codigo, codigo, "width=806, height=349");
							}

						});

					});

					encontrarDebito();
				}

			});

			$("#formaPagamento").val(modo);

		});
		
	});


	function encontrarDebito()
	{
		$(".busca").html("Por favor, aguarde...");

		var valores = $("#frmPesquisa").serialize();
		$.get("pagamentos/dadosparcela_ajax.php", valores).done(function(dados){

			if(dados.dados == null)
			{
				$(".busca").html("Nenhum débito encontrado para o código informado.");
				$("div.todos").hide();
				$(".busca").show();
			}else{

				$(".r_cod").text(dados.barras);
				$(".r_vencimento").text(dados.vencimento);
				$(".r_detalhes").text(dados.dados.descricao);
				$(".r_valor").text("R$ "+dados.dados.valor);
				$(".r_ordem").text(dados.dados.ordem);
				$(".r_paciente").text(dados.nome_paciente);
				$(".r_dentista").text(dados.nome_dentista);

				if(dados.dados.status == 1)
				{
					$("button#btn_imprimir").attr("cid", dados.dados.codigo);
					$("#pendente").hide();
					$("#pago").show();
					$("#pago strong").text("Débito quitado em "+dados.pagamento);
					$("#btn_pagar").hide();
					$("#btn_imprimir").show();
				}else{
					$("button#btn_pagar").attr("tid", dados.dados.codigo);
					$("#pago").hide();
					$("#pendente").show();
					$("#pendente strong").text("Débito em aberto");
					$("#btn_pagar").show();
					$("#btn_imprimir").hide();
				}

				$(".busca").hide();
				$("div.todos").show();


			}

		});
	}

</script>

<div class="panel panel-default">
	<div class="panel-body">
		<h4>Pagamento de débitos</h4>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><b>Formulário de pagamentos</b></div>
	<div class="panel-body">

		<form action="javascript:;" id="frmPesquisa">
			<table class="table" id="sem">
				<tr>
					<td>
						<label for="dentista">Código de barras</label><br>
						<input class="form-control" name="barras">
					</td>
					
					<td>
						<label style="color: #fff;">.</label><br>
						<button class="btn btn-success" id="pesquisar"><span class="glyphicon glyphicon-search"></span> Encontrar</button>
					</td>
				</tr>

			</table>
		</form>

	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><b>Resultado de débitos</b></div>
	<div class="panel-body">
		<div id="resultado">
			<span class="busca">Resultado de débitos</span>
			<div class="todos" style="display: none;">
				
				<div class="alert alert-success" id="pago" style="display: none;">
					<strong></strong>
				</div>

				<div class="alert alert-danger" id="pendente" style="display: none;">
					<strong></strong>
				</div>

				<table class="table table-bordered">
					<thead>
						<th colspan="2">
							Resultado para a busca
						</th>
					</thead>
					<tbody>
						<tr>
							<td><b>Código</b></td>
							<td><span class="r_cod"></span></td>
						</tr>
						<tr>
							<td><b>Vencimento</b></td>
							<td><span class="r_vencimento"></span></td>
						</tr>
						<tr>
							<td><b>Detalhes</b></td>
							<td><span class="r_detalhes"></span></td>
						</tr>
						<tr>
							<td><b>Valor</b></td>
							<td><span class="r_valor"></span></td>
						</tr>
						<tr>
							<td><b>Paciente</b></td>
							<td><span class="r_paciente"></span></td>
						</tr>
						<tr>
							<td><b>Dentista</b></td>
							<td><span class="r_dentista"></span></td>
						</tr>
						<tr>
							<td><b>ORDEM</b></td>
							<td><span class="r_ordem"></span></td>
						</tr>
					</tbody>
				</table>
				<br>
				<button class="btn btn-primary" id="btn_pagar">Realizar pagamento</button>
				<button class="btn btn-success" id="btn_imprimir" cid="">Comprovante de pagamento</button>

			</div>
		</div>
	</div>
</div>