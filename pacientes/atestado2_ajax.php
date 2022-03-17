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

    .campo{
      /*line-height: 25px;*/
  		border-style: solid;
  		border-width: 0px 0px 1px 0px;
  		border-color: #000;
  		outline: none;
  	}


  	#at{
  		
  		border: 1px solid silver;
	    width: 640px;
	    height: 700px;
	    padding: 25px;
	    margin: 0 auto;
	    margin-top: 15px;
      text-align: justify;
  	}

  	#ates{
      line-height: 33px;
      text-align: center;
      margin-top: 25px;
  	}
  		#texto{
  		font-size: 11pt;
  	}
  	#es{
  		font-size: 11pt;
  		margin-left: 103px;
  	}
  	#ult{
  	    margin-left: 140px;
        width: 234px;
        margin-top: 15px;
        color: white;

  	}
  	#ult2{
  		font-size: 9pt;
        margin-left: 283px;
  		

  	}
  	#ult1{
  		color: white;
        width: 209px;
  	}
  	#ultt{
  		font-size: 9pt;
        margin-left: 31px;
  		
  	}

    #cc{
      /*line-height: 30px;*/
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

      <form action="relatorios/atestado_2.php" method="GET" target="_blank">
      <div class="panel panel-default">
      <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b>Atestado médico</b> </div>
      <div class="panel-body">
    	
      
    	<div id="at">

        <br>
    	<span style="font-size:13pt;"><b><?php echo $titulo." ".$nomeDentista; ?></b></span><br>
      <span style="font-size: 11pt;"><?php echo utf8_encode($espec[0]); ?> - <?php echo $cro; ?><br>
      <?php echo utf8_encode($espec[1]); if($espec[2] != "") echo" e ".utf8_encode($espec[2]); ?>
      </span><br>
      
       <div id="ates"> <span style="font-size:15pt;" id="ates"><b>ATESTADO</b></span>
         </br></br>
       </div>
       <div id="cc" style="line-height: 27px;">
       	<span id="texto">Atestado para os devidos fins que </span> <b><input type="text" name="paciente" id="dec" value="<?php echo strtoupper($inPaciente->retornaDados("nome"));?>" class="campo" style="width:340px;"/>
       	<input type="text" id="dec" class="campo" style="width:586px;"/></b></br>
       	<span id="texto">R.G. N° <b><input type="text" name="rg" value="<?php echo $inPaciente->retornaDados('rg'); ?>" id="" class="campo" style="width:318px;"/></b>, <!-- mask de RG -->
       	residente e domiciliado (a) à</br>
       	<b><input type="text" name="endereco" value="<?php echo utf8_encode($inPaciente->retornaDados('endereco')).' - '.utf8_encode($inPaciente->retornaDados('bairro')).' - '.utf8_encode($inPaciente->retornaDados('cidade')).' - '.utf8_encode($inPaciente->retornaDados('estado')); ?>" class="campo" style="width:586px;"/></br></b>
       	Esteve sob tratamento odontológico neste consultório, no período </br>
       	das <b><input type="text" name="periodo_inicio" class="campo" style="width:219px;"/></b>às <b><input type="text" name="periodo_fim" class="campo" style="width:219px;"/></b>
       	horas do dia: <b><input type="date" name="data_consulta" class="campo" style="width:146px;"/></b>, 
       	necessita afastar-se de suas atividades por <b><input type="number" name="dias_afastado" class="campo" style="width:71px;"/></b> dia(s).</br></br>
        </span>
        <span id="texto">Goiânia, <b><input type="date" name="data" class="campo" style="width:147px;"/></b></span><span id="es">	C.I.D. 
        	<b><input type="text" name="cid" class="campo" style="width:190px;"/></b>
        	</span>

       
        	<b><input type="text" id="ult1" class="campo" style="width;88px;"/> <input type="text" id="ult" class="campo" style="width;180px;"/></b></br>
        	<span  id="ultt" > Assinatura do paciente</span> <span id="ult2">Assinatura </span>

          <div style="line-height:20px;margin-top: 70px;">
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
          </div>

        </div>
        	


      </div>

    </div>
    <center><button class="btn btn-primary">Imprimir Atestado</button></center><br><br>
  </div>
 </form> 

    </body>

</html>
