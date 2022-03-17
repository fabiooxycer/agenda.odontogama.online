<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
/*if(!checklog()) {
	echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
	die();
}

if(!verifica_nivel('pacientes', 'L')) {
	echo $LANG['general']['you_tried_to_access_a_restricted_area'];
	die();
}*/

if(isset($_POST["tipo"])) { // Listas as parcelas da O.S selecionada.

	$os = new os();

	if($_POST["tipo"] == "parcelas")
	{

		$dados = $os->getParcelas($_POST["id"]);

		$moeda = new moeda();

		if(count($dados) == 0)
		{
			echo "Nenhuma parcela localizada!";
			exit;
		}

		echo "<table class='table' style='font-size: 11pt; text-align: left;'>
		<thead>
			<th>Vencimento</th>
			<th>Valor</th>
			<th>Status</th>
		</thead>
		<tbody>
			";


			for($i = 0; $i < count($dados); $i++)
			{
				echo "<tr>
				<td>".date("d/m/Y", strtotime($dados[$i]["data"]))."</td>
				<td> R$ ".$moeda->formatar($dados[$i]["valor"])."</td>
				<td>".$os->statusParcela($dados[$i]["status"]);

				$hoje = date("Y-m-d");

				if($hoje > $dados[$i]["data"])
				{
					if($dados[$i]["status"] == 0) echo " (Vencida)";
				}

			echo"
			</td>
			</tr>";
		}

		echo "</tbody>
	</table>";

	exit;

}

}

