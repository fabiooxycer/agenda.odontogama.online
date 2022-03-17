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

<div class="panel panel-default">
    <div class="panel-body">
      <?php 
      $ativo_mais = true;
      include('submenu.php'); ?>
    </div>
  </div>

  <form action="relatorios/receituario.php" method="GET" target="_blank">

  <div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b>Receituário</b> </div>
    <div class="panel-body">
      <div style="margin:0 auto;width:640px;border:1px solid silver;height:700px;padding: 25px;">
        
        <span style="font-size: 12pt;"><b><?php echo $titulo." ".$nomeDentista; ?></b></span><br>
        <span style="font-size: 11pt;"><?php echo utf8_encode($espec[0]); ?> - <?php echo $cro; ?></span><br>
        <span style="font-size: 11pt;"><?php echo utf8_encode($espec[1]); if($espec[2] != "") echo" e ".utf8_encode($espec[2]); ?></span><br><br><br>

        <textarea class='form-control' id="receita" name="receita" style="height:473px;border:0;"></textarea>

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
        <button class="btn btn-primary">Imprimir Receituário</button>
      </center>
    </div>
  </div>
</form>

  
  
