<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

 

	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!checklog()) {
		die($frase_log);
	}
    function em_debito($codigo) {
        $query = mysqli_query($conn, "SELECT DISTINCT(vo.codigo_paciente), tp.* FROM pacientes tp INNER JOIN v_orcamento vo ON tp.codigo = vo.codigo_paciente WHERE data < '".date('Y-m-d')."' AND pago = 'Não' AND confirmado = 'Sim' AND baixa = 'Não' AND tp.codigo = ".$codigo." ORDER BY `nome` ASC");
        return(mysqli_num_rows($query) > 0);
    }
?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th><?php echo $LANG['patients']['patient']?></th>
        <th>Sexo</th>
        <th style="width: 109px;">Telefone</th>
        <th title="Ficha clínica">F.C</th>
        <th>Localidade</th>
        <th>Endereço</th>
        <th style="width: 106px;">Ações</th>
      </tr>
    </thead>
      
<?php
    $_GET['pesquisa'] = utf8_decode ( htmlspecialchars( utf8_encode($_GET['pesquisa']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') );
	$pacientes = new TPacientes();
	if($_GET[campo] == 'nascimento') {

        if ( strlen ( $_GET['pesquisa'] ) <= 2 ) {
            $where .= "MONTH(nascimento) = '".$_GET['pesquisa']."'";
        } else {

            $pesq = explode ( '_' , $_GET['pesquisa'] );
            foreach ( $pesq as $k => $v ) {
                $v = explode ( '-' , $v );
                $v[1] = str_pad($v[1], 2, '0', STR_PAD_LEFT);
                $pesq[$k] = implode ( '-' , $v );
            }

            $where = "RIGHT(nascimento, 5) = '".$pesq[0]."'";
            if ( count ( $pesq ) > 1 ) {
                $where = "DATE_FORMAT(nascimento, '%m-%d') BETWEEN '".$pesq[0]."' AND '".$pesq[1]."'";
            }

        }

	} elseif($_GET[campo] == 'nome') {
		$where = "nome LIKE '%".$_GET[pesquisa]."%'";
	} elseif($_GET[campo] == 'telefone') {
		$where = "telefone1 = '".$_GET[pesquisa]."' OR telefone2 = '".$_GET[pesquisa]."' OR celular = '".$_GET[pesquisa]."'";
	} elseif($_GET[campo] == 'matricula') {
		$where = "codigo = '".$_GET[pesquisa]."'";
	} elseif($_GET[campo] == 'cidade') {
		$where = "cidade LIKE '".$_GET[pesquisa]."%'";
	} elseif($_GET[campo] == 'cep') {
		$where = "cep LIKE '".$_GET[pesquisa]."%'";
	} elseif($_GET[campo] == 'profissao') {
		$where = "profissao LIKE '%".$_GET[pesquisa]."%'";
	} elseif($_GET[campo] == 'area') {
		$where = "tratamento LIKE '%".$_GET[pesquisa]."%'";
	} elseif($_GET[campo] == 'procurado') {
		$where = "codigo_dentistaprocurado = '".$_GET[pesquisa]."'";
	} elseif($_GET[campo] == 'atendido') {
		$where = "codigo_dentistaatendido = '".$_GET[pesquisa]."'";
	} elseif($_GET[campo] == 'indicacao') {
        $where = "indicadopor LIKE '%".$_GET[pesquisa]."%'";
    } elseif($_GET[campo] == 'endereco') {
        $where = "endereco LIKE '%".$_GET[pesquisa]."%'";
    }
	if($_GET[pg] != '') {
		$limit = ($_GET[pg]-1)*PG_MAX;
	} else {
		$limit = 0;
		$_GET[pg] = 1;
	}
	$sql = "SELECT * FROM `pacientes` WHERE ".$where." ORDER BY `nome` ASC";

    if($_GET['campo'] == 'debito') {
        $sql = "SELECT DISTINCT(vo.codigo_paciente), tp.* FROM pacientes tp INNER JOIN v_orcamento vo ON tp.codigo = vo.codigo_paciente WHERE data < '".date('Y-m-d')."' AND pago = 'Não' AND confirmado = 'Sim' AND baixa = 'Não' ORDER BY `nome` ASC";
    }
    if($_GET['campo'] == 'agendados') {
        $sql = "SELECT DISTINCT ta.codigo_paciente, tp.* FROM agenda ta INNER JOIN pacientes tp ON ta.codigo_paciente = tp.codigo WHERE ta.data = CURDATE()";
    }
	$lista = $pacientes->ListPacientes($sql.' LIMIT '.$limit.', '.PG_MAX);
	$total_regs = $pacientes->ListPacientes($sql);
	$par = $odev = "F0F0F0";
	$impar = "F8F8F8";
	for($i = 0; $i < count($lista); $i++) {
		if($i % 2 == 0) {
			$odev = $par;
		} else {
			$odev = $impar;
		}
?>
    <tr>
      <td style="line-height:33px;overflow: hidden;"><?php echo strtoupper(((encontra_valor('pacientes', 'codigo', $lista[$i]['codigo'], 'falecido') == 'Sim')?'<font color="#808080">':((em_debito($lista[$i][codigo]))?'<font color="red">':'')).utf8_encode($lista[$i][nome]).' '.getStatus(encontra_valor('pacientes', 'codigo', $lista[$i]['codigo'], 'status').''))?></td>
      <td style="line-height:33px;"><?php echo getSexo(utf8_encode($lista[$i][sexo])); ?></td>
      <td style="line-height:33px;"><?php echo utf8_encode($lista[$i][celular]); ?></td>
      <td style="line-height:33px;"><?php echo ((encontra_valor('pacientes', 'codigo', $lista[$i]['codigo'], 'falecido') == 'Sim')?'<font color="#808080">':((em_debito($lista[$i][codigo]))?'<font color="red">':'')).$lista[$i][codigo]?></td>
      <td style="line-height:33px;"><?php echo utf8_encode($lista[$i][cidade]." - ".$lista[$i][estado]); ?></td>
      <td style="line-height:33px;overflow: hidden;"><?php echo utf8_encode($lista[$i][endereco]); ?></td>
      <td>
        <!-- Split button -->
        <div class="btn-group">
          <button type="button" class="btn btn-primary">Ações</button>
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><?php echo ((verifica_nivel('pacientes', 'V'))?'<a href="javascript:Ajax(\'pacientes/incluir\', \'conteudo\', \'codigo='.$lista[$i][codigo].'&acao=editar\')">Editar / Visualizar</a>':'')?></li>
            <li><?php echo ((verifica_nivel('pacientes', 'A'))?'<a href="javascript:Ajax(\'pacientes/gerenciar\', \'conteudo\', \'codigo='.$lista[$i][codigo].'" onclick="return confirmLink(this)">Excluir</a>':'')?></li>
          </ul>
        </div>
        
        
      </td>
    
    </tr>
<?php
	}
?>
  </table>
  <br>
  <table class="table" style="background:#ECECEC;height:40px;line-height:40px;padding-left:5px;border-radius:3px;">
    <tr>
      <td style="border-top:0;line-height:34px;">
      <?php echo $LANG['patients']['total_patients']?>: <b><?php echo count($total_regs)?></b>
      </td>
      <td style="border-top:0;line-height:34px;text-align:left;" align="center">
<?php
	$pg_total = ceil(count($total_regs)/PG_MAX);
	$i = $_GET[pg] - 5;
	if($i <= 1) {
		$i = 1;
		$reti = '';
	} else {
		$reti = '...&nbsp;&nbsp;';
	}
	$j = $_GET[pg] + 5;
	if($j >= $pg_total) {
		$j = $pg_total;
		$retf = '';
	} else {
		$retf = '...';
	}
	echo $reti;
	while($i <= $j) {
		if($i == $_GET[pg]) {
			echo $i.'&nbsp;&nbsp;';
		} else {
			echo '<a href="javascript:;" onclick="javascript:Ajax(\'pacientes/pesquisa\', \'pesquisa\', \'pesquisa=\'+getElementById(getElementById(\'id_procurar\').value).value+\'&campo=\'+getElementById(\'campo\').options[getElementById(\'campo\').selectedIndex].value+\'&pg='.$i.'\')"><button class="btn btn-default">'.$i.'</button></a>&nbsp;&nbsp;';
		}
		$i++;
	}
	echo $retf;
?>
      </td>
      <td width="43%" align="right">
        <a href="relatorios/pacientes.php?sql=<?php echo $sql;?>" target="_blank" style="text-decoration:none;">
            <button class="btn btn-warning">
                <span class="glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print_report']?>
            </button>
        </a>
        <a href="etiquetas/print_etiqueta.php?sql=<?php echo $sql; ?><?php echo ($_GET['campo']=='nascimento' ? '&nasc=true' : '')?>" target="_blank">
            <button class="btn btn-warning">
                <span class="glyphicon glyphicon-print"></span> <?php echo $LANG['patients']['print_labels']?>
            </button>
        </a>
      </td>
    </tr>
  </table>
