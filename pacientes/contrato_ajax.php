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

  $enderClinica = $clinica->Endereco.", ".$clinica->Bairro." - ".$clinica->Cidade." - ".$clinica->Estado;

?>

<html>

    <head>

      <style type="text/css">


  	#corpo{
  		border: 1px none silver;
	    width: 722px;
	    /*height: 700px;*/
	    padding: 14px;
	    margin: 0 auto;
	    margin-top: 15px;
  	}

    .campos{
        width: 123px;
        border: 2px solid black;
        border-width: 0px 0px 1px 0px;
        outline: none;

    }

    #con{
      font-size: 24px;
      font-family: arial;
      text-align: center;
    }

    #tex{
      font-size: 15px;
      font-family: arial;
    }

    .ult{
      width: 273px;
      border: 2px solid black;
      border-width: 0px 0px 1px 0px;
      outline: none;

    }

    .ult1{
      width: 273px;
      border: 2px solid black;
      border-width: 0px 0px 1px 0px;
      outline: none;
      margin-top: 52px;
      margin-left: 45px;
      margin-right: 41px;
    }

    .ult2{
     
       width: 273px;
       border: 2px solid black;
       border-width: 0px 0px 1px 0px;
       outline: none;

    }

    #ult3{
      margin-left: 45px;
      font-size: 10pt;
      font-family: arial;
      text-align: center;

    }

    #ult4{
      margin-left: 77px;
      font-size: 10pt;
      font-family: arial;
      text-align: center;
      

    }
    .ult5{
      width: 273px;
      border: 2px solid black;
      border-width: 0px 0px 1px 0px;
      outline: none;
      margin-left: 45px;
      margin-right: 41px;
      margin-top: 38px;
    }

    #ult6{
      margin-left: 137px;
      font-size: 10pt;
      font-family: arial;
      text-align: center;

    }

    #ult7{
      margin-left: 238px;
      font-size: 10pt;
      font-family: arial;
      text-align: center;

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

      <form action="relatorios/contrato.php" method="GET" target="_blank">
     <div class="panel panel-default">
      <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b>Contrato ortodontia e implantes</b> </div>
      <div class="panel-body">

       <div id="corpo">

        
    
    	<div id="con"><span id="texto"><b>CONTRATO DE PRESTAÇÃO DE SERVIÇOS ODONTOLÓGICOS</b></span></div>
    	</br>
    	<span id="tex">Pelo presente instrumento particular de contrato de prestação de serviços odontológicos, os contratantes, de um lado
    	<b> <input type="text" id="" name="destista" value="<?php echo utf8_encode($inDentista->retornaDados('nome')); ?>" class="campos" style="width:607px;"/> </b> </span>
      <span ii="text">, RG</span><b><input type="text" name="rg" value=" <?php echo $inDentista->retornaDados('rg'); ?>" id="" class="campos" style="width:263px"/></b>
      <span id="tex">CRO-UF <b> <input type="text" id="" value="<?php echo $cro; ?>" name="cro" class="campos" style="width:215px;"/> </b> , com consultório à </span><b><input name="endereco" type="text" id="" value="<?php echo $enderClinica; ?>" class="campos" style="width:671px;"/></b></br> 
      <b><input type="text" id="" class="campos" style="width:616px" name="endereco1"/></b><span id="tex">, </span>
      <span id="tex">doravante denominado simplesmente Cirurgião-Dentista e, do outro lado </span>
      <b><input type="text" id="" name="paciente" value="<?php echo $inPaciente->retornaDados('nome'); ?>" class="campos" style="width:283px;"/></b></br>
      <b><input type="text" id="" class="campos" name="paciente_1" style="width:369px"/></b><span id="tex">,</span>
      <span id="tex">RG </span><b><input type="text" id="" name="rg_paciente" value="<?php echo $inPaciente->retornaDados('rg'); ?>" class="campos" style="width:124px"/></b><span id="tex"><span id="tex">,CPF </span>
      <b><input type="text" id="" name="cpf" class="campos" value="<?php echo $inPaciente->retornaDados('cpf'); ?>" style="width:124px"/></b>
      <span id="tex">, residente à </span><b><input name="endereco_paciente" type="text" id="" value="<?php echo utf8_encode($inPaciente->retornaDados('endereco')).' - '.utf8_encode($inPaciente->retornaDados('bairro')).' - '.utf8_encode($inPaciente->retornaDados('cidade')).' - '.utf8_encode($inPaciente->retornaDados('estado')); ?>" class="campos" style="width:600px"/></b>
      <span id="tex">, doravante denominado </span>
      <span id="tex">simplesmente de paciente ou responsável pelo paciente </span><b><input name="nome_paciente" type="text" id="" value="<?php echo utf8_encode($inDentista->retornaDados('nome')); ?>" class="campos" style="width:172px"/></b></br>
      <b><input type="text" id="" class="campos" name="nome_paciente1" style="width:689px;"/></b><span id="tex">,</span></br>
      <span id="tex">têm entre si justo e contratado, na melhor forma do direito as seguintes condições:</span><br><br>
      <span id="tex"><b>Cláusula Primeira –</b> Do Objetivo O objetivo do presente contrato constitui-se na prestação de serviços 
               odontológicos, pelo Cirurgião-Dentista ao paciente, no endereço do seu consultório acima grafado ou em outro local 
               indicado pelo profissional desde que previamente notificado o paciente, de acordo com o plano de tratamento aprovado
               e constante do prontuário odontológico do paciente, que passa a fazer parte deste contrato como anexo seu.</br>
            <b>Cláusula Segunda –</b> Do Valor e Do Pagamento dos Honorários O valor  total dos honorários profissionais, relativos aos serviços 
              odontológicos prestados é de R$ </span>
              <b><input type="text" id="" name="valor" class="campos" style="width:162px;"/></b><span id="tex">( </span> 
              <b><input type="text" id="" name="valor_ext1" class="campos" style="width:149px;"/></b>
              <b><input type="text" id="" name="valor_ext2" class="campos" style="width:149px;"/></b><span id="tex">) </span>
             <span id="tex"> e seu pagamento deverá ser realizado nas datas indicadas no orçamento apresentado e aprovado que passa 
               a fazer parte deste contrato como anexo seu.</br>
              <b>§ 1° –</b> O valor dos honorários, ora estipulado, poderá sofrer alteração, 
                caso seja necessário modificar o plano de tratamento inicialmente aprovado, em face da constatação de questões técnicas 
                ou outras intercorrências que inviabilizem sua execução, sendo necessário que as partes acordem, formalmente, os novos 
                valores ajustados;</br>
               <b>§ 2° –</b>  Os pagamentos vencidos e efetuados fora dos prazos previstos, estarão sujeitos a atualização 
                monetária e a multa de mora de 2% (dois por cento) e juros de 1% (um por cento) ao mês.</br>
                <b>Cláusula Terceira –</b> Das Garantias 
                O paciente foi devidamente informado sobre propósitos, riscos e alternativas de tratamento, bem como que a Odontologia não 
                é uma ciência exata e que os resultados esperados, a partir do diagnóstico, poderão não se concretizar em face da resposta 
                biológica do paciente e da própria limitação da ciência.</br> 
             <b>Cláusula Quarta –</b> Das Obrigações do Cirurgião-Dentista O
                Cirurgião-Dentista se compromete a utilizar as técnicas e os materiais adequados à execução do plano de tratamento aprovado, 
                assumir a responsabilidade pelos serviços prestados, resguardar a privacidade do paciente e o necessário sigilo, bem como
                zelar pela sua saúde e dignidade.</br> 
              <b>Cláusula Quinta –</b>
                Das Obrigações do Paciente ou seu Responsável O paciente ou seu responsável se compromete a seguir rigorosamente as orientações 
                do Cirurgião-Dentista, 
                comunicando imediatamente qualquer alteração em decorrência do tratamento realizado, comparecer pontualmente as consultas marcadas, 
               justificando as faltas com antecedência mínima de
              <b><input type="text" id="" name="horas" class="campos" style="width:72px;"/></b> 
               horas.</br> 
              <b>Parágrafo Único –</b> As faltas não justificadas, conforme preceitua a cláusula quinta, serão cobradas no valor correspondente 
                 a uma consulta;</br> 
              <b>Cláusula Sexta –</b> O presente contrato tem duração pelo período necessário para realização do tratamento, conforme 
                informado no plano de tratamento aprovado, desde que o paciente compareça às consultas previamente agendadas.</br>
              <b>Cláusula Sétima –</b> Da Rescisão Este contrato poderá ser rescindido a qualquer tempo, por qualquer das partes, sendo neste caso cobrados 
                os valores relativos aos trabalhos efetivamente, realizados, mesmo que não totalmente concluídos.</br>  
              <b>§ 1° -</b> Será caracterizado o abandono do tratamento quando o paciente faltar a três consultas consecutivas, ou se ausentar,
                  sem justificativa do consultório, por mais de quarenta e cinco dias, sendo neste caso considerado o contrato rescindido por iniciativa do paciente;</br>
              <b>§ 2° -</b> o paciente desde já se declara ciente de que o abandono do tratamento poderá acarretar prejuízos à sua saúde, inclusive com
                  agravamento do estado inicial, não sendo necessário a rechamada do paciente para que o abandono fique caracterizado.</br>
              <b>Cláusula Oitava -</b> Para dirimir quaisquer dúvidas sobre o presente contrato fica eleito o foro da Cidade de
                <b><input type="text" id="" value="Goiânia - GO" name="foro" class="campos" style="width:273px;"/></b> 
                , com exclusão de qualquer outro por mais privilegiado que seja. E por estarem de acordo com as condições acima descritas, assinam o presente contrato,
                em duas vias de igual teor, na presença de duas testemunhas, para que produza todos os efeitos legais.</br></br></br>
            </span>
            <span id="tex"><b>Local e data.</b></span></br>
            <input type="text" id="" class="ult1" style="width:273px;"/> <input type="text" id="" class="ult2" style="width:273px;"/></br>
            <span id="ult3"><b>Assinatura do Paciente ou seu Responsável</b></span> <span id="ult4"><b>Assinatura do Cirurgião-Dentista</b></span></br>
            <input type="text" id="" class="ult5" style="width:273px;"/> <input type="text" id="" class="ult" style="width:273px;"/></br>
            <span id="ult6"><b>Testemunha 1</b></span><span id="ult7"><b>Testemunha 2</b></span></br>






        </div>
      </div>

      <center><button type="submit" class="btn btn-primary">Imprimir contrato</button></center><br><br>
    </div>
  </form>

    </body>
</html>