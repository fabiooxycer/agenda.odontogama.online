<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
	if(($_GET['codigo'] != '' && !verifica_nivel('patrimonio', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('patrimonio', 'I'))) {
        $disable = 'disabled';
    }
	$patrimonio = new TPatrimonios();
	if(isset($_POST[Salvar])) {	
		$obrigatorios[1] = 'codigo';
		$obrigatorios[] = 'descricao';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
				$r[$i] = '<font color="#FF0000">';
			    $j++;
			}
		}
		if($j == 0) {
			if($_GET[acao] == "editar") {
				$patrimonio->LoadPatrimonio($_GET[codigo]);
				$strScrp = "Ajax('patrimonio/gerenciar', 'conteudo', '');";
			}
			$patrimonio->SetDados('codigo', $_POST[codigo]);
			$patrimonio->SetDados('setor', $_POST[setor]);
			$patrimonio->SetDados('descricao', $_POST[descricao]);
			$patrimonio->SetDados('valor', $_POST[valor]);
			$patrimonio->SetDados('dataaquisicao', $_POST[dataaquisicao]);
			$patrimonio->SetDados('tempogarantia', $_POST[tempogarantia]);
			$patrimonio->SetDados('cor', $_POST[cor]);
			$patrimonio->SetDados('quantidade', $_POST[quantidade]);
			$patrimonio->SetDados('fornecedor', $_POST[fornecedor]);
			$patrimonio->SetDados('numeronotafiscal', $_POST[numeronotafiscal]);
			$patrimonio->SetDados('dimensoes', $_POST[dimensoes]);
			$patrimonio->SetDados('observacoes', $_POST[observacoes]);
			if($_GET[acao] != "editar") {
				$patrimonio->SalvarNovo();
				$strScrp = "Ajax('patrimonio/gerenciar', 'conteudo', '');";
			}
			$patrimonio->Salvar();
		}
	}
	if($_GET[acao] == "editar") {
		$strLoCase = $LANG['patrimony']['editing'];
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$patrimonio->LoadPatrimonio($_GET[codigo]);
		$row = $patrimonio->RetornaTodosDados();
	} else {
		$strLoCase = $LANG['patrimony']['including'];
		$row = $_POST;
		$row[nome] = $_POST[nom];
		if(!isset($_POST[codigo]) || $j == 0) {
			$row = "";
			$row[codigo] = next_autoindex('patrimonio');
		} else {
			$row[codigo] = $_POST[codigo];
		}
	}
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-edit"></span> <b>Incluir Patrimônio</b></div>
  <div class="panel-body">
  

<div class="conteudo" id="table dados"><br>
  <form id="form2" name="form2" method="POST" action="patrimonio/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;">
  <table class="table">
    <tr>
      <td colspan="4">
      <fieldset>
        <legend><span class="style1"><?php echo $LANG['patrimony']['patrimony_information']?></span></legend>
        </td>
          <tr>
            <td><?php echo $r[1]?>* <?php echo $LANG['patrimony']['code']?><br />
              <input name="codigo" value="<?php echo $row[codigo]?>" type="text" class="form-control" <?php echo $disable?> id="codigo" /></td>
            <td><?php echo $LANG['patrimony']['sector']?><br />
              <input name="setor" value="<?php echo utf8_encode($row[setor])?>" type="text" class="form-control" <?php echo $disable?> id="setor" /></td>
          
            <td width="287"><?php echo $r[3]?>* <?php echo $LANG['patrimony']['description']?><br />
                <label>
                  <input name="descricao" value="<?php echo utf8_encode($row[descricao])?>" type="text" class="form-control" <?php echo $disable?> id="descricao" size="45" maxlength="150" />
                </label>
                <label></label></td>
            <td width="210"><?php echo $LANG['patrimony']['value']?><br />
              <input name="valor" type="text" class="form-control" <?php echo $disable?> id="valor" value="<?php echo $row[valor]?>" onKeypress="return Ajusta_Valor(this, event);" /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['patrimony']['acquisition_date']?> <br />
              <input name="dataaquisicao" value="<?php echo $row[dataaquisicao]?>" type="text" class="form-control" <?php echo $disable?> id="dataaquisicao" maxlength="150" onKeypress="return Ajusta_Data(this, event);" /></td>
            <td><?php echo $LANG['patrimony']['warranty_time']?><br />
              <input name="tempogarantia" type="text" class="form-control" <?php echo $disable?> id="tempogarantia" value="<?php echo $row[tempogarantia]?>" /></td>
          
            <td><?php echo $LANG['patrimony']['color']?><br />
                <input name="cor" value="<?php echo $row[cor]?>" type="text" class="form-control" <?php echo $disable?> id="cor" size="15" maxlength="50" /></td>
            <td><?php echo $LANG['patrimony']['quantity']?><br />
                <input name="quantidade" value="<?php echo utf8_encode($row[quantidade])?>" type="text" class="form-control" <?php echo $disable?> id="quantidade" /></td>
          </tr>
          <tr>
            <td><?php echo $LANG['patrimony']['supplier']?><br />
                <input name="fornecedor" value="<?php echo utf8_encode($row[fornecedor])?>" type="text" class="form-control" <?php echo $disable?> id="fornecedor" size="30" maxlength="50" />
              <br /></td>
            <td><?php echo $LANG['patrimony']['legal_document']?> <br />
              <input name="numeronotafiscal" value="<?php echo utf8_encode($row[numeronotafiscal])?>" type="text" class="form-control" <?php echo $disable?> id="numeronotafiscal" /></td>
          
            <td colspan="2" valign="top"><?php echo $LANG['patrimony']['dimensions']?><br />
              <input name="dimensoes" value="<?php echo utf8_encode($row[dimensoes])?>" type="text" class="form-control" <?php echo $disable?> id="dimensoes" /></td>
            
              </tr>
          <tr>
            <td colspan="4"><?php echo $LANG['patrimony']['comments']?><br />
              <textarea name="observacoes" cols="25" rows="5" class="form-control" <?php echo $disable?> id="observacoes"><?php echo utf8_encode($row[observacoes]);?></textarea></td>
          </tr>
        
        </fieldset>
      </table>
		<br />
        <div align="center"><br />
          <button name="Salvar" type="submit" class="btn btn-primary" <?php echo $disable?> id="Salvar"><span class="glyphicon glyphicon-ok"></span> Salvar</button>
        </div>
      </form>      
</div>
</div>
