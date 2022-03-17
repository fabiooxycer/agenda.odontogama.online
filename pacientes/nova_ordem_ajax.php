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

if($_POST[tipo] == "pac")
{

	$paciente = new TPacientes();

	$dados = $paciente->buscarPaciente($_POST[texto]);

	for($i = 0; $i < count($dados); $i++)
	{
		echo '<table class="table table-hover" style="cursor: pointer;" onClick="selecionarPaciente('.$dados[$i][codigo].', \''.strtoupper(utf8_encode($dados[$i][nome])).'\');">
		<tr>
		<td width="78%">'.strtoupper(utf8_encode($dados[$i][nome])).'</td>
		<td width="30%">'.$dados[$i][cpf].'</td>
		</tr>
		</table>';
	}

	exit;
}

if($_POST[tipo] == "den")
{

	$paciente = new TDentistas();

	$dados = $paciente->buscarDentista($_POST[texto]);

	for($i = 0; $i < count($dados); $i++)
	{
		echo '<table class="table table-hover" style="cursor: pointer;" onClick="selecionarDentista('.$dados[$i][codigo].', \''.strtoupper(utf8_encode($dados[$i][nome])).'\');">
		<tr>
		<td width="78%">'.strtoupper(utf8_encode($dados[$i][nome])).'</td>
		<td width="30%">'.$dados[$i][conselho_tipo].'/'.$dados[$i][conselho_estado].' '.$dados[$i][conselho_numero].'</td>
		</tr>
		</table>';
	}

	exit;
}

if(isset($_GET[id])) {

	$os = new os();

	$dados = $os->carregar($_GET[id]);

	if(count($dados) < 1)
	{
		echo "Ordem de serviço não encontrada!";
		exit;
	}

}

if(isset($_POST['idOs']))
{
	$os = new os();

	$idPaciente = $_POST[idPaciente];
	$idDentista = $_POST[idDentista];
	$total = $_POST[total];
	$id = $_POST[idOs];

	$valor = $_POST[valor];
	$tag = $_POST[tag];
	$procedimento = $_POST[procedimento];


	if($os->atualizar($idPaciente, $idDentista, $total, $id) == true)
	{
		for($i = 0; $i < count($valor); $i++)
		{
			$os->atualizarProcedimentos($id, $valor[$i], $tag[$i], $procedimento[$i]);
		}

		echo $id;
	}

	exit;

}

if(isset($_POST[idPaciente]) AND !isset($_POST['idOs']))
{
	$os = new os();

	$idPaciente = $_POST[idPaciente];
	$idDentista = $_POST[idDentista];
	$total = $_POST[total];

	$valor = $_POST[valor];
	$tag = $_POST[tag];
	$procedimento = $_POST[procedimento];
	$status = $_POST[status];

	$idOrdem = $os->salvar($idPaciente, $idDentista, $total, $status);

	for($i = 0; $i < count($valor); $i++)
	{
		$os->salvarProcedimentos($idOrdem, $valor[$i], $tag[$i], $procedimento[$i]);
	}

	if($idOrdem != "")
	{
		echo $idOrdem;
	}

	exit;
}

if(isset($_POST["pesqProc"]))
{

	$os = new os();

	$texto = $_POST["texto"];

	$id = $_POST["id"];

	$procedimentos = $os->getProcedimentos($texto);

	echo "<table class=\"table table-hover\" style=\"cursor: pointer;\">";

	for($i = 0; $i < count($procedimentos); $i++)
	{
		echo ' 
		<tr onClick="adicionar('.$id.', \''.$procedimentos[$i][codigo].'\', \''.utf8_encode($procedimentos[$i][procedimento]).'\', \''.$procedimentos[$i][valor].'\');">
		<td width="10%">'.$procedimentos[$i][codigo].'</td>
		<td width="80%">'.utf8_encode($procedimentos[$i][procedimento]).'</td>
		<td width="10%">R$ '.$procedimentos[$i][valor].'</td>
		</tr>';
	}

	echo '</table>';

	exit;

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

	
	background: #fff;
	height: auto;
	position: absolute;
	border: 1px solid #a6d2ff;
	margin-top: -1px;
}

