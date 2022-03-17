<?php
  //header("Content-type: text/html; charset=ISO-8859-1", true);
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
	
	if(!checklog()) {
		die($frase_log);
	}

  if($_POST['tipo'] == "add")
  {
    $texto = utf8_decode(mysqli_real_escape_string($conn, $_POST['texto']));
    $id = mysqli_real_escape_string($conn, $_POST['paciente']);
    $dente = mysqli_real_escape_string($conn, $_POST['dente']);

    mysqli_query($conn, "INSERT INTO odontograma (descricao, idPaciente, dente) VALUES ('$texto', '$id', '$dente')")or die(mysqli_error());

    exit;
  }

  if($_POST['tipo'] == "apagar")
  {

    $id = mysqli_real_escape_string($conn, $_POST['id']);

    mysqli_query($conn, "DELETE FROM odontograma WHERE id='$id'")or die(mysqli_error());

    exit;
  }

	$acao = '&acao=editar';
	$paciente = new TPacientes();
    $query = mysqli_query($conn, "SELECT * FROM odontograma WHERE idPaciente = '$_GET[codigo]'") or die('Line 39: '.mysqli_error());
    while($row = mysqli_fetch_assoc($query)) {
        $dente[$row['dente']] = $row['descricao'];
    }
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];

echo "<script type='text/javascript'>var id = $_GET[codigo];</script>";
?>

<style type="text/css">
<!--
.style4 {color: #FFFFFF}
#dente{border: 1px solid #c10202;width: 50px;border-radius:5px;height:87px;}
#numero, #numero_1{width: 100%;height: 30px;border:1px solid #e88181;margin-top: 5px;text-align: center;line-height:30px;font-family: Arial; font-size: 10pt;
font-weight: bold;border-radius: 5px;background: #ffb5b5; color: #fff;text-shadow:0 0 2px silver;}

#fundoEscuro{
  width: 84%;
  height: 100%;
  left: 16%;
  top:0;
  background: rgba(255, 255, 255, 0.95);
  z-index: 1;
  position: fixed;
  display: none;
}

#tabelaDentes td div{cursor: pointer;}

#numero_1{margin-bottom: 5px;}
#corpo{width:760px;height:500px;margin: 0 auto;display: none;box-shadow: 2px 2px 2px #9a9a9a;}
#odontoConteudo{height: 381px;overflow-y: scroll;}
-->
</style>



<script type="text/javascript">

  var idDente = "";
  $(function(){

    $("#corpo").click(function(){
      return false;
    });

    $("#salvar").click(function(){
      var texto = $("#texto").val();

      if(texto == "") return $("#texto").focus();

      $("#texto").val("");

      $.post("pacientes/odontograma_ajax.php", {tipo: 'add', dente: idDente, paciente: id, texto: texto}).done(function(){
        Ajax("pacientes/carr_odonto", "odontogramaConteudo", "dente="+idDente+"&paciente="+id);
      });
    });

    $("table#tabelaDentes td").click(function(){

      $("#tituloDente b").text("Tratamentos - Dente "+$(this).attr("id"));
      idDente = $(this).attr("id");

      Ajax("pacientes/carr_odonto", "odontogramaConteudo", "dente="+idDente+"&paciente="+id);

      $("#fundoEscuro").fadeIn("fast", function(){
        $("#corpo").fadeIn("fast");
      });
      

    });

    $("#fundoEscuro, #fechar").click(function(){

      $("#corpo").fadeOut("fast", function(){
        $("#fundoEscuro").fadeOut("fast");
      });
      

    });

  });

  function apagar(idO)
  {
    $.post("pacientes/odontograma_ajax.php", {tipo: 'apagar', id: idO}).done(function(){
      Ajax("pacientes/carr_odonto", "odontogramaConteudo", "dente="+idDente+"&paciente="+id);
    });
  }

</script>

<table id="fundoEscuro">
  <tr>
    <td>
      <div class="panel panel-default" id="corpo">
        <div class="panel-heading" id="tituloDente"><b></b></div>
        <div class="panel-body">
          <div id="odontoConteudo">
            <table class="table table-default" id="odontogramaConteudo">
              <tr>
                <td>
                  Nenhum tratamento para este dente
                </td>
              </tr>
            </table>
          </div>

          <table class="table table-default">
            <tr>
              <td>
                <input type="text" id="texto" class="form-control">
              </td>
              <td align="right">
                <button class="btn btn-success" id="salvar"><span class="glyphicon glyphicon-plus"></span> Incluir</button>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </td>
  </tr>

</table>


<div class="panel panel-default">
    <div class="panel-body">
      <?php 
      $ativo_odonto = true;
      include("submenu.php"); ?>
    </div>
  </div>

