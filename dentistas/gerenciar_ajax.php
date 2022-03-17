<?php

	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
  $sistema = new sistema();

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
  
	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('profissionais', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `dentistas` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
		@unlink('fotos/'.$_GET[cpf].'.jpg');
	}
?>
<script type="text/javascript">
function esconde(campo) {

    
    if(campo.selectedIndex == 3) {
        document.getElementById('procurar').style.display = 'none';
        document.getElementById('procurar1').style.display = '';
        document.getElementById('id_procurar').value = 'procurar1';
    } else {
        document.getElementById('procurar').style.display = '';
        document.getElementById('procurar1').style.display = 'none';
        document.getElementById('id_procurar').value = 'procurar';
    }
}

$(function(){

  $("#campo").change(function(){

    var refer = $(this).val();
    

    if(refer == "") 
    {
      $("#procurar").attr("disabled", "disabled");
    }else{
      $("#procurar").removeAttr("disabled");
    }

  });

  $("#procurar1").change(function(){

    pesquisa = $("#procurar1").val();
    campo = $("#campo").val();
    Ajax('dentistas/pesquisa', 'pesquisa', 'pesquisa='+pesquisa+"&campo="+campo);

  });

  $("#procurar").keypress(function(){
    pesquisa = $("#procurar").val();
    campo = $("#campo").val();
    Ajax('dentistas/pesquisa', 'pesquisa', 'pesquisa='+pesquisa+"&campo="+campo);

  });

})
</script>

<style>
.table tbody tr td{border-top:0;}
</style>

<div class="panel panel-default">
  <div class="panel-body">

  <div style="float:left">
    <table class="table">
      <tr>

            <td style="border-top:0;">
              <br>
          <?php echo ((verifica_nivel('profissionais', 'I'))?'<a href="javascript:Ajax(\'dentistas/incluir\', \'conteudo\', \'\')"><button class=\'btn btn-success\'><span class=\' glyphicon glyphicon-plus\'></span> Incluir novo Dentista</button></a>':'')?>
        </td>
        <td style="border-top:0;">
        <br>
              <select name="campo" id="campo" class="form-control" onchange="esconde(this)">
                <option value="">---Procurar Por---</option>
                <option value="nome"><?php echo $LANG['professionals']['name']?></option>
                <option value="cpf"><?php echo $LANG['professionals']['document1']?></option>
                <option value="nascimento"><?php echo $LANG['patients']['birthdays_in_month']?></option>
              </select>
            </td>
            <td style="border-top:0;">
              <br>
              <input type="hidden" id="id_procurar" value="procurar" class="form-control">
              <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" disabled>
              <select name="procurar1" id="procurar1" style="display:none" class="form-control" onchange="javascript:Ajax('dentistas/pesquisa', 'pesquisa', 'pesquisa='%2Bthis.options[this.selectedIndex].value%2B'&campo='%2BgetElementById('campo').options[getElementById('campo').selectedIndex].value)">
                <option value=""></option>
                <?php
                    for($i = 1; $i <= 12; $i++) {
                        echo '                <option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'">'.nome_mes($i).'</option>';
                    }
                ?>
              </select>
            </td>
          </tr>
        </table>

          </div>

        </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="  glyphicon glyphicon-th-list"></span> Gerenciar Dentistas</div>

  <div class="panel-body">

  <div id="pesquisa"></div>

 
  
</div></div><bR><br>
<script>
    Ajax("dentistas/pesquisa", "pesquisa", "pesquisa=");
  </script>