if(isset($_POST['ordem'])) {

	$os = new os();


	if(isset($_POST['status'])) { // alteração de status

		$tipo_pagamento = $_POST["tipo"];
		$total = $_POST["total"];
		$vlEntrada = $_POST["vlEntrada"];
		$qtParcela = $_POST["qtParcela"];
		$ordem = $_POST["ordem"];
		$comissao = $_POST["comissao"];
		$txtVencimento = $_POST["vencimento"];
		$tipo_entrada = $_POST["tipo_entrada"];

		/* DETERMINA O TIPO DE PAGAMENTO SELECIONADO */
		/*if($tipo == "vista")  $tipo_pagamento = 1;
		if($tipo == "cartao") $tipo_pagamento = 2;
		if($tipo == "cheque") $tipo_pagamento = 3;
		if($tipo == "parcelado") $tipo_pagamento = 4;*/

		//

		if($_POST['status'] == "addCheque") // adicionar parcelas de cheques.
		{
			$datas = $_POST['data'];

			if($tipo_entrada == "") $tipo_entrada = 5;

			if($qtParcela < 2)
			{
				$vlParcelas = ($total-$vlEntrada);
			}else{
				$vlParcelas = round(($total-$vlEntrada)/$qtParcela, 2);
			}

			for($i = 0; $i < count($datas); $i++)
			{
				$data = date("Y-m-d", strtotime(str_replace("/", "-", $datas[$i])));
				$os->addParcelas($data, $vlParcelas, $ordem, $i, $qtParcela, $comissao, $tipo_pagamento);
			}

			if($comissao == "s") $os->calcularComissao($ordem, "entrada", $vlEntrada);
			if($os->alterarStatus($ordem, 3, $vlEntrada, "entrada", $tipo_entrada) == true) echo "sucesso";
			exit;
		}

		if($_POST['status'] == "calcular") // calcula as parecelas do cheque e permite alteração das datas.
		{

			$moeda = new moeda();

			echo "<form action='javascript:;' id='frmVencimentos'>";

			echo "<input type='hidden' name='tipo' value='$tipo_pagamento'>";
			echo "<input type='hidden' name='total' value='$total'>";
			echo "<input type='hidden' name='vlEntrada' value='$vlEntrada'>";
			echo "<input type='hidden' name='qtParcela' value='$qtParcela'>";
			echo "<input type='hidden' name='ordem' value='$ordem'>";
			echo "<input type='hidden' name='tipo_entrada' value='$tipo_entrada'>";
			echo "<input type='hidden' name='status' value='addCheque'>";


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

			if($qtParcela < 2)
			{
				$vlParcelas = ($total-$vlEntrada);
			}else{
				$vlParcelas = round(($total-$vlEntrada)/$qtParcela, 2);
			}
			
			for($i = 1; $i <= $qtParcela; $i++)
			{
				$data->modify("+1 month");
				$dataParcela = $data->format("d/m/Y");

				if($i%2 == 1) echo "<tr>"; // verifica se é impar

				echo "<td>".$i."/".$qtParcela."</td>";
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

		if($tipo == "parcelado") // adiciona parcelas de 30 dias automaticamente para a categoria a prazo.
		{

			$hoje = date("Y-m-d");

			$data = new DateTime($hoje);

			if($qtParcela < 2)
			{
				$vlParcelas = ($total-$vlEntrada);
			}else{
				$vlParcelas = round(($total-$vlEntrada)/$qtParcela, 2);
			}
			
			for($i = 0; $i < $qtParcela; $i++)
			{
				$data->modify("+1 month");
				$dataParcela = $data->format("Y-m-d");

				$os->addParcelas($dataParcela, $vlParcelas, $ordem, $i, $qtParcela, $comissao, $tipo_pagamento);
			}

			if($os->alterarStatus($_POST['ordem'], 2, $vlEntrada, "entrada", $tipo_entrada) == true) echo "sucesso";
			if($comissao == "s") $os->calcularComissao($_POST['ordem'], "entrada", $vlEntrada);
/*
echo $data;*/
}else{

	$comissao = $_POST["comissao"];
	//echo $txtVencimento;
	if($txtVencimento != "")
	{
		$txtVencimento = date("Y-m-d", strtotime(str_replace("/", "-", $txtVencimento)));
	}else{
		$txtVencimento = date("Y-m-d");
	}
	//echo $txtVencimento;
	//exit;
	if($tipo_pagamento != "1")
	{
		$os->addParcelas($txtVencimento, $total, $ordem, 0, 1, $comissao, $tipo_pagamento);
		if($os->alterarStatus($_POST['ordem'], 4, $total, "aguardando", $tipo_pagamento) == true) echo "sucesso";
	}else{
		//$os->addParcelas($txtVencimento, $total, $ordem, 0, 1, $comissao, $tipo_pagamento);
		if($os->alterarStatus($_POST['ordem'], 1, $total, "pagamento", $tipo_pagamento) == true) echo "sucesso";
		if($comissao == "s") $os->calcularComissao($_POST['ordem'], "total", "");
	}
	
	//if($comissao == "s") $os->calcularComissao($_POST['ordem'], "total", "");

}



	}else{ // exclusão de ordem de serviço

		if($os->excluir($_POST['ordem']) == true) echo "sucesso";

	}

	exit;

}

?>

<style type="text/css">

	#txtOrdem, #txtPaciente, #txtDentista{
		display: none
	}

</style>

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

	#pesqPaciente, #pesqDentista{
		display: block;
		padding: 0;
	}

	.aparecer{
		/*display: none;*/
	}

</style>

<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script src="js/mask.js" type="text/javascript"></script>



