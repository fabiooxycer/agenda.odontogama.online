<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';

date_default_timezone_set("Brazil/East");

if(!checklog()) {
	echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
	die();
}

if(!verifica_nivel('pacientes', 'L')) {
	echo $LANG['general']['you_tried_to_access_a_restricted_area'];
	die();
}

$idPaciente = $_POST["idPaciente"];
$idDentista = $_POST["idDentista"];
$procurar = $_POST["procurar"];
$situacao = $_POST["situacao"];
$forma_pagamento = $_POST["forma_pagamento"];
$dataI  = $_POST["dataInicial"];
$dataF  = $_POST["dataFinal"];

if($dataI == "") $dataI = "2000-01-02";
if($dataF == "") $dataF = "2999-01-01";

$novaDataI = new DateTime(str_replace("/", "-", $dataI));
$novaDataF = new DateTime(str_replace("/", "-", $dataF));

$dataI = $novaDataI->format("Y-m-d");
$dataF = $novaDataF->format("Y-m-d");

$os = new os();

$pacientes = new TPacientes();
$dentistas = new TDentistas();

$dados = $os->encontrar($idPaciente, $idDentista, $procurar, $situacao, $forma_pagamento, $dataI, $dataF);

$total = 0.0;

$moeda = new moeda();

if(count($dados) > 0) 
{

	echo"
	<table class='table table-hover'>
		<thead>
			<th></th>
			<th>Número</th>
			<th>Data</th>
			<th>Paciente</th>
			<th>Dentista</th>
			<th>Valor</th>
			<th>Status</th>
			<th>Ação</th>
		</thead>
		<tbody>";

		}else{
			echo "Nenhum resultado encontrado!";
		}

		for($i = 0; $i < count($dados); $i++)
		{
			$dadosPaciente = $pacientes->loadPaciente($dados[$i][paciente]);
			$dadosDentista = $dentistas->loadDentista($dados[$i][dentista]);

			$total+=$dados[$i][total];

			echo "
			<tr>	
				<td style='line-height: 33px;'>".$os->getModoPagamento($dados[$i]["modo_pagamento"])."</td>
				<td style='line-height: 33px;'>".$dados[$i][id]."</td>
				<td style='line-height: 33px;'>".date("d/m/Y", strtotime($dados[$i][data]))."</td>
				<td style='line-height: 33px;'>".mb_convert_case(utf8_encode($pacientes->retornaDados('nome')), MB_CASE_UPPER, "UTF-8")."</td>
				<td style='line-height: 33px;'>".mb_convert_case(utf8_encode($dentistas->retornaDados('nome')), MB_CASE_UPPER, "UTF-8")."</td>
				<td style='line-height: 33px;'>R$ ".$moeda->formatar($dados[$i][total])."</td>
				<td style='line-height: 33px;'>".$os->getStatus($dados[$i][status])."	</td>
				<td>
					<!-- Single button -->
					<div class=\"btn-group\">
						<button type=\"button\" class=\"btn btn-success dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
							Ação <span class=\"caret\"></span>
						</button>
						<ul class=\"dropdown-menu\">
							<li><a href=\"javascript: Ajax('pacientes/nova_ordem', 'conteudo', 'id=".$dados[$i][id]."');\">Visualizar / Editar</a></li>
							";

							if($dados[$i][status] == 0) echo "<li><a href=\"#\" onClick=\"alterarStatus(1, ".$dados[$i][id].", '".$dados[$i][total]."');\">Alterar para aprovado</a></li>";
							if($dados[$i][status] == 2 OR $dados[$i][status] == 3)
							{
								echo "<li><a href=\"#\" onClick=\"visualizar(".$dados[$i][id].", '".$dados[$i][total]."');\">Visualizar Parcelas</a></li>";
								echo "<li><a href=\"carne.php?os=".$dados[$i]["id"]."\" target=\"_blank\">Imprimir carnê</a></li>";
							}
    						//if($dados[$i][status] == 1) echo "<li><a href=\"#\" onClick=\"alterarStatus(0, ".$dados[$i][id].");\">Alterar para não aprovado</a></li>";

							echo"
							<li><a href=\"relatorios/ordem.php?id=".$dados[$i]['id']."\" target='_blank'>Imprimir O.S</a></li>
							<li class=\"divider\"></li>
							<li><a href=\"#\" onClick='excluir(".$dados[$i][id].");'>Excluir</a></li>
						</ul>
					</div>
				</td>
			</tr>";
		}

		?>

		<table class="table">
			<thead>
				<th>Quantidade de O.S:<br><?php echo count($dados); ?></th>
				<th>Valor total (R$):<br><?php echo $moeda->formatar($total); ?></th>
			</thead>
		</table>


