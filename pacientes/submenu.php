<?php
 
	if($_GET[acao] == 'editar') {
		$odontograma = "onclick=\"javascript:Ajax('pacientes/odontograma','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		//$orcamento = "onClick=\"javascript:Ajax('pacientes/orcamento','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$objetivo = "onClick=\"javascript:Ajax('pacientes/objetivo','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$evolucao = "onclick=\"javascript:Ajax('pacientes/evolucao','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$inquerito = "onclick=\"javascript:Ajax('pacientes/inquerito','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$ortodontia = "onclick=\"javascript:Ajax('pacientes/ortodontia','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$implantodontia = "onclick=\"javascript:Ajax('pacientes/implantodontia','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$fotos = "onClick=\"javascript:Ajax('pacientes/fotos','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$outros = "onClick=\"javascript:Ajax('pacientes/outros','conteudo','codigo=".$_GET[codigo].$acao."')\"";
		$radio = "onClick=\"javascript:Ajax('pacientes/radio','conteudo','codigo=".$_GET[codigo].$acao."')\"";
    $comparecimento = "onClick=\"javascript:Ajax('pacientes/comparecimento','conteudo','codigo=".$_GET[codigo].$acao."')\"";
    $atestado = "onClick=\"javascript:Ajax('pacientes/atestado1','conteudo','codigo=".$_GET[codigo].$acao."')\"";
    $atestado_esp = "onClick=\"javascript:Ajax('pacientes/atestado2','conteudo','codigo=".$_GET[codigo].$acao."')\"";
    $receituario = "onClick=\"javascript:Ajax('pacientes/receituario','conteudo','codigo=".$_GET[codigo].$acao."')\"";
    $contrato = "onClick=\"javascript:Ajax('pacientes/contrato','conteudo','codigo=".$_GET[codigo].$acao."')\"";
	}
	if(($_GET['codigo'] != '' && !verifica_nivel('pacientes', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('pacientes', 'I'))) {
        $disable = 'disabled';
	}
?>

<!--<div class="btn-group" role="group" aria-label="...">
    
        <button class="btn btn-default" onClick="Ajax('pacientes/incluir', 'conteudo', 'codigo=1&acao=editar');">Ficha Clínica</button>
        <?php echo '<button  class="btn btn-default" '.$odontograma.">".$LANG['patients']['odontogram']?></button></a>
        <?php echo '<button  class="btn btn-default" '.$orcamento.">";?>Orçamento</button></a>
        <?php echo '<button  class="btn btn-default" '.$objetivo.">".$LANG['patients']['objective_examination']?></button></a>
        <?php echo '<button  class="btn btn-default" '.$evolucao.">";?>Evolução do Tratamento</button></a>

        <?php echo '<button  class="btn btn-default" '.$inquerito.">";?>Inquérito de Saúde</button></a>
        <?php echo '<button  class="btn btn-default" '.$ortodontia.">".$LANG['patients']['orthodonty']?></button></a>
        <?php echo '<button  class="btn btn-default" '.$implantodontia.">".$LANG['patients']['implantodonty']?></button></a>
        <?php echo '<button  class="btn btn-default" '.$fotos.">".$LANG['patients']['photos']?></button></a>
        <?php echo '<button  class="btn btn-default" '.$radio.">".$LANG['patients']['radiograph']?></button></a>
        <?php echo '<button  class="btn btn-default" '.$outros.">".$LANG['patients']['others']?></button></a>

</div>-->
<ul class="nav nav-pills">
  <li role="presentation" <?php if($ativo_incluir == true) echo "class='active'"; ?>onClick="Ajax('pacientes/incluir', 'conteudo', 'codigo=1&acao=editar');"><a href="#">Ficha Clínica</a></li>
  <li role="presentation" <?php echo $odontograma; if($ativo_odonto == true) echo "class='active'";?>><a href="#">Odontograma</a></li>
  <!--<li role="presentation" <?php echo $orcamento; if($ativo_orcamento == true) echo "class='active'";?>><a href="#">Orçamento</a></li>-->
  
  
  
  <li role="presentation" <?php echo $ortodontia; if($ativo_ortodontia == true) echo "class='active'";?>><a href="#">Ortodontia</a></li>
  <li role="presentation" <?php echo $fotos; if($ativo_fotos == true) echo "class='active'";?>><a href="#">Fotos</a></li>
  <li role="presentation" <?php echo $radio; if($ativo_radio == true) echo "class='active'"; ?>><a href="#">Radiografias</a></li>

  <div class="btn-group nav nav-pills" style="margin-left: 2px;">

  <li role="presentation" <?php if($ativo_mais == true) echo "class='active'"; ?> data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <a href="#">Mais <span class="caret"></span></a>
  </li>
  <ul class="dropdown-menu">
    <li role="presentation" <?php echo $contrato; ?>><a href="#">Contrato ortodontia e implantes</a></li>
    <li role="presentation" <?php echo $comparecimento; ?>><a href="#">Declaração de comparecimento</a></li>
    <li role="presentation" <?php echo $receituario; ?>><a href="#">Receituário</a></li>
    <li role="presentation" <?php echo $atestado; ?>><a href="#">Atestado</a></li>
    <li role="presentation" <?php echo $atestado_esp; ?>><a href="#">Atestado especial</a></li>
    <li role="presentation" <?php echo $objetivo; ?>><a href="#">Exame objetivo</a></li>
  	<li role="presentation" <?php echo $evolucao; ?>><a href="#">Evolução do tratamento</a></li>
  	<li role="presentation" <?php echo $implantodontia; ?>><a href="#">Implantodontia</a></li>
  	<li role="presentation" <?php echo $inquerito; ?>><a href="#">Inquérito de Saúde</a></li>
    <li role="presentation"><a href="relatorios/agenda.php?codigo=<?php echo $_GET[codigo]; ?>" target="_blank">Outros</a></li>
  </ul>
</div>

</ul>

