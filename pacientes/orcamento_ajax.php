<?php

  
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=UTF-8", true);
	if(!checklog()) {
		die($frase_log);
	}

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
  
	$strUpCase = "ALTERAÇÃO";
    $strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome');
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$acao = '&acao=editar';
?>


<div class="panel panel-default">
    <div class="panel-body">
      <?php 
      $ativo_orcamento = true;
      include('submenu.php'); ?>
    </div>
  </div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="  glyphicon glyphicon-user"></span> <b><?php echo $LANG['patients']['budget_of_the_patient']." - ".strtoupper($strLoCase)?></b> </div>
  <div class="panel-body">


  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;">
        <div align="left"><br /><?php echo ((verifica_nivel('pacientes', 'E'))?'<button class="btn btn-success"  onclick="javascript:Ajax(\'pacientes/orcamentofechar\', \'conteudo\', \'codigo='.$_GET[codigo].$acao.'\')" type="button"><span class=" glyphicon glyphicon-plus"></span> '.$LANG['patients']['insert_new_budget'].'</button>':'')?>
          
         
        </div>
         <br />
        <fieldset>
      <table class="table table-hover table-bordered">
        
        <thead>
          <th align="left"><?php echo $LANG['patients']['budget']?></th>
          <th align="left"><?php echo $LANG['patients']['professional']?></th>
          <th align="left"><?php echo $LANG['patients']['date']?></th>
          <th align="center"><?php echo $LANG['patients']['value']?></th>
          <th align="center"><?php echo $LANG['patients']['edit']?></th>
          <th align="center"><?php echo $LANG['patients']['confirmed']?></th>
        </thead>
      <tbody>
<?php
    limpa_orcamentos();
	$i = 0;
	$query = mysqli_query($conn, "SELECT * FROM `orcamento` WHERE `codigo_paciente` = '$_GET[codigo]' ORDER BY `codigo` ASC");
	while($row = mysqli_fetch_array($query)) {
		if($i%2 === 0) {
			$td_class = 'td_even';
		} else {
			$td_class = 'td_odd';
		}
		$dentista = new TDentistas();
		$lista = $dentista->LoadDentista($row[codigo_dentista]);
		$nome = explode(' ', $dentista->RetornaDados('nome'));
		$nome = utf8_encode($nome[0].' '.$nome[count($nome) - 1]);
?>
      <tr>
          <td><?php echo $LANG['patients']['budget']?> <?php echo $i+1?></td>
          <td><?php echo $dentista->RetornaDados('titulo').' '.$nome;?></td>
          <td><?php echo converte_data($row[data], 2)?></td>
          <td align="right"><?php echo $LANG['general']['currency'].' '.money_form($row[valortotal]-($row[valortotal]*($row[desconto]/100)))?></td>
          <td><div align="center"><a href="javascript:Ajax('pacientes/orcamentofechar', 'conteudo', 'codigo=<?php echo $_GET[codigo]?>&indice_orc=<?php echo ($i+1)?>&acao=editar&subacao=editar&codigo_orc=<?php echo $row[codigo]?>')"><img src="imagens/icones/editar.gif" border="0" alt="Editar" width="16" height="18" /></div></td>
          <td><div align="center"><?php echo (($row['confirmado'] != 'Sim')?'':'<img src="imagens/icones/ok.gif" border="0" alt="Confirmado" width="19" height="19" /> '.((mysqli_num_rows(mysqli_query($conn, "SELECT * FROM parcelas_orcamento WHERE codigo_orcamento = ".$row['codigo']." AND pago = 'Não'")) > 0)?$LANG['patients']['open']:$LANG['patients']['paid']))?></div></td>
        </tr>
<?php
		$i++;
	}
?>
  </tbody>
      </table>
      </fieldset>
        <br />
        <div align="center"></div>
      </form>      </td>
    </tr>
</table>
</div>