.table > tbody + tbody {

	border-top: 2px solid transparent;

}

</style>

<script type="text/javascript">

var valores = new Array();
var desconto = 0.0;


<?php



if(count($dados) < 1)
{

	?>

	var id_proc = 1;
	var total = 0.0;
	var idPaciente = '';
	var idDentista = '';


	<?php

}else{

	?>

	var id_proc = <?php echo count($dados[0][procedimentos])+1; ?>;
	var total = <?php echo $dados[0][total]; ?>;
	var idPaciente = <?php echo $dados[0][paciente]; ?>;
	var idDentista = <?php echo $dados[0][dentista]; ?>;

	<?php
}
?>

function calcular(obj)
{
	var valor = $(obj).val();
	var id = $(obj).attr("id");
	id = id.split("_");
	id = id[1];

	valores[id] = valor.replace(",", ".");;

}

function calcularTotal()
{
	total = 0.0;

	for(i = 0; i < valores.length; i++)
	{
		total+=parseFloat(valores[i]);
	}

	total-= parseFloat(desconto);

	$("#valorTotal").text("R$ "+total);
	$("#oValor").val(total);
}

function selecionarPaciente(id, nome)
{
	$("#paciente").val(nome);
	idPaciente = id;
	$("#idPaciente").val(id);
	$("#pesqPaciente").html("");
}

function selecionarDentista(id, nome)
{
	$("#dentista").val(nome);
	idDentista = id;
	$("#idDentista").val(id);
	$("#pesqDentista").html("");
}

function adicionar(id, codigo, descricao, valor) {

	$("#valor_"+id).val(valor);
	$("#proc_"+id).val(descricao);
	$("#tag_"+id).val(codigo);

	total+=eval(valor);

	$("#valorTotal").text("R$ "+total);

	$("#oValor").val(total);

	calcular($("#valor_"+id));
	calcularTotal();

	ocultar(id);

}

function ocultar(id) {

	$("#pesq_"+id).html("");

}

function buscar(elemento) {

	var texto = elemento.val();

	var id = elemento.attr("id");
	id = id.split("_");

	if(texto == "") return $("#pesq_"+id[1]).html("");

	$("#pesq_"+id[1]).html("Carregando...");

	$.post("pacientes/nova_ordem_ajax.php", {'pesqProc': '', 'texto': texto, 'id': id[1]}).done(function(dados){
		$("#pesq_"+id[1]).html(dados);

		if(dados == "") $("#pesq_"+id[1]).html("Nenhum resultado encontrado.");
	});

}

function remover(id)
{
	$("#proced_"+id).remove();
	valores[id] = 0.0;
	calcularTotal();
}

