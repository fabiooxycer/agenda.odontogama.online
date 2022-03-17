<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";

header("Content-type: application/json", true);

if(!checklog()) {
  die($frase_log);
}

$barras = $_GET["barras"];

if($barras == "")
{
  exit;
}

$conta = new TContas('clinica', 'receber');

$paciente = new TPacientes();
$dentista = new TDentistas();

$conta->LoadConta($barras);

$retorno = array();

$retorno["dados"] = $conta->RetornaTodosDados();

$paciente->LoadPaciente($retorno["dados"]["paciente"]);
$dentista->LoadDentista($retorno["dados"]["dentista"]);

$retorno["vencimento"] = date("d/m/Y", strtotime($retorno["dados"]["datavencimento"]));
$retorno["pagamento"] = date("d/m/Y", strtotime($retorno["dados"]["datapagamento"]));

$retorno["nome_paciente"] = mb_convert_case($paciente->RetornaDados("nome"), MB_CASE_UPPER, "UTF-8");
$retorno["nome_dentista"] = mb_convert_case($dentista->RetornaDados("nome"), MB_CASE_UPPER, "UTF-8");
$retorno["barras"] = $barras;

echo json_encode($retorno);

?>