<?php
	if(!isset($_GET['idioma'])) {
        $_GET['idioma'] = 'pt_br';
    }
	//include "lib/config.inc.php";
	require_once('lang/'.$_GET['idioma'].'.php');
	$motivo = $_GET['motivo'];

	switch($motivo)
	{
		case "faltaPagamento":
			$nMotivo = "Falta da pagamento";
			/*mysql_connect($server, $user, $pass) or die(mysql_error());

    		mysql_select_db($bd)or die(mysql_error());

    		$licenca = mysql_query("SELECT chave FROM dados_clinica")or die(mysql_error());

    		$getLicenca = mysql_fetch_row($licenca);

    		$cURL = curl_init("odonto/validar.php?licenca=".$getLicenca[0]);
    
    		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true );
    
    		$resultado = curl_exec($cURL);*/
			break;

		case "duplicidade":
			$nMotivo = "Duplicidade de licença";
			break;

		default:
			$nMotivo = "Desconhecido";
			break;
	}

?>

<html>
	<head>
    <title>Verificar Licença</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href="css/smile.css" rel="stylesheet" type="text/css" />
<link href="css/smile.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">

		$(function(){

		$("#enviarauto").click(function(){
				var cnpj = $("#cnpj").val();
				$.ajax({
	  				method: "POST",
	  				url: "odonto/validar.php",
	  				data: { cnpj: cnpj,
					validarauto: '' }
				}).done(function(msg){
						$("#sucesso").fadeIn(500);
						setTimeout(function () {
       window.location.href = "/odonto"; }, 2000);
    					$("#erro").hide();
    					return false;
  				});
  			return false;

			});
			
			$("#enviarchave").click(function(){

				var chave = $("#chave").val();

				if(chave == "")
				{
					$("#erro").fadeIn(500);
					$("#erro div").html("<strong>Atenção!</strong>  Informe sua chave de acesso.");
					return false;
				}

				$.ajax({
	  				method: "POST",
	  				url: "odonto/validar.php",
	  				data: { licenca: chave, validar: '' }
				}).done(function(msg){
						$("#sucesso").fadeIn(500);
						setTimeout(function () {
       window.location.href = "/odonto"; }, 2000);
    					$("#erro").hide();
    					return false;
  				});
  			return false;

			});

		});

		</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
#sem td{
  border-top: 0;
}
-->
</style></head>

<body>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
              <form role="form"><fieldset>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table" id="sem">
 <tr>
    <td>              
             <fieldset>
              <legend><strong><?php echo $LANG['config']['account_verification']?></strong> </legend>
                <p><?php if($motivo == 'invalida') echo 'Sua Chave é Inválida!'; else echo 'Sua Chave Expirou!'; ?></p>
                <br />
                <p align="center">                            
                            <input name="send" type="submit" class="btn btn-primary" id="enviarauto" value="Verifique Chave Automaticamente" />
                        </p>
                   <br /><p align="center">Ou</p> 
                  <span class="texto"><font<?php echo $r[0]?>>* <?php echo $LANG['config']['insert_key']?>:</font><br />
                  <input name="chave" type="text" class="form-control" id="chave" />
                  <input name="cnpj" style="visibility:hidden" type="text" value="<?php echo $_GET['cnpj']; ?>" class="form-control" id="cnpj" />
                 </span>
            </p>
            </fieldset>
                      <div class="sobre" id="div4">
                          <p align="center">                            
                            <input name="send" type="submit" class="btn btn-primary" id="enviarchave" value="Verificar Chave" />
                        </p>
                      <div class="alert alert-danger" id="erro" style="display:none;">
  							<div></div>
                      </div>
                      <div class="alert alert-success" id="sucesso" style="display:none;">
  							<strong>Sucesso!</strong> Aguarde, você seja redirecionado...
						</div>
    </div>
    </div></form></td>
  </tr>
</table>
</html>