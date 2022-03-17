<?php
	mysql_connect($server, $user, $pass) or die(mysql_error());

    mysql_select_db($bd)or die(mysql_error());

    $licenca = mysql_query("SELECT * FROM dados_clinica")or die(mysql_error());

    $getLicenca = mysql_fetch_assoc($licenca);

    $cURL = curl_init("http://localhost:7777/odonto/odonto/validar.php?licenca=$getLicenca[chave]&cnpj=$getLicenca[cnpj]");
    
    curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true );
    
    $resultado = curl_exec($cURL);

    

    if($resultado != "sucesso" && $_SERVER['PHP_SELF'] != "/insereLicenca.php")
    {

        if($resultado == "") $resultado = "conexao";
        Header("Location: insereLicenca.php?motivo=".$resultado);
        exit;
    }


    
	?>