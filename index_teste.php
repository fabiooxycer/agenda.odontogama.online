<?php
	include "lib/config.inc.php";
    if(!$install) {
        header('Location: ./configurador.php');
    } else {
        //@unlink('./configurador.php');
    }
	include "lib/func.inc.php";
	include "lib/classes.inc.php";
	require_once 'lang/'.$idioma.'.php';
	
    if(!checklog())
    {
        include("login.php");
        exit;
    }

    //header("Content-type: text/html; charset=ISO-8859-1", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gerenciador Clínico Odontológico Smile Odonto - Administração Odontológica Em Suas Mãos</title>
<link rel="SHORTCUT ICON" href="favicon.ico">
<link href="css/smile.cssw" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="lib/script.js.php"></script>
<script language="javascript" type="text/javascript" src="lib/ajax_search.js"></script>
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/bootstrap.min.js" type="text/javascript"></script>

</head>
<body>
  <input type="hidden" id="ScriptID" value="0" />
  
    <?php include "menu.php"; ?>
    <br />

<div class="conteudo" id="conteudo"></div>
  
  <nav class="navbar navbar-default" style="border-top:1px solid #C7C7C7;position: fixed;bottom: 0px;margin:0;width:100%;background:#E0E0E0;border-radius:0;\">
    <center>
    <div class="navbar-header" style="margin: 0 auto;">
      <span class="navbar-brand" style="font-size: 9pt;"><b>GCO V1.1 - <?php echo date("Y"); ?></b></span>
    </div>
    </center>
  </nav>

</body>
</html>
