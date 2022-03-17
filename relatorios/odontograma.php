<?php
   header("Content-type: text/html; charset=UTF-8", true);
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

    
    if(!checklog()) {
        die($frase_log);
    }
    include "../timbre_head.php";
    $paciente = new TPacientes();
    $paciente->LoadPaciente($_GET['codigo']);
    $query = mysqli_query($conn, "SELECT * FROM odontograma WHERE idPaciente = ".$_GET['codigo']) or die('Line 39: '.mysqli_error());
    while($row = mysqli_fetch_assoc($query)) {
        $dente[$row['dente']] = $row['descricao'];
    }
?>

<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/responsivo.css">

<br />
<div align="center"><font size="4"><b><?php echo $LANG['reports']['odontogram']?></b></font></div><br /><br />

<table class="table">
  <tr>
    <td>
      <font size="2">PACIENTE:<br />
      <b><?php echo utf8_encode($paciente->RetornaDados('nome')).' ['.$paciente->RetornaDados('codigo').']'?></b>
    </td>
    <td>
      DATA DE IMPRESSÃO:<br />
      <b><?php echo date('d/m/Y')?></b></font><br /><br />
    </td>
  </tr>
</table>

  <div align="center">
        
    <table class="table">
      <thead>
        <th>DENTE</th>
        <th>DESCRIÇÃO</th>
      </thead>
      <tbody>

        <?php

          $query = mysqli_query($conn, "SELECT dente, descricao FROM odontograma WHERE idPaciente='$_GET[codigo]' ORDER BY dente ASC")or die(mysqli_error());

          while($resul = mysqli_fetch_array($query))
          {
            echo "<tr><td>$resul[dente]</td><td>".utf8_encode($resul[descricao])."</td></tr>";
          }

        ?>

      </tbody>
    </table>

  </div>

<script>
window.print();
</script>
<?php
    include "../timbre_foot.php";
?>
