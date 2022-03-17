<?php
  
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

    $sistema = new sistema();
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);
	//header("Content-type: text/html; charset=ISO-8859-1", true);

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = addslashes($_GET['search']);
        $suggest_query = mysqli_query($conn, "SELECT codigo, nome FROM pacientes WHERE nome like '".$search."%' OR codigo = '".$search."' ORDER BY nome LIMIT 5");
        while($suggest = mysqli_fetch_array($suggest_query)) {
            echo utf8_encode($suggest['nome']).' - '.$suggest['codigo']."\n";
        }
    }
?>
