
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Gerenciado Odontológico - Login</title>
		<link rel="stylesheet" href="css/login.css">


		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/cycle.js"></script>

		<link rel="stylesheet" href="css/bootstrap.min.css">
  		<script src="js/bootstrap.min.js"></script>

		<script type="text/javascript">

		$(function(){

			$("#usr").focus();

			$('#fundo').cycle({
	        	fx: 'fade',
	        	speed: 4000,
	        	timeout: 3000
	    	});

			$("#btLogin").click(function(){

				var usuario = $("#usr").val();
				var senha = $("#pwd").val();

				if(usuario == "")
				{
					$("#erro").fadeIn(500);
					$("#erro div").html("<strong>Atenção!</strong>  Informe seu nome de usuário.");
					$("#usr").focus();
					return false;
				}

				if(senha == "")
				{
					$("#erro").fadeIn(500);
					$("#erro div").html("<strong>Atenção!</strong>  Informe sua senha.");
					$("#pwd").focus();
					return false;
				}

				$("#erro").fadeOut(500);

				$.ajax({
	  				method: "POST",
	  				url: "wallpapers/index_ajax.php",
	  				data: { usuario: usuario, senha: senha, login: '' }
				}).done(function( msg ){
					if(msg == ""){
    					$("#erro").hide();
    					$("#sucesso").fadeIn(500);
    					setInterval(window.location.reload(), 3000);
    					return false;
    				}else{
    					$("#erro").fadeIn(500);
    				}
  				});

  			return false;

			});

		});

		</script>

		<title></title>
	</head>

	<body style="background: #133248;">
		<img src="http://www.familiariodontologia.com/wp-content/uploads/2014/05/novo-sorriso.jpg" style="position: absolute;top:0;left:0;width:100%;height:100%">
		<!--<img src="/meuSite/img/logo.png" style="z-index:1;position:absolute;top:10px;left:10px;width:170px;">-->
		<table id="centro">

			<tr>
				<td>
					
					<div id="container">

						<div class="alert alert-success" id="sucesso">
  							<strong>Sucesso!</strong> Aguarde, você seja redirecionado...
						</div>

						<div class="alert alert-danger" id="erro">
  							<div><strong>Atenção!</strong>  Usuário ou senha inválidos.</div>
						</div>

						<form role="form">
	    					<div class="form-group">
						      <label for="email">Usuário</label>
						      <div class="input-group">
						      	<span class="input-group-addon">
						      		<i class="glyphicon glyphicon-user"></i>
						      	</span>
						      	<input type="text" class="form-control" id="usr">
						      </div>
						    </div>
						    <div class="form-group">
						      <label for="pwd">Senha</label>
						      <div class="input-group">
						      	<span class="input-group-addon">
						      		<i class="glyphicon glyphicon-lock"></i>
						      	</span>
						      	<input type="password" class="form-control" id="pwd">
						      </div>
						    </div>
						    <div class="checkbox">
						      <!--<label><input type="checkbox">Lembrar minha senha</label>--><br>
						    </div>
	    					<button type="button" class="btn btn-default" id="btLogin">Entrar</button>
	  					</form>
	  				</div>
				</td>
			</tr>
		</table>

	</body>
</html>