$(function(){

	/*$("#confirmar").change(function(){


		alert($(this).prop("checked"));

	});*/

$("#desconto").mask("0000000");

$("#paciente").keyup(function(){

	var pesquisa = $(this).val();

	if(pesquisa == "") return $("#pesqPaciente").html("");

	$("#pesqPaciente").html("Carregando...");

	$.post("pacientes/nova_ordem_ajax.php", {tipo: 'pac', 'texto': pesquisa}).done(function(dados){

		$("#pesqPaciente").html(dados);

		if(dados == "") $("#pesqPaciente").html("Nenhum resultado...");

	});

});

$("#dentista").keyup(function(){

	var pesquisa = $(this).val();

	if(pesquisa == "") return $("#pesqDentista").html("");

	$("#pesqDentista").html("Carregando...");

	$.post("pacientes/nova_ordem_ajax.php", {tipo: 'den', 'texto': pesquisa}).done(function(dados){

		$("#pesqDentista").html(dados);
		if(dados == "") $("#pesqDentista").html("Nenhum resultado...");

	});

});

$("#desconto").change(function(){

	//if(total == 0.0) return false;
	//total = eval(total-$(this).val());

	//if(isNaN(total)) total = 0.0;

	desconto = $(this).val();
	calcularTotal();

	//$("#valorTotal").text("R$ "+total);

	//$("#oValor").val(total);

});

$("#incluir").click(function(){

	$("#proc").append('<tr id="proced_'+id_proc+'"><!--<td width="10%"><label for="paciente">TAG</label><input type="text" class="form-control" id="tag_'+id_proc+'" name="tag[]" readonly></td>--><td width="75%"><label for="dentista">* Procedimento</label><input type="text" class="form-control" name="procedimento[]" id="proc_'+id_proc+'" onKeyUp="buscar($(this));" autocomplete="off"><div class="pesqProcedimento" id="pesq_'+id_proc+'"></div></td><td width="10%"><label for="dentista">* Valor</label><input type="text" class="form-control" id="valor_'+id_proc+'" name="valor[]" placeholder="Ex: 150,00" onKeyUp="calcular(this);" onChange="calcularTotal();"></td><td width="5%"><br><button id="bt_remove" onclick="remover('+id_proc+');" class="btn btn-danger glyphicon glyphicon-trash"></button></td></tr>');
	id_proc++;
});

$("#salvar").click(function(){

	if(idPaciente == "") {
		swal({
			type: 'info',
			title: 'Atenção!',
			text: 'Antes de continuar você deve selecionar um paciente.'
		});
		return false;
	}

	if(idDentista == "") {
		swal({
			type: 'info',
			title: 'Atenção!',
			text: 'Antes de continuar você deve selecionar um dentista.'
		});
		return false;
	}

	if($("#proc_0").val() == "")
	{
		swal({
			type: 'info',
			title: 'Atenção!',
			text: 'Antes de continuar você deve informar no mínimo um procedimento.'
		});
		return false;
	}

	$.post("pacientes/nova_ordem_ajax.php", $("#formOS").serialize(), {'idPaciente': idPaciente, 'idDentista': idDentista, 'total': total}).done(function(dados){

		if(dados != "")
		{
			swal({
				type: 'success',
				title: 'Sucesso!',
				text: 'Ordem de serviço número: \'#'+dados+'\' salva com sucesso!',
			});

			Ajax('pacientes/ordem_servico','conteudo','')
		}else{
			swal({
				type: 'error',
				title: 'Erro!',
				text: 'Ocorreu um erro ao tentar salvar a ordem de serviço!',
			});
		}

	});



});

});

</script>

<div class="panel panel-default">
	<div class="panel-body">
		<table class="table">
			<tr>
				<td>
					<h4>Cadastro de O.S</h4>
				</td>
				<td align="right">
					<h4>#Nº <?php echo $dados[0][id]; ?></h4>
				</td>
			</tr>
		</table>
	</div>
</div>

