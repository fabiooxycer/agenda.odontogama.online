<?php

include "lib/config.inc.php";
include "lib/func.inc.php";
include "lib/classes.inc.php";
require_once 'lang/'.$idioma.'.php';

header("Content-type: application/json");

$sistema = new sistema(); 
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

$sms = new sms();

if($sms->verificarDia() === true)
{
	$resultado["status"] = "ja_enviado";
	echo json_encode($resultado);
	exit;
}

$resultado = array();

/* OBTER ANIVERSÁRIOS */

$paciente = new TPacientes();
$aniversarios = $paciente->LoadAniversarios();

for($i = 0; $i < count($aniversarios); $i++)
{
	$sms->disparar($aniversarios[$i]["celular"], $aniversarios[$i]["nome"], "ani"); // dispara o sms para o cliente;
}

/* OBTER VENCIMENTOS DO DIA */

$contas = new TContas("clinica");
$dadosContas = $contas->getVencimentos();

for($i = 0; $i < count($dadosContas); $i++)
{
	$sms->disparar($dadosContas[$i]["celular"], $dadosContas[$i]["nome"], "cob", $dadosContas[$i]["datavencimento"], $dadosContas[$i]["valor"]);
}

/* OBTER CONSULTAS DO DIA */

$agenda = new TAgendas();
$dadosAgenda = $agenda->getConsultasHoje();

for($i = 0; $i < count($dadosAgenda); $i++)
{
	$sms->disparar($dadosAgenda[$i]["celular"], $dadosAgenda[$i]["nome"], "con", $dadosAgenda[$i]["data"], "", $dadosAgenda[$i]["hora"]);
}

/**************************/
$resultado["aniversarios"] = count($aniversarios);
$resultado["contas"] = count($dadosContas);
$resultado["consultas"] = count($dadosAgenda);

echo json_encode($resultado);
?>
