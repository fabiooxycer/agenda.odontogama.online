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

?>

<style type="text/css">

	#txtOrdem, #txtPaciente, #txtDentista{
		display: none
	}

</style>

<script type="text/javascript">

	$(function(){

		$("button#recibo").click(function(){

			var id = $(this).attr("cid");

			window.open("comprovantes/comissao.php?id="+id, id, "width=806, height=349");

		});

	});

</script>

<?php

$os = new OS();
$moeda = new moeda();

$filtro = $_POST[filtro];
$texto = $_POST[texto];
$dataI = $_POST[dataI];
$dataF = $_POST[dataF];

if($dataI == "") $dataI = "2000-01-02";
if($dataF == "") $dataF = "2999-01-01";

$novaDataI = new DateTime(str_replace("/", "-", $dataI));
$novaDataF = new DateTime(str_replace("/", "-", $dataF));

$dataI = $novaDataI->format("Y-m-d");
$dataF = $novaDataF->format("Y-m-d");

$pacientes = new TPacientes();
$dentistas = new TDentistas();

$dados = $os->carregarComissao($filtro, $texto, $dataI, $dataF);

if(count($dados) > 0)
{


	?>

	<table class="table table-hover">
		<thead>
			<!--<th>#ID</th>-->
			<th>Data</th>
			<th>Número da O.S</th>
			<th>Dentista</th>
			<th>Paciente</th>
			<th>Valor</th>
			<th>Comissão</th>
			<th>Status</th>
			<th>Ação</th>
		</thead>

		<tbody>

			<?php

			for($i = 0; $i < count($dados); $i++)
			{

				$dadosPaciente = $pacientes->loadPaciente($dados[$i][id_paciente]);
				$dadosDentista = $dentistas->loadDentista($dados[$i][id_dentista]);

				echo "<tr>";
				//echo "<td>".$dados[$i][id]."</td>";
				echo "<td>".date("d/m/Y", strtotime($dados[$i][data]))."</td>";
				echo "<td><a href=\"javascript: Ajax('pacientes/nova_ordem', 'conteudo', 'id=".$dados[$i][id_ordem]."');\">".$dados[$i][id_ordem]."</a></td>";
				echo "<td>".mb_convert_case(utf8_encode($dentistas->retornaDados('nome')), MB_CASE_UPPER, "UTF-8")."</td>";
				echo "<td>".mb_convert_case(utf8_encode($pacientes->retornaDados('nome')), MB_CASE_UPPER, "UTF-8")."</td>";
				echo "<td>R$ ".$moeda->formatar(round($dados[$i][valor], 2))."</td>";
				echo "<td>".$dentistas->retornaDados('comissao')."%</td>";
				echo "<td>".$os->statusComissao($dados[$i][status])."</td>";

				if($dados[$i][status] == 0) {

					echo "<td><button class='btn btn-primary' onClick=\"pagar(".$dados[$i][id].", '".round($dados[$i][valor], 2)."', '".mb_convert_case(utf8_encode($dentistas->retornaDados('nome')), MB_CASE_UPPER, "UTF-8")."');\" title='Autorizar pagamento'><span class='glyphicon glyphicon-ok-sign'></span> Pagar</button></td>";

				}else if($dados[$i][status] == 1){

					echo "<td><button class=\"btn btn-success\" cid=\"".$dados[$i]['id']."\" id=\"recibo\"><span class=\"glyphicon glyphicon-print\"></span> <b>Imprimir recibo</b></button></td>";

				}
			}

			?>

		</tbody>
	</table>

	<?php
}else{
	?>

	<span>Nenhum resultado encontrado!</span>

	<?php
}
?>