<script type="text/javascript">

	var vlEntrada = "0,00";
	var qtParcela = 0;
	var resultado = 0;
	var total = 0;
	var tipo = "1";
	var vencimento = "";

	var idPaciente = "";
	var idDentista = "";

	function selecionar(obj)
	{
		tipo = $(obj).val();

		if(tipo == "6") // cartão de crédito
		{
			$("tr#aparecerVencimento").show();
		}else{
			$("tr#aparecerVencimento").hide();
		}

		/*if(tipo == "4" || tipo == "3") // parcelado
		{
			$("tr#aparecerPrazo").show();
		}else{
			$("tr#aparecerPrazo").hide();
		}

		if(tipo == "3") // cheque
		{
			$("tr#aparecerCheque").show();
		}else{
			$("tr#aparecerCheque").hide();
		}*/
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

	function visualizar(id, valor) {
		swal({
			title: 'Histórico O.S #'+id+' (R$ '+valor+')',
			type: '',
			html: '<div id="resul_parcelas">Carregando</div>',
			
			showCancelButton: false,
			showCloseButton: true,
			confirmButtonText:
			'Fechar'
		});

		$.post("pacientes/ordem_servico_ajax.php", {'tipo': 'parcelas', 'id': id}).done(function(dados){

			$("#resul_parcelas").html(dados);

		});
	}

	function entrada(obj)
	{
		var tipo = $(obj).attr("tipo");
		var valor = $(obj).val();

		//	alert(valor);

		if(valor == "")
		{
			if(tipo == "cheque")
			{
				$("#vl_entradaCheque").attr("readonly", "true");
			}else{
				$("#vl_entrada").attr("readonly", "true");
			}
		}else{

			if(tipo == "cheque")
			{
				$("#vl_entradaCheque").removeAttr("readonly");
			}else{
				$("#vl_entrada").removeAttr("readonly");
			}
			
		}
	}

	function alterarStatus(novo, id, valor) {

		vlEntrada = 0;
		qtParcela = 0;
		resultado = 0;
		var comissao = "n";

		if(novo == 1) {

			total = valor;

			swal({
				title: 'Confirmar pagamento (R$ '+valor+')',
				type: '',
				html:
				'<table class="table" style="text-align: left; font-size: 10pt;">'+
				'<tr>'+
				'<td>Tipo de pagamento<br>'+
				'<select id="tipoPagamento" class="form-control" onChange="selecionar(this);">'+
				'<option value="1">Dinheiro</option>'+
				'<option value="4">Promissória</option>'+
				'<option value="2">Cartão de débito</option>'+
				'<option value="6">Cartão de crédito</option>'+
				'<option value="3">Cheque</option>'+
				'</select>'+
				'</td>'+
				'</tr>'+
				'<tr id="aparecerVencimento" style="display: none;">'+
				'<td>'+
				'<span>Data de vencimento</span><br>'+
				'<input type="text" id="txtVencimento" class="form-control" placeholder="__/__/____" onKeyUp="vencimento=$(this).val();">'+
				'</td>'+
				'</tr>'+
				
				'<tr id="aparecerPrazo" style="/*display: none;*/">'+
				'<td><span>Forma de entrada:</span>'+
				'<select id="pagamento_entrada" tipo="prazo" class="form-control" onChange="entrada(this);">'+
				'<option value="">Sem entrada</option>'+
				'<option value="1">Dinheiro</option>'+
				'<!--<option value="4">Parcelado (Promissória)</option>-->'+
				'<option value="2">Cartão de débito</option>'+
				'<option value="6">Cartão de crédito</option>'+
				'<option value="3">Cheque</option>'+
				'</select>'+
				'</td>'+
				'<td><span>Entrada (R$)</span><br>'+
				'<input type="text" class="form-control" id="vl_entrada" onFocusout="calParcela(\'entrada\', this);" placeholder="Ex: 120 (Sem pontos ou virgulas)" readonly>'+
				'</td>'+
				'<td>Parcelas<br>'+
				'<select id="qtParcelas" class="form-control" onChange="calParcela(\'parcela\', this);">'+
				<?php

				echo "'<option value=\"0\">0</option>'+";

				for($i = 1; $i <= 20; $i++)
				{
					echo "'<option value=\"$i\">$i</option>'+";
				}

				?>

				'</select>'+
				'</td>'+
				'<tr id="aparecerPrazo" style="display: none;">'+
				'<td>Resultado: <br>'+
				'<span id="resultado">R$ 0,00</span>'+
				'</tr>'+
				'</table>',
				showCloseButton: true,
				showCancelButton: true,
				confirmButtonText:
				'<i class="	glyphicon glyphicon-ok"></i> Confirmar',
				cancelButtonText:
				'<i class="glyphicon glyphicon-remove"></i> Cancelar'
			}).then(function(dismiss){

				if(dismiss === true)
				{

					var tipoEntrada = "";
					tipoEntrada = $("#pagamento_entrada").val();

					swal({
						type: 'question',
						title: 'Comissão dos dentistas',
						text: "Deseja gerar comissão para essa ordem de serviço?",

						showCancelButton: true,

						confirmButtonText: "Sim",
						cancelButtonText: "Não"

					}).then(function(resposta){

						if(resposta === true)
						{
							comissao = "s";
						}

						if(qtParcela != "")
						{

							swal({

								type: '',
								title: 'Aguarde...',
								text: 'Gerando parcelas...',

							});

							$.post("pacientes/ordem_servico_ajax.php", {'ordem': id, 'status': 'calcular', 'tipo': tipo, 'total': total, 'vlEntrada': vlEntrada, 'qtParcela': qtParcela, 'comissao': comissao, 'tipo_entrada': tipoEntrada}).done(function(dados){

								swal({
									title: 'Datas de vencimento (O.S #'+id+')',
									type: '',
									html: dados,
									showCloseButton: true,
									showCancelButton: true,
									confirmButtonText:
									'<i class="	glyphicon glyphicon-ok"></i> Confirmar',
									cancelButtonText:
									'<i class="glyphicon glyphicon-remove"></i> Cancelar'

								}).then(function(retorno){

									if(retorno === true)
									{

										
										if(tipo == "4")
										{
											window.open("carne.php?os="+id, id);
										}

										var link = $("#frmVencimentos").serialize();

										$.post("pacientes/ordem_servico_ajax.php", link).done(function(dados){

											if(dados == "sucesso")
											{
												swal({

													title: 'Sucesso!',
													type: 'success',
													text: 'Modo de pagamento adicionado com sucesso!',

													showCancelButton: false,
													showCloseButton: false,
													confirmButtonText: 'Concluir'

												});

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
									}

								});

							});



							return false;
						}
						else if(qtParcela == "0")
						{	
							$.post("pacientes/ordem_servico_ajax.php", {'ordem': id, 'status': 'aprovar', 'tipo': tipo, 'total': total, 'vlEntrada': vlEntrada, 'qtParcela': qtParcela, 'comissao': comissao, 'vencimento': vencimento}).done(function(dados){

								if(dados == "sucesso") {
									swal({
										type: 'success',
										title: 'Sucesso!',
										text: 'Ordem de serviço aprovada com sucesso!'
									});

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

						}
					});
				}

			});

		}
	}

	function excluir(id) {

		swal({
			title: "Atenção!",
			text: "Deseja realmente excluir esta ordem de serviço?",
			type: "question",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Excluir",
			cancelButtonText: "Cancelar",
			closeOnConfirm: false
		}).then(function (resul) {

			if(resul === true) {
				$.post("pacientes/ordem_servico_ajax.php", {'ordem': id}).done(function(dados){

					if(dados == "sucesso") {
						swal({
							type: 'success',
							title: 'Sucesso!',
							text: 'Ordem de serviço apaga com sucesso!'
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
							type: 'error',
							title: 'Ops!',
							text: 'Ocorreu um erro ao tentar apagar a ordem de serviço!'
						});
					}
				});
			}

		});

	}

	function pesquisar(filtro, texto, dataI, dataF)
	{
		$("#resultado").html("Carregando...");

		$.post("pacientes/ordem_pesquisa.php", {'filtro': filtro, 'texto': texto, 'dataI': dataI, 'dataF': dataF}).done(function(dados){

			if(dados == "") return $("#resultado").html("Nenhum resultado encontrado!");

			$("#resultado").html(dados);

		});
	}

	function selecionarPaciente(id, nome)
	{
		$("#campoPaciente").val(nome);
		idPaciente = id;
		$("#idPaciente").val(id);
		$("#pesqPaciente").html("");
	}

	function selecionarDentista(id, nome)
	{
		$("#campoDentista").val(nome);
		idDentista = id;
		$("#idDentista").val(id);
		$("#pesqDentista").html("");
	}

	$(function(){

		pesquisar('', '', '', '');

		$("#avancado").click(function(){

			$("#fundoEscuro, .pesqProcedimento").show();

		});

		$("#txtVencimento").mask("00/00/0000");

		$("#txtOrdem, #txtPaciente, #txtDentista").keyup(function(){

			var texto = $(this).val();
			var filtro = $("#filtro").val();
			var dataI = $("#dataInicial").val();
			var dataF = $("#dataFinal").val();

			pesquisar(filtro, texto, dataI, dataF);

		});

		/*$("#filtro").change(function(){

			var filtro = $(this).val();

			$("#txtOrdem, #txtPaciente, #txtDentista").slideUp(300);

			if(filtro == "ordem") $("#txtOrdem").slideDown(300);
			if(filtro == "paciente") $("#txtPaciente").slideDown(300);
			if(filtro == "dentista") $("#txtDentista").slideDown(300);

		});*/

		$("input#dataInicial, input#dataFinal").datepicker({
			dateFormat: 'dd/mm/yy',
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
			dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
			onSelect: function(date){ 
				/*
				var filtro = $("#filtro").val();
				var dataI = $("#dataInicial").val();
				var dataF = $("#dataFinal").val();

				if(filtro == "ordem") texto = $("#txtOrdem").val();
				if(filtro == "paciente") texto = $("#txtPaciente").val();
				if(filtro == "dentista") texto = $("#txtDentista").val();
				if(filtro == "") texto = "";

				pesquisar(filtro, texto, dataI, dataF);
				*/

			}
		});

		$("button#fechar").click(function(){

			$("#fundoEscuro, .pesqProcedimento, #fEscuro").hide();

		});

		$("#campoPaciente").keyup(function(){

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

		$("#campoDentista").keyup(function(){

			var valor = $(this).val();
			if(valor == "")
			{
				$("#pesqDentista").html("");
				return false;
			}

			var pesquisa = $(this).val();

			$.post("pacientes/nova_ordem_ajax.php", {tipo: 'den', 'texto': pesquisa}).done(function(dados){

				$("#pesqDentista").html(dados);

			});

		});

		$("#procurar").keyup(function(){

			$("#resultado").html("Carregando...");

			var procurar = $(this).val();

			$.post("pacientes/ordem_pesquisa.php", {'procurar': procurar}).done(function(dados){

				if(dados == "") return $("#resultado").html("Nenhum resultado encontrado!");

				$("#resultado").html(dados);

			});

		});

		$("#pesquisar").click(function(){

			//Ajax("contasreceber/pesquisa", "pesquisa", $("#frmPesquisar").serialize());
			$("#fundoEscuro, .pesqProcedimento").hide();
			$("#procurar").val("");

			$("#resultado").html("Carregando...");

			$.post("pacientes/ordem_pesquisa.php", $("#frmPesquisar").serialize()).done(function(dados){

				if(dados == "") return $("#resultado").html("Nenhum resultado encontrado!");

				$("#resultado").html(dados);

			});

		});

	});

</script>

<div class="panel panel-default">
	<div class="panel-body">
		<table class="table">
			<tr>
				<td>
					<h4>Ordens de serviços (OS)</h4>
				</td>
				<td align="right">
					<a href="javascript:Ajax('pacientes/nova_impressao','conteudo','');">
						<button class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Imprimir ordem</button>
					</a>
					<a href="javascript:Ajax('pacientes/nova_ordem','conteudo','');">
						<button class="btn btn-primary"><span class="glyphicon glyphicon-asterisk"></span> Cadastrar ordem</button>
					</a>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
	<div class="panel-body">
		<form action="javascript:;" id="frmPesquisar">

			<input type="hidden" name="idPaciente" id="idPaciente">
			<input type="hidden" name="idDentista" id="idDentista">

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
    		<input name="procurar" placeholder="Pesquisar por número da OS" id="procurar" type="text" class="form-control" size="20" maxlength="40">

    		<div id="fundoEscuro"></div>
    		<div class="pesqProcedimento" style="width: 650px;">

    			<table class="table" id="table">
    				<tr>
    					<td>
    						<label for="situacao">Situação</label>
    						<select id="situacao" name="situacao" class="form-control">
    							<option value="">Selecione</option>
    							<option value="0">Aguardando Aprovação</option>
    							<option value="1">Pagas</option>
    							<!--<option value="2">Paga parcialmente</option>-->
    							<option value="3">Paga parcialmente</option>
    							<option value="4">Aguardando compensação</option>
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
    						<label>Data da OS (Data inicial - Data final)</label>
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
    						<input type="text" id="campoPaciente" name="nome[paciente]" autocomplete="off" class="form-control" placeholder="Nome do paciente">
    						<div class="pesqProcedimento" style="width:519px;" id="pesqPaciente"></div>
    					</td>
    				</tr>
    				<tr>
    					<td colspan="2">
    						<label>Dentista</label>
    						<input type="text" id="campoDentista" name="nome[dentista]" autocomplete="off" class="form-control" placeholder="Nome do dentista">
    						<div class="pesqProcedimento" style="width:519px;" id="pesqDentista"></div>
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

<div class="panel panel-default">
	<div class="panel-heading">Resultado para ordens de serviços</div>
	<div class="panel-body" id="resultado">
		<span></span>
	</div>
</div>
