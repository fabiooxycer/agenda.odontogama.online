<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}

  session_start();

	$paciente = new TOrtodontia();
	if(isset($_POST['Salvar'])) {
		$paciente->LoadOrtodontia($_GET['codigo']);
		foreach($_POST as $chave => $valor) {
            if($chave != 'Salvar') {
                $paciente->SetDados($chave, $valor);
            }
		}
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET['codigo'];
	$paciente->LoadOrtodontia($_GET['codigo']);
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET['codigo'], 'nome').' - '.$_GET['codigo'];
	$row = $paciente->RetornaTodosDados();
	$check = array('tratamento');
	foreach($check as $campo) {
		if($row[$campo] == 'Sim') {
			$chk[$campo]['Sim'] = 'checked';
		} else {
			$chk[$campo]['Não'] = 'checked';
		}
	}
	$acao = '&acao=editar';
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();
	}

  $inPaciente = new TPacientes();
  $inPaciente->LoadPaciente($_GET['codigo']);
  $data = date("d/m/Y");

  $inDentista = new TDentistas();
  $inDentista->LoadDentista($_SESSION[ID]);

  $titulo = $inDentista->retornaDados("titulo");
  $nomeDentista = $inDentista->retornaDados("nome");
  $cro = $inDentista->retornaDados("conselho_tipo")."/".$inDentista->retornaDados("conselho_estado")." ".$inDentista->retornaDados("conselho_numero");

  /* Carrega a especialidade do dentista */
  $especialidades = new TEspecialidades();
  $lista = $especialidades->ListEspecialidades();

  $espec = array();

  for($i=0; $i < count($lista); $i++){
    if($inDentista->retornaDados("codigo_areaatuacao1") == $lista[$i][codigo]) $espec[0] = $lista[$i][descricao];
    if($inDentista->retornaDados("codigo_areaatuacao2") == $lista[$i][codigo]) $espec[1] = $lista[$i][descricao];
    if($inDentista->retornaDados("codigo_areaatuacao3") == $lista[$i][codigo]) $espec[2] = $lista[$i][descricao];
  }

      /* obtem informações da clinica */
  $clinica = new TClinica();
  $clinica->LoadInfo();

  $enderClinica = $clinica->Endereco.", ".$clinica->Bairro." - CEP ".$clinica->Cep." - ".$clinica->Cidade." - ".$clinica->Estado."<br> Telefone: ".$clinica->Telefone1;
 

?>

 <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Gerenciador odontol&oacute;gico</title>


<script language="javascript" type="text/javascript" src="../lib/script.js.php"></script>
<script language="javascript" type="text/javascript" src="../lib/ajax_search.js"></script>
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="../css/responsivo.css">
<link href="../css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/jquery-ui.css" />


<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/mask.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/nav.js"></script>

<script type="text/javascript" src="../js/mascara.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>

<script type="text/javascript">
window.print();
</script>
<br>
  <form action="relatorios/receituario.php" method="GET" target="_blank">
      <div style="margin:0 auto;width:640px;border:1px solid silver;height:700px;padding: 25px;">
        
        <span style="font-size: 12pt;"><b><?php echo $titulo." ".$nomeDentista; ?></b></span><br>
        <span style="font-size: 11pt;"><?php echo utf8_encode($espec[0]); ?> - <?php echo $cro; ?></span><br>
        <span style="font-size: 11pt;"><?php echo utf8_encode($espec[1]); if($espec[2] != "") echo" e ".utf8_encode($espec[2]); ?></span><br><br><br>

        <textarea class='form-control' id="receita" name="receita" readonly=true style="background:#fff;height:473px;border:0;"><?php echo $_GET['receita'];?></textarea>

        <center>
          <span style="font-size: 11pt;"><b><?php echo $clinica->Fantasia; ?></b></span>
        </center>
        <br>
       <center>
        <span style="font-size: 11pt;">
          <?php echo $enderClinica; ?> - E-mail: <?php echo $inDentista->retornaDados("email"); ?>
        </span>
      </center>
      </div><br><br><br>
      <center>
      </div><br><br><br>
    </div>
</form>

  
  
