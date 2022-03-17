<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	$sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

  if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('pacientes', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `pacientes` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
		mysqli_query($conn, "DELETE FROM `exameobjetivo` WHERE `codigo_paciente` = '".$_GET[codigo]."'") or die(mysqli_error());
		mysqli_query($conn, "DELETE FROM `inquerito` WHERE `codigo_paciente` = '".$_GET[codigo]."'") or die(mysqli_error());
        mysqli_query($conn, "UPDATE agenda SET descricao = NULL , procedimento = NULL , faltou = 'Não' , codigo_paciente = NULL WHERE codigo_paciente = '".$_GET[codigo]."'");
	}
?>
<script>
    function esconde(campo) {
        if(campo.selectedIndex == 7) {
            document.getElementById('procurar').style.display = 'none';
            document.getElementById('procurar1').style.display = '';
            document.getElementById('procurar2').style.display = 'none';
            document.getElementById('procurar3').style.display = 'none';
            document.getElementById('procurar4').style.display = 'none';
            document.getElementById('id_procurar').value = 'procurar1';
        } else if(campo.selectedIndex == 10 || campo.selectedIndex == 11) {
            document.getElementById('procurar').style.display = 'none';
            document.getElementById('procurar1').style.display = 'none';
            document.getElementById('procurar2').style.display = 'none';
            document.getElementById('procurar3').style.display = 'none';
            document.getElementById('procurar4').style.display = 'none';
            document.getElementById('id_procurar').value = 'procurar';
            Ajax('pacientes/pesquisa', 'pesquisa', 'campo='+document.getElementById('campo').value);
        } else if(campo.selectedIndex == 8 || campo.selectedIndex == 9) {
            document.getElementById('procurar').style.display = 'none';
            document.getElementById('procurar1').style.display = 'none';
            document.getElementById('procurar2').style.display = '';
            document.getElementById('procurar2').selectedIndex = 0;
            document.getElementById('procurar3').style.display = 'none';
            document.getElementById('procurar4').style.display = 'none';
            document.getElementById('id_procurar').value = 'procurar2';
        } else if(campo.selectedIndex == 1) {
            document.getElementById('procurar').style.display = 'none';
            document.getElementById('procurar1').style.display = 'none';
            document.getElementById('procurar2').style.display = 'none';
            document.getElementById('procurar3').style.display = 'flex';
            document.getElementById('procurar3').selectedIndex = 0;
            document.getElementById('procurar4').style.display = 'none';
            document.getElementById('id_procurar').value = 'procurar3';
        } else if(campo.selectedIndex == 5) {
            document.getElementById('procurar').style.display = 'none';
            document.getElementById('procurar1').style.display = 'none';
            document.getElementById('procurar2').style.display = 'none';
            document.getElementById('procurar3').style.display = 'none';
            document.getElementById('procurar3').selectedIndex = 0;
            document.getElementById('procurar4').style.display = '';
            document.getElementById('id_procurar').value = 'procurar3';
        } else {
            document.getElementById('procurar').style.display = '';
            document.getElementById('procurar1').style.display = 'none';
            document.getElementById('procurar2').style.display = 'none';
            document.getElementById('procurar3').style.display = 'none';
            document.getElementById('procurar4').style.display = 'none';
            document.getElementById('id_procurar').value = 'procurar';
        }
    }

    function niver() {

        var pesq = '';

        document.getElementById('dia1').disabled = true;
        document.getElementById('mes2').disabled = true;
        document.getElementById('dia2').disabled = true;

        if ( document.getElementById('mes1').options[document.getElementById('mes1').selectedIndex].value != '' ) {
            pesq = document.getElementById('mes1').options[document.getElementById('mes1').selectedIndex].value;
            document.getElementById('dia1').disabled = false;
        }
        if ( document.getElementById('dia1').value != '' ) {
            pesq =   document.getElementById('dia1').value;
            document.getElementById('mes2').disabled = false;
        }

        if ( document.getElementById('mes2').options[document.getElementById('mes2').selectedIndex].value != '' ) {
            pesq =  document.getElementById('mes2').options[document.getElementById('mes2').selectedIndex].value;
            document.getElementById('dia2').disabled = false;
        }
        if ( document.getElementById('dia2').value != '' ) {
            pesq =  document.getElementById('dia2').value;
        }

        Ajax('pacientes/pesquisa', 'pesquisa', 'pesquisa='+ pesq+'&campo='+document.getElementById('campo').options[document.getElementById('campo').selectedIndex].value)
    }
</script>

<script type="text/javascript">
  
  $(function(){
    //$("body").css("background", "#fff");
  });

</script>

