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

<html>
 


  <head>
  	
    <style type="text/css">


  	#corpo{
  		border: 1px solid silver;
	    width: 640px;
	    height: 710px;
	    padding: 25px;
      text-align: justify;
	    margin: 0 auto;
	    margin-top: 15px;
  	}

  	.campo{
      line-height: 0;
  		border-style: solid;
  		border-width: 0px 0px 1px 0px;
  		border-color: #000;
  		outline: none;
  	}

  	#texto{
  		font-size: 10pt;
      line-height: 28px;
  	}

  	#nomeDoc{
  		text-align: center;
  		padding: 10px;
  		margin-top: -60px;
  		background: #333;
  		float: right;
  		width: 270px;
  		font-size: 13pt;
  		margin-right: -26px;
  		color: white;
  	}

  	</style>



  </head>
  
  <body>

    <div class="panel panel-default">
      <div class="panel-body">
        <?php 
        $ativo_mais = true;
        include('submenu.php'); ?>
      </div>
    </div>

    <form action="relatorios/atestado_1.php" method="GET" target="_blank">

    <div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b>Atestado médico</b> </div>
    <div class="panel-body">

  	<div id="corpo" >
  		<br>
  	<span style="font-size:15pt;"><b><?php echo $titulo." ".$nomeDentista; ?></b></span><br>
    <span style="font-size: 11pt;"><?php echo utf8_encode($espec[0]); ?> - <?php echo $cro; ?></span><br>
    <span style="font-size: 11pt;"><?php echo utf8_encode($espec[1]); if($espec[2] != "") echo" e ".utf8_encode($espec[2]); ?></span>
  	<div id="nomeDoc">ATESTADO MÉDICO</div>
  </br></br></br>


    <span id="texto">Paciente: <b><input type="text" id="Pac" name="paciente" class="campo" value="<?php echo strtoupper($inPaciente->retornaDados("nome"));?>" style="width:524px;"/></b>
    </span></br></br>
    <span id="texto">Declaro para fins de <b><input type="text" name="fins" id="dec" class="campo" style="width:455px;"/></br></b>
    	que o paciente acima esteve em consulta ondontológica no dia  <input type="text" name="dt_consulta" id="dat" class="campo" style="width:126px;"/>.</br>
    	O paciente deverá em consequência de tratamento  odontológico, consulta ou realização de exames ficar afastado de suas atividades:</br>
      Período das <input type="text" id="dat" name="periodo_inicio" class="campo" style="width:126px;"/> ás  <input type="text" name="periodo_fim" id="dat" class="campo" style="width:126px;"/>. </br>
      Apenas no horário da consulta das <input type="text" id="dat" name="consulta_inicio" class="campo" style="width:57px;"/> Hs ás  <input type="clock" name="consulta_final" id="dat" class="campo" style="width:57px;"/> Hs.</br>
      Apenas no dia de hoje</br>

      Por<input type="text" id="dat" name="por" class="campo" style="width:110px;"/> (<input type="text" name="por_ext" id="sla" class="campo" style="width:65px;"/>) 
       dias de <input type="text" name="dias_de" id="dat" class="campo" style="width:126px;"/> a <input type="text" name="dias_a" id="dat" class="campo" style="width:126px;"/></br>
       Acompanhar paciente em tratamento Ondontológico.</br></br>
       CID<input type="text" id="dec" name="CID" class="campo" style="width:200px;"/></br>
       Autorizo a divulgação do CID
      </br><br>
       Data:<input type="text" id="dat" name="data" class="campo" style="width:126px;"/></br><br>
       <?php echo $clinica->Fantasia; ?></br>
      </span style="font size: 10pt">
      <?php echo $enderClinica; ?> - E-mail: <?php echo $inDentista->retornaDados("email"); ?>

      <span>
      </span>
    </div>
  </div>

  <center><button class="btn btn-primary">Imprimir Atestado</button></center><br><br>
</div>

</form>
 

  </body>








</html>