<form action="javascript:;" id="formOS">

	<input type="hidden" name="idPaciente" id="idPaciente" value="<?php echo $dados[0][paciente]; ?>">
	<input type="hidden" name="idDentista" id="idDentista" value="<?php echo $dados[0][dentista]; ?>">
	<input type="hidden" name="total" id="oValor" value="<?php echo $dados[0][total]; ?>">

	<div class="panel panel-default">
		<div class="panel-heading"><span class="glyphicon glyphicon-plus"></span> Dados pessoais ( Paciente / Dentista )</div>
		<div class="panel-body">
			<table class="table">
				<tr>
					<td>
						<label for="paciente">* Paciente</label>
						<input type="text" class="form-control" name="paciente" autocomplete="off" id="paciente" <?php echo "value='".strtoupper($dados[0][nomePaciente])."'"; ?>>
						<div class="pesqProcedimento" style="width:519px;" id="pesqPaciente"></div>
					</td>
					<td>
						<label for="dentista">* Dentista</label>
						<input type="text" class="form-control" autocomplete="off" id="dentista" <?php echo "value='".strtoupper($dados[0][nomeDentista])."'"; ?>>
						<div class="pesqProcedimento" name="dentista" style="width:519px;" id="pesqDentista"></div>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><span class="glyphicon glyphicon-plus"></span> Procedimentos</div>
		<div class="panel-body">
			<table class="table" id="proc">
				<tr>
					<td>
						<button class="btn btn-danger" id="incluir" title="Incluir novo procedimento à ordem de serviço"><span class="glyphicon glyphicon-plus"></span> Incluir procedimento</button>
					</td>
				</tr>

				<?php

				if(count($dados[0][procedimentos]) > 0)
				{
					echo "<input type='hidden' name='idOs' value='".$_GET[id]."'>";

					for($i = 0; $i < count($dados[0][procedimentos]); $i++)
					{

						echo'
						<tr>
						<!--<td width="10%">
						<label for="paciente">TAG</label>
						<input type="text" class="form-control" id="tag_'.$i.'" name="tag[]" readonly value="'.$dados[0][procedimentos][$i][tag].'">
						</td>-->
						<td width="75%">
						<label for="dentista">* Procedimento</label>
						<input type="text" class="form-control" name="procedimento[]" id="proc_'.$i.'" onKeyUp="buscar($(this));" autocomplete="off" value="'.$dados[0][procedimentos][$i][procedimento].'">
						<div class="pesqProcedimento" id="pesq_'.$i.'"></div>
						</td>
						<td width="10%">
						<label for="dentista">* Valor</label>
						<input type="text" class="form-control" id="valor_'.$i.'" name="valor[]" value="'.$dados[0][procedimentos][$i][valor].'" placeholder="Ex: 150,00" onKeyUp="calcular(this);" onChange="calcularTotal();">
						</td>
						<td width="5%">
						<button id="bt_remove" onclick="remover('.$i.');" class="btn btn-danger glyphicon glyphicon-trash"></button>
						</td>
						</tr>';

					}

				}else{

					?>

					<tr>
						<!--<td width="10%">
							<label for="paciente">TAG</label>
							<input type="text" class="form-control" id="tag_0" name="tag[]" readonly>
						</td>-->
						<td width="75%">
							<label for="dentista">* Procedimento</label>
							<input type="text" class="form-control" name="procedimento[]" id="proc_0" autocomplete="off" onKeyUp="buscar($(this));">
							<div class="pesqProcedimento" id="pesq_0"></div>
						</td>
						<td width="10%">
							<label for="dentista">* Valor</label>
							<input type="text" class="form-control" id="valor_0" name="valor[]" placeholder="Ex: 150,00" onKeyUp="calcular(this);" onchange="calcularTotal();">
						</td>
						<td width="5%"></td>
					</tr>

					<?php
				}
				?>

			</table>
			<table class="table">
				<tr>
					<td width="18%">
						<label for="desconto">
							<b>Desconto (R$)</b>
						</label><br>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" id="desconto" aria-label="Amount (to the nearest dollar)">
							<span class="input-group-addon">.00</span>
						</div>
					</td>
					<td width="85%">
						<!--<br>
						<input type="checkbox" id="bostinha" name="status" <?php if($dados[0][status] == 1) echo "checked disabled"; ?>>
						<label for="bostinha">OS aprovada pelo cliente</label>-->
					</td>
					<td width="5%"></td>
				</tr>
				<tr>
					<td>
						<label><b>Total da ordem:</b></label><br>
						<?php

						if($dados[0][total] == "") echo "<label id=\"valorTotal\">R$ 0,00</label>";
						if($dados[0][total] != "") echo "<label id=\"valorTotal\">R$ ".$dados[0][total]."</label>";

						?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>

<div class="panel panel-default">
	<div class="panel-body" id="resultado">
		<button class="btn btn-primary" id="salvar">Salvar</button>
		<button class="btn btn-warning" id="cancelar">Cancelar</button>
	</div>
</div>