<div class="panel panel-default">
  <div class="panel-body">

    <div style="float:left;">
    <table class="table">
      <tr>
        <td style="border-top:0;">
          <br>
         
            <?php echo ((verifica_nivel('pacientes', 'I'))?'<a href="javascript:Ajax(\'pacientes/incluir\', \'conteudo\', \'\')"> <button class="btn btn-success">'.$LANG['patients']['include_new_patient'].'</button></a>':'')?>

        </td>
        <td style="border-top:0;">

    <?php echo $LANG['patients']['search_for']?><br>
              <select name="campo" id="campo" class="form-control" onchange="esconde(this)">
                <option value="nome"><?php echo $LANG['patients']['name']?></option>
                <option value="nascimento"><?php echo $LANG['patients']['birthdays']?></option>
                <option value="matricula"><?php echo $LANG['patients']['clinical_sheet']?></option>
                <option value="cidade"><?php echo $LANG['patients']['city']?></option>
                <option value="cep"><?php echo $LANG['patients']['zip']?></option>
                <option value="telefone"><?php echo $LANG['patients']['telephone']?></option>
                <option value="profissao"><?php echo $LANG['patients']['profession']?></option>
                <option value="area"><?php echo $LANG['patients']['treatment_area']?></option>
                <option value="procurado"><?php echo $LANG['patients']['professional_searched']?></option>
                <option value="atendido"><?php echo $LANG['patients']['professional_who_answered']?></option>
                <option value="debito"><?php echo $LANG['patients']['patients_in_debt']?></option>
                <option value="agendados"><?php echo $LANG['patients']['scheduled_patients']?></option>
                <option value="indicacao"><?php echo $LANG['patients']['indicated_by']?></option>
                <option value="endereco"><?php echo $LANG['patients']['address1']?></option>
              </select>
            </td><td style="border-top:0;">
            <br>
              <input type="hidden" id="id_procurar" value="procurar">
           </td>
           <td style="border-top:0;">
            
              <br>
              <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('pacientes/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
              </td>
              <td style="border-top:0;">
                <br>
              <select name="procurar1" id="procurar1" style="display:none" class="form-control" onchange="javascript:Ajax('pacientes/pesquisa', 'pesquisa', 'pesquisa='+this.options[this.selectedIndex].value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
                <option value=""></option>
                <option value="Ortodontia"><?php echo $LANG['patients']['orthodonty']?></option>
                <option value="Implantodontia"><?php echo $LANG['patients']['implantodonty']?></option>
                <option value="Dentística"><?php echo $LANG['patients']['dentistic']?></option>
                <option value="Prótese"><?php echo $LANG['patients']['prosthesis']?></option>
                <option value="Odontopediatria"><?php echo $LANG['patients']['odontopediatry']?></option>
                <option value="Cirurgia"><?php echo $LANG['patients']['surgery']?></option>
                <option value="Endodontia"><?php echo $LANG['patients']['endodonty']?></option>
                <option value="Periodontia"><?php echo $LANG['patients']['periodonty']?></option>
                <option value="Radiologia"><?php echo $LANG['patients']['radiology']?></option>
                <option value="DTM"><?php echo $LANG['patients']['dtm']?></option>
                <option value="Odontogeriatria"><?php echo $LANG['patients']['odontogeriatry']?></option>
                <option value="Ortopedia"><?php echo $LANG['patients']['orthopedy']?></option>
              </select>
            </td>
            <td style="border-top:0;">
              <br>
              <select name="procurar2" id="procurar2" style="display:none" class="form-control" onchange="javascript:Ajax('pacientes/pesquisa', 'pesquisa', 'pesquisa='+this.options[this.selectedIndex].value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
                <option></option>
<?php
  $dentista = new TDentistas();
  $lista = $dentista->ListDentistas();
  for($i = 0; $i < count($lista); $i++) {
    if($row[codigo_dentistaprocurado] == $lista[$i][codigo]) {
      echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
    } else {
      echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
    }
  }
?>
        </select>
              <div id="procurar3" style="display:none">
                  <select name="mes1" id="mes1" class="form-control" onchange="javascript:niver()">
                      <option value=""></option>
                      <?php
                      for($i = 1; $i <= 12; $i++) {
                          echo '                <option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'">'.nome_mes($i).'</option>';
                      }
                      ?>
                  </select>
                  
                  <input name="dia1" id="dia1" disabled="disabled" type="text" class="form-control" size="4" maxlength="2" onkeyup="javascript:niver()">
                  
                  <select name="mes2" id="mes2" disabled="disabled" class="form-control" onchange="javascript:niver()">
                      <option value=""></option>
                      <?php
                      for($i = 1; $i <= 12; $i++) {
                          echo '                <option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'">'.nome_mes($i).'</option>';
                      }
                      ?>
                  </select>
                  <input name="dia2" id="dia2" disabled="disabled" type="text" class="form-control" size="4" maxlength="2" onkeyup="javascript:niver()">
              </div>
              <br>
              <input name="procurar4" id="procurar4" style="display:none" type="text" class="form-control" size="20" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" onkeyup="javascript:Ajax('pacientes/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
            </tr>
          </table>
            </div></div>
  </div>
</div>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><b><?php echo $LANG['patients']['manage_patients']?></b></div>
  <div class="panel-body">

  
	  </td>
    </tr>
</table>
<div class="conteudo" id="table dados"><br>
  
      
    
  <div id="pesquisa"></div>
  <script>
  document.getElementById('procurar').focus();
  Ajax('pacientes/pesquisa', 'pesquisa', 'pesquisa=&campo=nome');
  </script>
</div></div>
