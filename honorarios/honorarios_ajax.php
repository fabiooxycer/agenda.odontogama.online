<?php

include "../lib/config.inc.php";
include "../lib/func.inc.php";
include "../lib/classes.inc.php";
require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);

$sistema = new sistema(); 
$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	/*if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
      }

      if(!verifica_nivel('honorarios', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
      }*/

      if($_GET['confirm_del'] == "delete") {
        mysqli_query($conn, "DELETE FROM honorarios WHERE codigo = '".$_GET['codigo']."'") or die(mysqli_error());
        mysqli_query($conn, "DELETE FROM honorarios_convenios WHERE codigo_procedimento = '".$_GET['codigo']."'");
      }

      if(isset($_POST['Salvar'])) {
        $obrigatorios[1] = 'codigo';
        $obrigatorios[] = 'procedimento';
        $obrigatorios[] = 'valor_particular';
        $i = $j = 0;
        foreach($_POST as $post => $valor) {
         $i++;
         if(array_search($post, $obrigatorios) && $valor == "") {
           $j++;
           $r[$j] = '<font color="#FF0000">';
         }
       }
       if($j == 0) {
        $codigo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT RIGHT( codigo, 3 ) AS autoindex FROM `honorarios` WHERE codigo LIKE '".$_POST['area']."%' ORDER BY codigo DESC LIMIT 1"));
        $codigo = $_POST['area'].completa_zeros($codigo['autoindex']+1, 3);
        $caixa = new THonorarios();
        $caixa->SetDados('codigo', $codigo);
        $caixa->SetDados('procedimento', $_POST['procedimento']);
        $caixa->SalvarNovo();
        $caixa->Salvar();
        mysqli_query($conn, "INSERT INTO honorarios_convenios VALUES (1, '".$codigo."', '".$_POST['valor_particular']."')");
      }
    }
    $disabled = 'disabled';
    if(checknivel('Administrador')) {
      $disabled = '';
    }
    ?>
    <script>
      function esconde(campo) {
        if(campo.selectedIndex == 2) {
          document.getElementById('procurar').style.display = 'none';
          document.getElementById('procurar1').style.display = '';
          document.getElementById('procurar1').selectedIndex = 0;
          document.getElementById('id_procurar').value = 'procurar1';
        } else {
          document.getElementById('procurar').style.display = '';
          document.getElementById('procurar').value = '';
          document.getElementById('procurar').focus();
          document.getElementById('procurar1').style.display = 'none';
          document.getElementById('id_procurar').value = 'procurar';
        }
      }
    </script>

    <div class="panel panel-default">
      <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Pesquisa</div>
      <div class="panel-body">
        <table class="table">
          <tr>
            <td>
              <?php echo $LANG['fee_table']['search_for']?><br>
              <select name="campo" id="campo" class="form-control" onchange="esconde(this)">
                <option value="procedimento"><?php echo $LANG['fee_table']['procedure']?></option>
                <option value="codigo"><?php echo $LANG['fee_table']['code']?></option>
                <option value="area"><?php echo $LANG['fee_table']['area']?></option>
              </select>
              <input type="hidden" id="id_procurar" value="procurar">
            </td>

            <td>
              <br>
              <input name="procurar" id="procurar" type="text" class="form-control" size="40" maxlength="40" onkeyup="javascript:Ajax('honorarios/pesquisa', 'pesquisa', 'codigo_convenio=<?php echo $_GET['codigo_convenio']?>&pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
              <select name="procurar1" id="procurar1" style="display:none" class="form-control" onchange="javascript:Ajax('honorarios/pesquisa', 'pesquisa', 'codigo_convenio=<?php echo $_GET['codigo_convenio']?>&pesquisa='+this.options[this.selectedIndex].value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
                <option></option>
                <option value="CO"><?php echo $LANG['fee_table']['oral_surgery']?></option>
                <option value="DE"><?php echo $LANG['fee_table']['dentistic']?></option>
                <option value="EN"><?php echo $LANG['fee_table']['endodonty']?></option>
                <option value="EX"><?php echo $LANG['fee_table']['clinic_examination']?></option>
                <option value="IM"><?php echo $LANG['fee_table']['implantodonty']?></option>
                <option value="OD"><?php echo $LANG['fee_table']['odontopediatry']?></option>
                <option value="OR"><?php echo $LANG['fee_table']['orthodonty']?></option>
                <option value="RA"><?php echo $LANG['fee_table']['radiology']?></option>
                <option value="PE"><?php echo $LANG['fee_table']['periodonty']?></option>
                <option value="PR"><?php echo $LANG['fee_table']['prevention']?></option>
                <option value="PO"><?php echo $LANG['fee_table']['prosthesis']?></option>
                <option value="TE"><?php echo $LANG['fee_table']['laboratory_test_and_examination']?></option>
              </select>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['fee_table']['fee_table']?> - <?php echo encontra_valor('convenios', 'codigo', $_GET['codigo_convenio'], 'nomefantasia')?></b></div>
      <div class="panel-body">

        <?php
        if(verifica_nivel('honorarios', 'I')) {
          ?>
          <div class="panel panel-default">
            <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Incluir novo</div>
            <div class="panel-body">
              <form id="form2" name="form2" method="POST" action="honorarios/honorarios_ajax.php?codigo_convenio=<?php echo $_GET['codigo_convenio']?>" onsubmit="formSender(this, 'conteudo'); this.reset(); return false;">
                <table class="table">
                  <tr>
                    <td>Área <br />
                      <select name="area" class="form-control" id="area" <?php echo $disabled?>>
                        <option value="CO"><?php echo $LANG['fee_table']['oral_surgery']?></option>
                        <option value="DE"><?php echo $LANG['fee_table']['dentistic']?></option>
                        <option value="EN"><?php echo $LANG['fee_table']['endodonty']?></option>
                        <option value="EX"><?php echo $LANG['fee_table']['clinic_examination']?></option>
                        <option value="IM"><?php echo $LANG['fee_table']['implantodonty']?></option>
                        <option value="OD"><?php echo $LANG['fee_table']['odontopediatry']?></option>
                        <option value="OR"><?php echo $LANG['fee_table']['orthodonty']?></option>
                        <option value="RA"><?php echo $LANG['fee_table']['radiology']?></option>
                        <option value="PE"><?php echo $LANG['fee_table']['periodonty']?></option>
                        <option value="PR"><?php echo $LANG['fee_table']['prevention']?></option>
                        <option value="PO"><?php echo $LANG['fee_table']['prosthesis']?></option>
                        <option value="TE"><?php echo $LANG['fee_table']['laboratory_test_and_examination']?></option>
                      </select>
                    </td>
                    <td><?php echo $LANG['fee_table']['procedure']?> <br />
                      <input type="text" size="50" name="procedimento" id="procedimento" class="form-control" <?php echo $disabled?>>
                    </td>
                    <td><?php echo $LANG['fee_table']['private_value']?><br />
                      <input type="text" size="15" name="valor_particular" id="valor_particular" class="form-control" <?php echo $disabled?> onKeypress="return Ajusta_Valor(this, event);">
                    </td>
                    <td align="right">&nbsp; <br />
                      <input type="submit" name="Salvar" id="Salvar" value="<?php echo $LANG['fee_table']['save']?>" class="btn btn-success" <?php echo $disabled?>>
                    </td>
                  </tr>
                </table>
              </form>
            </div></div>
            <?php
          }
          ?>

          <div id="pesquisa"></div>
          <script>
            document.getElementById('procurar').focus();
            Ajax('honorarios/pesquisa', 'pesquisa', 'codigo_convenio=<?php echo $_GET['codigo_convenio']?>&campo=procedimento&pesquisa=');
          </script>
        </div>
      </div>
