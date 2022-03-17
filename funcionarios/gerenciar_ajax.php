<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	
	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
	if(!verifica_nivel('funcionarios', 'L')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }
	if($_GET[confirm_del] == "delete") {
		mysqli_query($conn, "DELETE FROM `funcionarios` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysqli_error());
		@unlink('fotos/'.$_GET[codigo].'.jpg');
	}
?>
<script>
function esconde(campo) {
    if(campo.selectedIndex == 2) {
        document.getElementById('procurar').style.display = 'none';
        document.getElementById('procurar1').style.display = '';
        document.getElementById('id_procurar').value = 'procurar1';
    } else {
        document.getElementById('procurar').style.display = '';
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
					<?php echo $LANG['employee']['search_for']?><br>
			      <select name="campo" id="campo" class="form-control" onchange="esconde(this)">
			      	<option value="nome"><?php echo $LANG['employee']['name']?></option>
			      	<option value="cpf"><?php echo $LANG['employee']['document1']?></option>
			      	<option value="nascimento"><?php echo $LANG['patients']['birthdays_in_month']?></option>
			    </select>
			  </td>
			  <td>
			  	<br>
			    <input type="hidden" id="id_procurar" value="procurar">
			      <input name="procurar" id="procurar" type="text" class="form-control" size="20" maxlength="40" onkeyup="javascript:Ajax('funcionarios/pesquisa', 'pesquisa', 'pesquisa='+this.value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
			      <select name="procurar1" id="procurar1" style="display:none" class="form-control" onchange="javascript:Ajax('funcionarios/pesquisa', 'pesquisa', 'pesquisa='+this.options[this.selectedIndex].value+'&campo='+getElementById('campo').options[getElementById('campo').selectedIndex].value)">
			        <option value=""></option>
							<?php
							    for($i = 1; $i <= 12; $i++) {
							      echo '<option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'">'.nome_mes($i).'</option>';
							    }
							?>
			      </select>
			   </td>
			   <td align="right">
			   	<br>
			      <?php echo ((verifica_nivel('funcionarios', 'I'))?'<a href="javascript:Ajax(\'funcionarios/incluir\', \'conteudo\', \'\')"><button class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span> '.$LANG['employee']['include_new_employee'].'</button></a>':'')?>
				 </td>
				</tr>
			</table>
	</div>
</div>

<div class="panel panel-default" id="conteudo_central">
	<div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> <b><?php echo $LANG['employee']['manage_employee']?></b></div>
	<div class="panel-body">
    
  <div id="pesquisa"></div>
  <script>
  document.getElementById('procurar').focus();
  Ajax('funcionarios/pesquisa', 'pesquisa', 'pesquisa=');
  </script>
</div>
</div>