<div class="panel panel-default">
  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> <b><?php echo $LANG['patients']['odontogram']?> &nbsp;[<?php echo $strLoCase?>]</b> </div>
  <div class="panel-body">


    <table class="table" id="tabelaDentes">
      <tr>
        <td id="18"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-57px -33px;"></div><div id="numero">18</div></td>
        <td id="17"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-97px -33px;"></div><div id="numero">17</div></td>
        <td id="16"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-147px -31px;"></div><div id="numero">16</div></td>
        <td id="15"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-192px -33px;"></div><div id="numero">15</div></td>
        <td id="14"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-234px -35px;"></div><div id="numero">14</div></td>
        <td id="13"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-273px -36px;"></div><div id="numero">13</div></td>
        <td id="12"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-319px -32px;"></div><div id="numero">12</div></td>
        <td id="11"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-362px -34px;"></div><div id="numero">11</div></td>
        <td id="21"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-412px -34px;"></div><div id="numero">21</div></td>        
        <td id="22"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-455px -31px;"></div><div id="numero">22</div></td>        
        <td id="23"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-494px -31px;"></div><div id="numero">23</div></td>        
        <td id="24"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-539px -31px;"></div><div id="numero">24</div></td>        
        <td id="25"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-580px -31px;"></div><div id="numero">25</div></td>        
        <td id="26"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-627px -25px;"></div><div id="numero">26</div></td>        
        <td id="27"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-675px -25px;"></div><div id="numero">27</div></td>        
        <td id="28"><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-716px -25px;"></div><div id="numero">28</div></td>        

      </tr>
      <tr>
        <td id="48"><div id="numero_1">48</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-59px -187px;"></div></td>
        <td id="47"><div id="numero_1">47</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-109px -187px;"></div></td>
        <td id="46"><div id="numero_1">46</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-162px -187px;"></div></td>
        <td id="45"><div id="numero_1">45</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-217px -187px;"></div></td>
        <td id="44"><div id="numero_1">44</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-258px -187px;"></div></td>
        <td id="43"><div id="numero_1">43</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-294px -185px;"></div></td>
        <td id="42"><div id="numero_1">42</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-331px -185px;"></div></td>
        <td id="41"><div id="numero_1">41</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-368px -185px;"></div></td>
        <td id="31"><div id="numero_1">31</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-403px -185px;"></div></td>
        <td id="32"><div id="numero_1">32</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-438px -189px;"></div></td>
        <td id="33"><div id="numero_1">33</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-478px -183px;"></div></td>
        <td id="34"><div id="numero_1">34</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-524px -183px;"></div></td>
        <td id="35"><div id="numero_1">35</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-565px -183px;"></div></td>
        <td id="36"><div id="numero_1">36</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-612px -185px;"></div></td>
        <td id="37"><div id="numero_1">37</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-670px -185px;"></div></td>
        <td id="38"><div id="numero_1">38</div><div id="dente" style="background:url(pacientes/img/odontograma1.png);background-size:809px;background-position:-717px -185px;"></div></td>
      </tr>
    </table>


<!--<table class="table">
  <tr>
    <td>
      <form id="form2" name="form2" method="POST" action="pacientes/incluir_ajax.php<?php echo $frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background: url('pacientes/img/odontograma.gif') center center no-repeat;background-size:168px;">
        <tr>
          <td width="38%" align="right">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
    for($i = 18; $i != 49; $i++) {
?>
              <tr>
                <td width="100%" align="right" valign="middle">
                  <input type="text" style="width:100%;" name="dente[<?php echo $i?>]" value="<?php echo $dente[$i]?>" class="forms" <?php echo $disable?>
                  onblur="Ajax('pacientes/atualiza', 'pacientes_atualiza', 'descricao='+this.value+'&codigo_paciente=<?php echo $_GET['codigo']?>&dente=<?php echo $i?>');" />
                </td>
              </tr>
<?php
        if($i == 11) {
            $i = 40;
        }
        if($i < 40) {
            $i -= 2;
        }
    }
?>
            </table>
          </td>
          <td width="22%" align="center">

          </td>
          <td width="38%" align="center">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
    for($i = 28; $i != 39; $i++) {
?>
              <tr>
                <td width="100%" align="left" valign="middle">
                  <input type="text" style="width:100%;" name="dente[<?php echo $i?>]" value="<?php echo $dente[$i]?>" class="forms" <?php echo $disable?>
                  onblur="Ajax('pacientes/atualiza', 'pacientes_atualiza', 'descricao='+this.value+'&codigo_paciente=<?php echo $_GET['codigo']?>&dente=<?php echo $i?>');" />
                </td>
              </tr>
<?php
        if($i == 21) {
            $i = 30;
        }
        if($i < 30) {
            $i -= 2;
        }
    }
?>
            </table>
          </td>
        </tr>
      </table>-->
    </form>
    </td>
  </tr>
    <tr>
      <td align="right"> <br />
        
      </td>
    </tr>
</table>
<table class="table">
      <tr>
        <td>
          <a href="relatorios/odontograma.php?codigo=<?php echo $_GET['codigo']?>" target="_blank">
            <button class="btn btn-warning">
              <span class="glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print_odontogram']?>
            </button>
          </a>
        </td>
      </tr>
    </table>
    <br><br>
    <div class="panel panel-default">
      <div class="panel-heading"><b><span class="glyphicon glyphicon-indent-left"></span> Histórico de procedimentos</b></div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <th>DENTE</th>
            <th>PROCEDIMENTO</th>
          </thead>
          <tbody>

            <?php

              $query = mysqli_query($conn, "SELECT dente, descricao FROM odontograma WHERE idPaciente='$_GET[codigo]' ORDER BY dente ASC")or die(mysqli_error());

              while($resul = mysqli_fetch_array($query))
              {
                echo "<tr><td>$resul[dente]</td><td>$resul[descricao]</td></tr>";
              }

            ?>
            
          </tbody>
        </table>
      </div>
    </div>

    

</div></div>
<div id="pacientes_atualiza"></div>
