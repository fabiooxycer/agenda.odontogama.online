<?php
	
	session_start();

	mysql_connect("localhost", "root", "") or die(mysql_error());

	mysql_select_db("openlusimed");

	$usuario = $_POST['usuario'];
	$senha = $_POST['senha'];

	$validar = mysql_fetch_row(mysql_query("SELECT * FROM wt_users WHERE name='$usuario' AND password='$senha'"));

	if($validar[0] == "")
	{
		echo "erro";
		exit;
	} else {

		echo "sucesso";
		$_SESSION["uid"] = $validar[0];
		$_SESSION["name"] = $validar[1];
		$_SESSION["RealName"] = $validar[4];
		$_SESSION["UserName"] = $validar[6];
		$_SESSION["email"] = $validar[5];
		$_SESSION["error"] = -1;
      	$_SESSION["class"] = $validar[3];
		$_SESSION["logged"] = true;
		$_SESSION['session'] = 1;
	}

?>