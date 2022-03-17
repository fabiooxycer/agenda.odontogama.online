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

if(isset($_POST[pesqProc]))
{

	$os = new os();

	$texto = $_POST[texto];

	$id = $_POST[id];

	$procedimentos = $os->getProcedimentos($texto);

	for($i = 0; $i < count($procedimentos); $i++)
	{
		echo '<table class="table table-hover" style="cursor: pointer;" onClick="adicionar('.$id.', \''.$procedimentos[$i][codigo].'\', \''.utf8_encode($procedimentos[$i][procedimento]).'\', \''.$procedimentos[$i][valor].'\');">
		<tr>
			<td width="10%">'.$procedimentos[$i][codigo].'</td>
			<td width="80%">'.utf8_encode($procedimentos[$i][procedimento]).'</td>
			<td width="10%">R$ '.$procedimentos[$i][valor].'</td>
		</tr>
	</table>';
}

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

	var id_proc = 1;
	var total = 0.0;
	var idPaciente = '';
	var idDentista = '';

	

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
	

	$(function(){

		$("#paciente").keyup(function(){

			var pesquisa = $(this).val();

			$.post("pacientes/nova_ordem_ajax.php", {tipo: 'pac', 'texto': pesquisa}).done(function(dados){

				$("#pesqPaciente").html(dados);

			});

		});

		$("#dentista").keyup(function(){

			var pesquisa = $(this).val();

			$.post("pacientes/nova_ordem_ajax.php", {tipo: 'den', 'texto': pesquisa}).done(function(dados){

				$("#pesqDentista").html(dados);

			});

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

			window.open("imprimir/ordem.php?paciente="+$("#paciente").val()+"&dentista="+$("#dentista").val(), "");

		});

	});

</script>

<div class="panel panel-default">
	<div class="panel-body">
		<table class="table">
			<tr>
				<td>
					<h4>Impressão de O.S</h4>
				</td>
			</tr>
		</table>
	</div>
</div>


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
	<div class="panel-body" id="resultado">
		<button class="btn btn-primary" id="salvar">Imprimir</button>
		<button class="btn btn-warning" id="cancelar">Cancelar</button>
	</div>
</div>
