<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	$sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = addslashes($_GET['search']);
        $suggest_query = mysqli_query($conn, "SELECT * FROM honorarios WHERE procedimento like '".$search."%' ORDER BY codigo LIMIT 8");
        while($suggest = mysqli_fetch_array($suggest_query)) {
            $valor_particular = encontra_valor('honorarios_convenios', 'codigo_convenio = 1 AND codigo_procedimento', $suggest['codigo'], 'valor');
            $valor_convenio   = encontra_valor('honorarios_convenios', 'codigo_convenio = '.$_GET['codigo_convenio'].' AND codigo_procedimento', $suggest['codigo'], 'valor');
            echo $suggest['codigo'].' |.:.| '.$suggest['procedimento'].' |.:.| '.$valor_particular.' |.:.| '.$valor_convenio."\n";
        }
    }
?>
