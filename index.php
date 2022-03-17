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
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Gerenciador odontol&oacute;gico</title>


<script language="javascript" type="text/javascript" src="lib/script.js.php"></script>
<script language="javascript" type="text/javascript" src="lib/ajax_search.js"></script>
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/responsivo.css">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/jquery-ui.css" />


<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/mask.js" type="text/javascript"></script>
<script type="text/javascript" src="js/nav.js"></script>

<script type="text/javascript" src="js/mascara.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<!-- Sweet Alert -->
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

<!-- Sweet alert -->
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>




<script type="text/javascript">

setInterval("enviarSMS()", 60000); // tenta enviar sms de 1 em 1 minuto.

function enviarSMS() // função de envio de sms.
{
    $.get("sms.php").done(function(dados){

        if(dados.status == "ja_enviado")
        {
            console.log("SMS já enviado hoje!");
        }

    });
}

$(function(){

    $("#logo").fadeOut(0);
    $( "#menu" ).animate({
    
    top: "+=80",
    //height: "toggle"
  }, 1000, function() {
    $("#logo").fadeIn(1500);
  });

    $("div#menu ul li").click(function(){
        $("div#menu ul li").attr("class", "collapsed");
        $(this).attr("class", "collapsed active");
    });

 });  

</script>

<style type="text/css">
body{
    font-family: "Roboto", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
}
.table tr td{border-top: 1px solid transparent !important;}

.table{margin-bottom:0;}

#in{box-shadow: 4px 5px 8px #B9B9B9;}

.dropdown: hover-menu .dropdown { 
display: block; 
}
#conteudo{
    margin-left: 16%;
    width: 84%;
    margin-top: -20px;
}
#painel{
    float: left; 
    width: 100%;
    height: 100%; 
}
</style>

</head>
<body style="background:rgba(230, 228, 228, 0.33);">
  <input type="hidden" id="ScriptID" value="0" />
  
    <?php include ("menu.php"); ?><br><br><br><br>


    <div style="position:fixed;top:0;left:0;width:100%;height:40px;background:#4d7eb9;z-index:99999;">
        <!--<img src="/meusite/img/logo.png" style="width:210px; margin-top:10px;margin-left: 11px;">-->
        <div style="float: right;padding: 11px;font-weight:bold;color:#fff;"><?php echo "Bem vindo ".$_SESSION[nome_user]; ?></div>
    </div>

<div class="container" id="conteudo">

    <!-- PAINEL TELA PRINCIPAL -->
    <div id="painel">
        <div class="panel panel-default">
            <div class="panel-heading"><b>Sistema odontologico</b></div>
            <div class="panel-body">

                <!-- alerta de perigo -->
                <div class="alert alert-danger">
                    <strong><span class="glyphicon glyphicon-exclamation-sign"></span> Aviso!</strong> alerta de perigo.
                </div>

                <!-- alerta de informação -->
                <div class="alert alert-info">
                    <strong><span class="glyphicon glyphicon-info-sign"></span> Aviso!</strong> alerta de informação.
                </div>

                <!-- alerta de atenção -->
                <div class="alert alert-warning">
                    <strong><span class="glyphicon glyphicon-asterisk"></span> Aviso!</strong> alerta de atenção.
                </div>

                <!-- alerta de sucesso -->
                <div class="alert alert-success">
                    <strong><span class="glyphicon glyphicon-ok"></span> Aviso!</strong> alerta de sucesso.
                </div>

                <!-- imagem do painel -->
                <center>
                </center>


            </div>
        </div>
    </div>
    <!-- #################### -->

    
           


    

</div><br><br>
  
  <!--<nav id="rodape" class="navbar navbar-default" style="border-top:1px solid #C7C7C7;position: fixed;z-index:999;bottom: 0px;margin:0;width:100%;background:#E0E0E0;border-radius:0;\">
    <center>
    <div class="navbar-header" style="margin: 0 auto;">
      <span class="navbar-brand" style="font-size: 9pt;"><b><?php echo date("Y"); ?></b></span>
    </div>
    </center>
  </nav>-->

</body>
</html>
