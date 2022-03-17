<?php

header("Content-type: text/html; charset=UTF-8", true);

	//$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
class barras
{
	function geraCodigoBarra($numero){
		$fino = 1;
		$largo = 3;
		$altura = 50;
		
		$barcodes[0] = '00110';
		$barcodes[1] = '10001';
		$barcodes[2] = '01001';
		$barcodes[3] = '11000';
		$barcodes[4] = '00101';
		$barcodes[5] = '10100';
		$barcodes[6] = '01100';
		$barcodes[7] = '00011';
		$barcodes[8] = '10010';
		$barcodes[9] = '01010';
		
		for($f1 = 9; $f1 >= 0; $f1--){
			for($f2 = 9; $f2 >= 0; $f2--){
				$f = ($f1*10)+$f2;
				$texto = '';
				for($i = 1; $i < 6; $i++){
					$texto .= substr($barcodes[$f1], ($i-1), 1).substr($barcodes[$f2] ,($i-1), 1);
				}
				$barcodes[$f] = $texto;
			}
		}
		
		echo '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		
		echo '<img ';
		
		$texto = $numero;
		
		if((strlen($texto) % 2) <> 0){
			$texto = '0'.$texto;
		}
		
		while(strlen($texto) > 0){
			$i = round(substr($texto, 0, 2));
			$texto = substr($texto, strlen($texto)-(strlen($texto)-2), (strlen($texto)-2));
			
			if(isset($barcodes[$i])){
				$f = $barcodes[$i];
			}
			
			for($i = 1; $i < 11; $i+=2){
				if(substr($f, ($i-1), 1) == '0'){
					$f1 = $fino ;
				}else{
					$f1 = $largo ;
				}
				
				echo 'src="imagens/p.gif" width="'.$f1.'" height="'.$altura.'" border="0">';
				echo '<img ';
				
				if(substr($f, $i, 1) == '0'){
					$f2 = $fino ;
				}else{
					$f2 = $largo ;
				}
				
				echo 'src="imagens/b.gif" width="'.$f2.'" height="'.$altura.'" border="0">';
				echo '<img ';
			}
		}
		echo 'src="imagens/p.gif" width="'.$largo.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/p.gif" width="1" height="'.$altura.'" border="0" />';
	}
}

class sms
{
	function verificarDia()
	{
		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$hoje = date("Y-m-d");

		$validar = mysqli_fetch_row(mysqli_query($conn, "SELECT id FROM tb_sms WHERE data='$hoje'"));

		if($validar[0] != "") return true;

	}

	function disparar($destino, $paciente, $tp, $dataConsulta="", $valor="", $horaConsulta)
	{

		$paciente = explode(" ", $paciente);

		$celular = str_replace("(", "", $destino);
		$celular = str_replace(")", "", $celular);
		$celular = str_replace("-", "", $celular);

		$nomeClinica = "odonto pirabeiraba";

		$msg["cob"] = "Olá ".$paciente[0].", lembre-se, existe um débito no valor de R$ ".str_replace(".", ",", $valor)." referente a ".$nomeClinica." com vencimento em ".date("d/m/Y", strtotime($dataConsulta));
		$msg["ani"] = "Olá ".$paciente[0].", a ".$nomeClinica." deseja a você um feliz aniversário.";
		$msg["con"] = "Olá ".$paciente[0].", lembre-se, você tem uma consulta marcada para o dia ".date("d/m/Y", strtotime($dataConsulta))." as ".date("H:i", strtotime($horaConsulta))." no consultório ".$nomeClinica;


		// URL que será feita a requisição
		$urlSms = "https://api.directcallsoft.com/sms/send";

		// Numero de origem
		$origem = "5562992139211";

		// Numero de destino
		$destino = "55".$celular;

		// Tipo de envio, podendo ser "texto" ou "voz"
		$tipo = "texto";

		//echo $msg[$tp];

		// Texto a ser enviado
		$texto = $msg[$tp];

		// Incluir o access_token
		$access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkuZGlyZWN0Y2FsbHNvZnQuY29tIiwiYXVkIjoiMTkyLjE2OC4yMzMuODIiLCJpYXQiOjE0ODYxNTU2NzUsIm5iZiI6MTQ4NjE1NTY3NSwiZXhwIjoxNDg2MTU5Mjc1LCJjbGllbnRfb2F1dGhfaWQiOiI1MDQ2NSJ9.Cu2LecZz4HRPsv7cXFCJQEdckP4Cgkqx2HUdgvSSeAk";

		// Formato do retorno, pode ser JSON ou XML
		$format = "json";

		// Dados em formato QUERY_STRING
		$data = http_build_query(array('origem'=>$origem, 'destino'=>$destino, 'tipo'=>$tipo, 'access_token'=>$access_token, 'texto'=>$texto));

		$ch = 	curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlSms);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$return = curl_exec($ch);
		
		curl_close($ch);
		
		// Converte os dados de JSON para ARRAY
		$dados = json_decode($return, true);

		if($dados["status"] == "ok") // apenas armazena no banco de dados se o SMS realmente for enviado!
		{

			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			$data = date("Y-m-d");
			$hora = date("H:i:s");

			$msg = $msg[$tp];

			mysqli_query($conn, "INSERT INTO tb_sms (paciente, tipo, data, mensagem, hora) VALUES ('$paciente[0]', '$tp', '$data', '$msg', '$hora')")or die(mysqli_error($conn));

		}

		//var_dump($dados);

		return $dados;
	}
}

class dados
{
	public function formatarCPF($valor)
	{
		$cpf = "";

		$cpf.=substr($valor, 0, 3).".";
		$cpf.=substr($valor, 3, 3).".";
		$cpf.=substr($valor, 6, 3)."-";
		$cpf.=substr($valor, 9, 2);

		return $cpf;
	}

	public function obterMes($mes)
	{
		$meses = Array();

		$meses[1] = "JANEIRO";
		$meses[2] = "FEVEREIRO";
		$meses[3] = "MARÇO";
		$meses[4] = "ABRIL";
		$meses[5] = "MAIO";
		$meses[6] = "JUNHO";
		$meses[7] = "JULHO";
		$meses[8] = "AGOSTO";
		$meses[9] = "SETEMBRO";
		$meses[10] = "OUTUBRO";
		$meses[11] = "NOVEMBRO";
		$meses[12] = "DEZEMBRO";

		return $meses[$mes];

	}
}

class moeda
{
	function formatar($numero)
	{
		$quantidade = strlen($numero);

		$moeda == "";
		$centavos = "0";

		$separar = explode(".", $numero);
		$quantidade = strlen($numero);

		if($separar[1] == "") return $numero.",00";

		if(strlen($separar[1]) == 1) return str_replace(".", ",", $numero)."0"; 

		if($separar[1] != "") return str_replace(".", ",", $numero); 


	}

}
class OS {

	function getParcelasDetalhes($ordem)
	{
		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$ordem = mysqli_real_escape_string($conn, $ordem);

		$retorno = array();

		$i = 0;

		$query = $conn->query("SELECT c.*, p.nome, p.cpf FROM contasreceber AS c INNER JOIN pacientes AS p ON c.paciente=p.codigo WHERE c.ordem='$ordem' AND c.status='0' ORDER BY c.codigo ASC")or die(mysqli_error($conn));

		echo $dados[0];

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[$i] = $resul;
			$i++;
		}


		return $retorno;

	}

	function getParcelas($ordem) 
	{
		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$ordem = mysqli_real_escape_string($conn, $ordem);

		$retorno = array();

		$i = 0;

		$query = mysqli_query($conn, "SELECT * FROM parcelas_ordem WHERE id_ordem='$ordem'");

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[$i] = $resul;
			$i++;
		}

		return $retorno;
	}

	function addParcelas($dataParcela, $valor, $ordem, $parcela, $qtParcela, $comissao, $tipo_pagamento = "0")
	{
		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$dataParcela = mysqli_real_escape_string($conn, $dataParcela);
		$valor = mysqli_real_escape_string($conn, $valor);
		$ordem = mysqli_real_escape_string($conn, $ordem);

		$parcela++;

		$descricao = "Parcela $parcela/$qtParcela referente a OS Nº $ordem";

		$os = new os();

		$dadosOrdem = $os->carregar($ordem);

		$idPaciente = $dadosOrdem[0]["paciente"];
		$idDentista = $dadosOrdem[0]["dentista"];

		mysqli_query($conn, "INSERT INTO parcelas_ordem (id_ordem, data, valor, status, comissao) VALUES ('$ordem', '$dataParcela', '$valor', '0', '$comissao')")or die(mysqli_error($conn));
		mysqli_query($conn, "INSERT INTO contasreceber (datavencimento, descricao, valor, datapagamento, paciente, dentista, ordem, comissao, forma_pagamento) VALUES ('$dataParcela', '$descricao', '$valor', NULL, '$idPaciente', '$idDentista', '$ordem', '$comissao', '$tipo_pagamento')")or die(mysqli_error($conn));
	}

	function autorizarPagamento($cod) {

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$cod = mysqli_real_escape_string($conn, $cod);

		if(mysqli_query($conn, "UPDATE tb_comissao SET status='1' WHERE id='$cod'"))
		{
			$pacientes = new TPacientes();
			$dentistas = new TDentistas();

			$dados = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tb_comissao WHERE id='$cod'"));

			$dadosPaciente = $pacientes->LoadPaciente($dados[id_paciente]);
			$dadosDentista = $dentistas->LoadDentista($dados[id_dentista]);

			$data = date("Y-m-d");
			$valor = $dados[valor];
			$descricao = "Pagamento da comissao referente a OS ".$dados[id_ordem].", Paciente: ".$pacientes->RetornaDados('nome').", Dentista: ".$dentistas->RetornaDados('nome');

			mysqli_query($conn, "INSERT INTO caixa (data, valor, descricao, dc) VALUES ('$data', '$valor', '$descricao', '-')");
			return true;
		}

	}

	function statusComissao($cod)
	{
		$status[0] = "<b><span style='color:#0985a5;' title='Pagamanto de comissão não realizado!'><span class='	glyphicon glyphicon-info-sign'></span> Não Pago</span></b>";
		$status[1] = "<span style='color:#43a200;'><span class='glyphicon glyphicon-ok-sign'></span> Pago</span>";

		return $status[$cod];
	}

	function calcularComissao($ordem, $tipo, $valorEntrada) {

		$os = new os();

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$dados = $os->carregar($ordem);

		$idDentista = $dados[0][dentista];
		$idPaciente = $dados[0][paciente];

		$dentistas = new TDentistas();
		$dentistas->LoadDentista($idDentista);

		$data = date("Y-m-d H:i:s");

		$valor = ($dados[0][total]*$dentistas->RetornaDados('comissao'))/100;

		if($tipo == "entrada") $valor = ($valorEntrada*$dentistas->RetornaDados('comissao'))/100;

		mysqli_query($conn, "INSERT INTO tb_comissao (id_ordem, id_dentista, id_paciente, valor, data, status) VALUES ('$ordem','$idDentista', '$idPaciente', '$valor', '$data', '0')");

	}

	function carregarGanhos($filtro, $texto, $dataI = "", $dataF = "") {

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$retorno = array();

		$i = 0;

		if($filtro == "") $query = mysqli_query($conn, "SELECT * FROM tb_comissao ORDER BY id DESC");

		if($dataI == "") $dataI = "1970-01-01 00:00:00";
		if($dataF == "1970-01-01") $dataF = "2999-01-01 00:00:00";

		if($filtro == "ordem") $query = mysqli_query($conn, "SELECT * FROM tb_comissao WHERE id_ordem='$texto'");

		if($filtro == "comissao") $query = mysqli_query($conn, "SELECT * FROM tb_comissao WHERE id='$texto'");

		if($filtro == "paciente")
		{
			$query = mysqli_query($conn, "SELECT * FROM tb_comissao INNER JOIN pacientes ON pacientes.codigo=tb_comissao.id_paciente WHERE pacientes.nome LIKE '%$texto%' AND tb_comissao.data >= '$dataI' AND tb_comissao.data <= '$dataF' ORDER BY id DESC")or die(mysqli_error($conn));
			
		}

		if($filtro == "dentista")
		{

			$query = mysqli_query($conn, "SELECT * FROM tb_comissao INNER JOIN dentistas ON dentistas.codigo=tb_comissao.id_dentista WHERE dentistas.nome LIKE '%$texto%' AND tb_comissao.data >= '$dataI' AND tb_comissao.data <= '$dataF' ORDER BY id DESC")or die(mysqli_error($conn));
		}

		if($filtro == "sql")
		{
			$query = mysqli_query($conn, $texto);
		}

		//echo "SELECT * FROM tb_comissao INNER JOIN pacientes ON pacientes.codigo=tb_comissao.id_paciente WHERE pacientes.nome LIKE '%$texto%' AND tb_comissao.data >= '$dataI' AND tb_comissao.data <= '$dataF' ORDER BY id DESC";

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[$i] = $resul;
			$i++;
		}

		return $retorno;

	}

	function carregarComissao($filtro, $texto, $dataI = "", $dataF = "") {

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$retorno = array();

		$i = 0;

		if($filtro == "") $query = mysqli_query($conn, "SELECT * FROM tb_comissao ORDER BY id DESC");

		if($dataI == "") $dataI = "1970-01-01 00:00:00";
		if($dataF == "1970-01-01") $dataF = "2999-01-01 00:00:00";

		if($filtro == "ordem") $query = mysqli_query($conn, "SELECT * FROM tb_comissao WHERE id_ordem='$texto'");

		if($filtro == "comissao") $query = mysqli_query($conn, "SELECT * FROM tb_comissao WHERE id='$texto'");

		if($filtro == "paciente")
		{
			$query = mysqli_query($conn, "SELECT * FROM tb_comissao INNER JOIN pacientes ON pacientes.codigo=tb_comissao.id_paciente WHERE pacientes.nome LIKE '%$texto%' AND tb_comissao.data >= '$dataI' AND tb_comissao.data <= '$dataF' ORDER BY id DESC")or die(mysqli_error($conn));
			
		}

		if($filtro == "dentista")
		{

			$query = mysqli_query($conn, "SELECT * FROM tb_comissao INNER JOIN dentistas ON dentistas.codigo=tb_comissao.id_dentista WHERE dentistas.nome LIKE '%$texto%' AND tb_comissao.data >= '$dataI' AND tb_comissao.data <= '$dataF' ORDER BY id DESC")or die(mysqli_error($conn));
		}

		if($filtro == "sql")
		{
			$query = mysqli_query($conn, $texto);
		}

		//echo "SELECT * FROM tb_comissao INNER JOIN pacientes ON pacientes.codigo=tb_comissao.id_paciente WHERE pacientes.nome LIKE '%$texto%' AND tb_comissao.data >= '$dataI' AND tb_comissao.data <= '$dataF' ORDER BY id DESC";

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[$i] = $resul;
			$i++;
		}

		return $retorno;

	}

	function atualizar($idPaciente, $idDentista, $total, $id) {

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			//echo $idPaciente." ".$idDentista." ".$total;

		if(mysqli_query($conn, "UPDATE tb_ordens SET paciente='$idPaciente', dentista='$idDentista', total='$total' WHERE id='$id'"))
		{

			mysqli_query($conn, "DELETE FROM tb_ordens_procedimentos WHERE id_ordem='$id'");

			return true;
		}

	}

	function atualizarProcedimentos($id, $valor, $tag, $procedimento) {

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		mysqli_query($conn, "INSERT INTO tb_ordens_procedimentos (id_ordem, valor, tag, procedimento) VALUES ('$id', '$valor', '$tag', '$procedimento')")or die(mysqli_error($conn));

	}

	function carregar($id) {

		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$query = mysqli_query($conn, "SELECT * FROM tb_ordens WHERE id='$id'");

		$retorno = array();
		$i = 0;

		$dentista = new TDentistas();
		$paciente = new TPacientes();

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[0] = $resul;

			$dadosDentista = $dentista->LoadDentista($resul[dentista]);
			$dadosPaciente = $paciente->LoadPaciente($resul[paciente]);

			$retorno[0][nomePaciente] = $paciente->RetornaDados("nome");
			$retorno[0][nomeDentista] = $dentista->RetornaDados("nome");

			$queryProc = mysqli_query($conn, "SELECT * FROM tb_ordens_procedimentos WHERE id_ordem='$id'");

			while($procs = mysqli_fetch_array($queryProc))
			{
				$retorno[0][procedimentos][$i] = $procs;
				$i++;
			}

		}

		return $retorno;

	}

	function alterarStatus($id, $status, $valorTotal, $tipo, $modoPagamento) {

		//echo "OK";

		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		if(mysqli_query($conn, "UPDATE tb_ordens SET status='$status', modo_pagamento='$modoPagamento' WHERE id='$id'")) {

			$pacientes = new TPacientes();
			$dentistas = new TDentistas();

			$conta = new TContas('clinica', 'receber');

			$dados = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tb_ordens WHERE id='$id'"))or die(mysqli_error($conn));

			$pacientes->LoadPaciente($dados["paciente"]);
			$dentistas->LoadDentista($dados["dentista"]);

			//echo $dentistas->RetornaDados("nome");

			
			$data = date("Y-m-d");

			if($tipo == "aguardando")
			{
				return true;
				exit;
			}
			
			if($tipo == "pagamento")
			{

				$valor = $dados[total];
				$descricao = "Pagamento da OS $id, Paciente: ".$pacientes->RetornaDados('nome').", Dentista: ".$dentistas->RetornaDados('nome');
				$descricao_contas = "Pagamento da OS $id";

				$conta->SetDados('datavencimento', date("Y-m-d"));
				$conta->SetDados('descricao', $descricao_contas);
				$conta->SetDados('valor', $valor);
				$conta->SetDados('datapagamento', date("Y-m-d"));
				$conta->SetDados('paciente', $dados["paciente"]);
				$conta->SetDados('dentista', $dados["dentista"]);
				$conta->SetDados('ordem', $id);
				$conta->SetDados('status', '1');
				$conta->SetDados('comissao', 'n');
				$conta->SetDados('forma_pagamento', $modoPagamento);

				$conta->SalvarNovo();
				$conta->Salvar();

			}

			if($tipo == "entrada")
			{

				$valor = $valorTotal;
				$descricao = "Pagamento de entrada da OS $id. - Paciente: ".utf8_encode($pacientes->RetornaDados('nome')).", Dentista: ".utf8_encode($dentistas->RetornaDados('nome'));
				$descricao_contas = "Pagamento de entrada da OS $id";

				$conta->SetDados('datavencimento', date("Y-m-d"));
				$conta->SetDados('descricao', $descricao_contas);
				$conta->SetDados('valor', $valor);
				$conta->SetDados('datapagamento', date("Y-m-d"));
				$conta->SetDados('paciente', $dados["paciente"]);
				$conta->SetDados('dentista', $dados["dentista"]);
				$conta->SetDados('ordem', $id);
				$conta->SetDados('status', '1');
				$conta->SetDados('comissao', 'n');
				$conta->SetDados('forma_pagamento', $modoPagamento);

				$conta->SalvarNovo();
				$conta->Salvar();
			}



			mysqli_query($conn, "INSERT INTO caixa (data, valor, descricao, dc, modo_pagamento) VALUES ('$data', '$valor', '$descricao', '+', '$modoPagamento')")or die(mysqli_error($conn));
			
			return true;
		}

	}

	function excluir($id) {

		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		if(mysqli_query($conn, "DELETE FROM tb_ordens WHERE id='$id'"))
		{
			mysqli_query($conn, "DELETE FROM tb_ordens_procedimentos WHERE id_ordem='$id'");
			return true;
		}

	}

	function getModoPagamento($cod)
	{
		/*

		0 = Não autorizado;
		1 = Dinheiro
		2 = Cartão de débito
		3 = Cheque
		4 = Promissória
		5 = Boleto
		6 = Cartão de crédito

		*/

		switch ($cod) {
			case 0:
			return '-';
			break;
			
			case 1:
			return '<div style="border:1px solid #1dad03; width:25px;height:25px;float:left;color:#fff;font-weight:bold;margin-right:5px;text-align:center;line-height: 23px;background: #10d428 !important;border-radius: 3px;margin-top:4px;" title="Dinheiro">DI</div>';
			break;

			case 2:
			return '<div style="border:1px solid #a58a05; width:25px;height:25px;float:left;color:#fff;font-weight:bold;margin-right:5px;text-align:center;line-height: 23px;background: #cc8e04 !important;border-radius: 3px;margin-top:4px;" title="Cartão de débito">CD</div>';
			break;

			case 3:
			return '<div style="border:1px solid #1eb4fb; width:25px;height:25px;float:left;color:#fff;font-weight:bold;margin-right:5px;text-align:center;line-height: 23px;background: #0dddf3 !important;border-radius: 3px;margin-top:4px;" title="Cheque">CH</div>';
			break;

			case 4:
			return '<div style="border:1px solid #f74cab; width:25px;height:25px;float:left;color:#fff;font-weight:bold;margin-right:5px;text-align:center;line-height: 23px;background: #fb7c9f;border-radius: 3px;margin-top:4px;" title="Promissória">PR</div>';
			break;

			case 5:
			return '<div style="border:1px solid #b75209; width:25px;height:25px;float:left;color:#fff;font-weight:bold;margin-right:5px;text-align:center;line-height: 23px;background: #ea6a0e;border-radius: 3px;margin-top:4px;" title="Boleto">BL</div>';
			break;

			case 6:
			return '<div style="border:1px solid #8f67f1; width:25px;height:25px;float:left;color:#fff;font-weight:bold;margin-right:5px;text-align:center;line-height: 23px;background: #b498f7 !important;border-radius: 3px;margin-top:4px;" title="Cartão de crédito">CC</div>';
			break;

			default:
			return "-";
			break;
		}
	}

	function getStatus($cod)
	{
		switch($cod) {
			case 0:
			return "Aguardando aprovação";
			break;

			case 1:
			return "Ordem de serviço paga";
			break;

			case 2:
			return "Paga parcialmente";
			break;

			case 3:
			return "Paga parcialmente";
			break;

			case 4:
			return "Aguardando compensação";
			break;

			default:
			break;
		}
	}

	function statusParcela($cod)
	{
		switch ($cod) {
			case 0:
			return "Não pago";
			break;

			case 1:
			return "Pago";
			break;
			
			default:
				# code...
			break;
		}
	}

	function encontrar($idPaciente, $idDentista, $procurar, $situacao, $pagamento, $dtInicial, $dtFinal)
	{
		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		$retorno = array();

		$complemento = "";

		if($idPaciente != "") $complemento = "AND paciente='$idPaciente'";
		if($idDentista != "") $complemento.= " AND dentista='$idDentista'";
		if($situacao != "") $complemento.= " AND status='$situacao'";
		if($pagamento != "") $complemento.= " AND modo_pagamento='$pagamento'";

		if($dtInicial != "")
		{
			$dataInicial = new dateTime(str_replace("/", "-", $dtInicial));
			$dataInicial = $dataInicial->format("Y-m-d");

			$complemento.= " AND data >= '$dataInicial'";
		}

		if($dtFinal != "")
		{
			$dataFinal = new dateTime(str_replace("/", "-", $dtFinal));
			$dataFinal = $dataFinal->format("Y-m-d");

			$complemento.= " AND data <= '$dataFinal'";
		}

		if($procurar != "")
		{
			$complemento = "AND id='$procurar'";
		}

		//echo "SELECT * FROM tb_ordens WHERE id!=0 $complemento";

		$query = $conn->query("SELECT * FROM tb_ordens WHERE id!=0 $complemento");

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[] = $resul;
		}

		return $retorno;

	}

	/*
	function encontrar($filtro, $texto, $dataI, $dataF) {


		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		if($filtro == "") $query = mysqli_query($conn, "SELECT * FROM tb_ordens ORDER BY id DESC, data ASC")or die(mysqli_error($conn));

		if($filtro == "ordem") $query = mysqli_query($conn, "SELECT * FROM tb_ordens WHERE id='$texto'");

		if($filtro == "paciente")
		{
			$query = mysqli_query($conn, "SELECT * FROM tb_ordens INNER JOIN pacientes ON pacientes.codigo=tb_ordens.paciente WHERE pacientes.nome LIKE '%$texto%' AND tb_ordens.data >= '$dataI' AND tb_ordens.data <= '$dataF' ORDER BY tb_ordens.data DESC")or die(mysqli_error($conn));
		}

		if($filtro == "dentista")
		{

			$query = mysqli_query($conn, "SELECT * FROM tb_ordens INNER JOIN dentistas ON dentistas.codigo=tb_ordens.dentista WHERE dentistas.nome LIKE '%$texto%' AND tb_ordens.data >= '$dataI' AND tb_ordens.data <= '$dataF' ORDER BY tb_ordens.data DESC")or die(mysqli_error($conn));
		}

		$retorno = array();
		$i = 0;

		while($resul = mysqli_fetch_array($query))
		{
			$retorno[$i] = $resul;
			$i++;
		}

		return $retorno;

	}*/

	function salvar($idPaciente, $idDentista, $total, $status) {

		$sistema = new sistema(); 
		$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

		$data = date("Y-m-d");

		if($status == "on") {
			$status = 1;
		}else{
			$status = 0;
		}

			//echo $idDentista." ".$idPaciente." ".$total." ".$status;


		if(mysqli_query($conn, "INSERT INTO tb_ordens (data, paciente, dentista, total, status) VALUES ('$data', '$idPaciente', '$idDentista', '$total', '$status')"))
		{
			return mysqli_insert_id($conn);
		}

	}

	function salvarProcedimentos($id, $valor, $tag, $procedimento) {

		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

		mysqli_query($conn, "INSERT INTO tb_ordens_procedimentos (id_ordem, valor, tag, procedimento) VALUES ('$id', '$valor', '$tag', '$procedimento')");

	}

	function getProcedimentos($txt)
	{

		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
		$query = mysqli_query($conn, "SELECT * FROM honorarios WHERE procedimento LIKE '%$txt%' OR codigo LIKE '%$txt%' ORDER BY procedimento ASC")or die(mysqli_error($conn));

		$i = 0;
		$retorno = array();

		while($resul = mysqli_fetch_array($query)){
			$valor = mysqli_fetch_row(mysqli_query($conn, "SELECT valor FROM honorarios_convenios WHERE codigo_procedimento='$resul[codigo]'"));

			$retorno[$i] = $resul;
			$retorno[$i][valor] = $valor[0];
			$i++;
		}

		return $retorno;
	}

}

class TEspecialidades {
	private $codigo;
	private $descricao;
	function __construct($intCodigo = "") {
		if($intCodigo != "") {
			$this->GetEspecContent($intCodigo);
		}
	}
	function GetEspecContent($intCodigo) {
		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
		$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `especialidades` WHERE `codigo` = '$intCodigo'"));
		if($row[codigo] != "") {
			$this->codigo = $row[codigo];
			$this->descricao = $row[descricao];
		}
	}
	function GetDescricao() {
		return $this->descricao;
	}
	function SetDescricao($strDescricao) {
		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
		$this->descricao = $strDescricao;
		mysqli_query($conn, "UPDATE `especialidade` SET `descricao` = '".$this->descricao."' WHERE `codigo` = '".$this->codigo."'");
	}
	function ChangeEspecialidade($intNovoCodigo) {
		$this->GetEspecContent($intCodigo);
	}
	function ListEspecialidades() {
		$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
		$i = 0;
		$query = mysqli_query($conn, "SELECT * FROM `especialidades` ORDER BY `descricao` ASC") or die(mysqli_error($conn));
		while($row = mysqli_fetch_array($query)) {
			$lista[$i][codigo] = $row[codigo];
			$lista[$i][descricao] = $row[descricao];
			$i++;
		}
		return $lista;
	}
}
	/**
	 * Classe dos dentistas das clínicas
	 *
	 */
	class TDentistas {
		private $dados;

		function buscarDentista($texto) {

			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$query = mysqli_query($conn, "SELECT * FROM dentistas WHERE nome LIKE '%$texto%' OR cpf LIKE '%$texto%' OR rg='$texto' ORDER BY nome ASC");

			$retorno = array();
			$i = 0;

			while($resul = mysqli_fetch_array($query))
			{
				$retorno[$i] = $resul;
				$i++;
			}

			return $retorno;

		}

		function LoadDentista($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `dentistas` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			if($this->dados[sexo] == "Masculino") {
				$this->dados[titulo] = "Dr.";
			} else {
				$this->dados[titulo] = "Dra.";
			}
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo' && $chave != 'titulo' && $chave != 'foto') {
					$sql = "UPDATE `dentistas` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'";
					mysqli_query($conn, $sql);
					echo mysqli_error($conn) ? $sql . ': ' . mysqli_error($conn) : '';
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			mysqli_query($conn, "INSERT INTO `dentistas` (`codigo`) VALUES ('0')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->dados['codigo'] = mysqli_insert_id($conn);
		}
		function ListDentistas($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `dentistas` ORDER BY `nome` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][codigo] = utf8_encode($row[codigo]);
				$lista[$i][nome] = utf8_encode($row[nome]);
				$lista[$i][cpf] = utf8_encode($row[cpf]);
				$lista[$i][email] = utf8_encode($row[email]);
				$lista[$i][cidade] = utf8_encode($row[cidade]);
				$lista[$i][estado] = utf8_encode($row[estado]);
				$lista[$i][conselho_tipo] = utf8_encode($row[conselho_tipo]);
				$lista[$i][conselho_estado] = utf8_encode($row[conselho_estado]);
				$lista[$i][conselho_numero] = utf8_encode($row[conselho_numero]);
				$lista[$i][telefone] = utf8_encode($row[telefone1]);
				$lista[$i][ativo] = $row[ativo];
				if($row[sexo] == "Masculino") {
					$lista[$i][titulo] = "Dr.";
				} else {
					$lista[$i][titulo] = "Dra.";
				}
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos pacientes da clínica
	 *
	 */
	class TPacientes {
		private $dados = array();
		private $codigo_anterior;

		function buscarPaciente($texto) {

			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			$query = mysqli_query($conn, "SELECT * FROM pacientes WHERE nome LIKE '%$texto%' OR cpf LIKE '%$texto%' OR rg='$texto' ORDER BY nome ASC");

			$retorno = array();
			$i = 0;

			while($resul = mysqli_fetch_array($query))
			{
				$retorno[$i] = $resul;
				$i++;
			}

			return $retorno;

		}

		function LoadAniversarios()
		{
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			$retorno = array();

			$query = $conn->query("SELECT * FROM pacientes");

			$hoje = date("d-m");

			$i=0;

			while($resul = mysqli_fetch_array($query))
			{
				$nascimento = new dateTime($resul["nascimento"]);
				$nascimento = $nascimento->format("d-m");

				if($nascimento == $hoje) // veririca se é dia do aniversário.
				{
					$retorno[$i] = $resul;
					$i++;
				}
			}

			return $retorno;

		}

		function LoadPaciente($intCodigo) {

			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `pacientes` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			//echo $this->dados[nome];
			$this->codigo_anterior = $this->dados[codigo];
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {

			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `pacientes` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->codigo_anterior."'");
				}
			}
			if($this->codigo_anterior != $this->dados[codigo]) {
				mysqli_query($conn, "UPDATE `pacientes` SET `codigo` = '".$this->dados[codigo]."' WHERE `codigo` = '".$this->codigo_anterior."'");
				$this->codigo_anterior = $this->dados[codigo];
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			mysqli_query($conn, "INSERT INTO `pacientes` (`codigo`) VALUES ('".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->codigo_anterior = $this->dados[codigo];
		}
		function ListPacientes($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `pacientes` ORDER BY `nome` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][nome] = $row[nome];
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][sexo] = $row[sexo];
				$lista[$i][cidade] = $row[cidade];
				$lista[$i][estado] = $row[estado];
				$lista[$i][endereco] = $row[endereco];
				$lista[$i][celular] = $row[celular];
				$i++;
			}
			return $lista;
		}
		function ProximoCodigo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_array(mysqli_query($conn, "SELECT `codigo` FROM `pacientes` ORDER BY `codigo` DESC LIMIT 1"));
			return($row[codigo] + 1);
		}
	}
	/**
	 * Classe dos Funcionários da clínica
	 *
	 */
	class TFuncionarios {

		private $dados;

		function LoadFuncionario($intCodigo) {

			//echo $intCodigo."OK";

			$sistema = new sistema();

			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `funcionarios` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			if($this->dados[sexo] == "Masculino") {
				$this->dados[titulo] = "Sr.";
			} else {
				$this->dados[titulo] = "Sra.";
			}
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `funcionarios` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			
			mysqli_query($conn, "INSERT INTO `funcionarios` (`codigo`) VALUES ('0')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->dados['codigo'] = mysqli_insert_id($conn);
		}
		function ListFuncionarios($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `funcionarios` ORDER BY `nome` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][nome] = $row[nome];
				$lista[$i][cpf] = $row[cpf];
				$lista[$i][funcao1] = $row[funcao1];
				$lista[$i][ativo] = $row[ativo];
				if($row[sexo] == "Masculino") {
					$lista[$i][titulo] = "Sr.";
				} else {
					$lista[$i][titulo] = "Sra.";
				}
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos Fornecedores da clínica
	 *
	 */
	class TFornecedores {
		private $dados;
		private $codigo_anterior;
		function LoadFornecedores($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `fornecedores` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			$this->codigo_anterior = $this->dados[codigo];
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {

				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `fornecedores` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->codigo_anterior."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$codigo = next_autoindex('fornecedores');
			mysqli_query($conn, "INSERT INTO `fornecedores` (`codigo`) VALUES ('".$codigo."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->codigo_anterior = $codigo;
		}
		function ListFornecedores($sql = "") {
			$i = 0;
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			if($sql == "") {
				$sql = "SELECT * FROM `fornecedores` ORDER BY `nomefantasia` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][nome] = $row[nomefantasia];
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][cidade_uf] = $row[cidade]."/".$row[estado];
				$lista[$i][telefone] = $row[telefone1];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos Laboratórios Clínicos
	 *
	 */
	class TLaboratorio {
		private $dados;
		private $codigo_anterior;
		function LoadLaboratorio($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `laboratorios` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			$this->codigo_anterior = $this->dados[codigo];
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `laboratorios` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->codigo_anterior."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$codigo = next_autoindex('laboratorios');
			mysqli_query($conn, "INSERT INTO `laboratorios` (`codigo`) VALUES ('".$codigo."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->codigo_anterior = $codigo;
		}
		function ListLaboratorios($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `laboratorios` ORDER BY `nomefantasia` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][nome] = $row[nomefantasia];
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][cidade_uf] = $row[cidade]."/".$row[estado];
				$lista[$i][telefone] = $row[telefone1];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos Convênios
	 *
	 */
	class TConvenio {
		private $dados;
		private $codigo_anterior;
		function LoadConvenio($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `convenios` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			$this->codigo_anterior = $this->dados[codigo];
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `convenios` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->codigo_anterior."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$codigo = next_autoindex('convenios');
			mysqli_query($conn, "INSERT INTO `convenios` (`codigo`) VALUES ('".$codigo."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->codigo_anterior = $codigo;
		}
		function ListConvenios($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `convenios` ORDER BY `nomefantasia` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][nome] = $row[nomefantasia];
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][cidade_uf] = $row[cidade]."/".$row[estado];
				$lista[$i][telefone] = $row[telefone1];
				$i++;
			}
			return $lista;
		}
	}

	/*$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://mdevsistema.ddns.net:7777/controle/login.php?hash=f0a13725f94af8ce929b93360195c538");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$resul = curl_exec($ch);

	if($resul == "login")
	{
		echo base64_decode("PGNlbnRlcj48c3BhbiBzdHlsZT0nZm9udC1mYW1pbHk6QXJpYWw7Jz5TaXN0ZW1hIGJsb3F1ZWFkbyBkZXZpZG8gZmFsdGEgZGUgcGFnYW1lbnRvLCBwb3IgZmF2b3IsIGVudHJlIGVtIGNvbnRhdG8gY29tIG8gYWRtaW5pc3RyYWRvciBkbyBzaXN0ZW1hIHBhcmEgcmVndWxhcml6YXIgYSBzaXR1YcOnw6NvLjwvc3Bhbj48L2NlbnRlcj4=");
		exit;
	}

	curl_close($ch);*/

	/**
	 * Classe do livro caixa da clínica e 
	 * dos funcionários
	 * 
	 */

	class TCaixa {
		private $dados;
		private $dbase;
		function __construct($strDBase = '') {
			if($strDBase != '') {
				$this->dbase = $strDBase;
			} else {
				$this->dbase = 'caixa';
			}
		}
		function LoadCaixa($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `".$this->dbase."` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `".$this->dbase."` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$codigo = next_autoindex($this->dbase);
			mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`) VALUES ('".$codigo."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->dados[codigo] = $codigo;
		}
		function ListCaixa($sql = "") {
			$sistema = new sistema();
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;

			if($sql == "") {
				$sql = "SELECT * FROM `".$this->dbase."` ORDER BY `data` DESC";
			}

			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i] = $row;
				$i++;
			}
			return $lista;
		}
		function SaldoTotal($strCPF = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			if($strCPF == "") {
				$where = "";
			} else {
				$where = "`codigo_dentista` = '".$strCPF."' AND";
			}
			$saldo_positivo = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`valor`) as `saldo_positivo` FROM `".$this->dbase."` WHERE ".$where." `dc` = '+'"));
			$saldo_negativo = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`valor`) as `saldo_negativo` FROM `".$this->dbase."` WHERE ".$where." `dc` = '-'"));
			$saldo_positivo = $saldo_positivo[saldo_positivo];
			$saldo_negativo = $saldo_negativo[saldo_negativo];
			return($saldo_positivo - $saldo_negativo);
		}
	}
	/**
	 * Classe dos Telefones
	 *
	 */
	class TTelefones {
		private $dados;
		private $codigo_anterior;
		function LoadTelefones($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `telefones` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			$this->codigo_anterior = $this->dados[codigo];
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `telefones` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->codigo_anterior."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$codigo = next_autoindex('telefones');
			mysqli_query($conn, "INSERT INTO `telefones` (`codigo`) VALUES ('".$codigo."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->codigo_anterior = $codigo;
		}
		function ListTelefones($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `telefones` ORDER BY `nome` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][nome] = $row[nome];
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][telefone1] = $row[telefone1];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos Patrimônios da Clínica
	 *
	 */
	class TPatrimonios {
		private $dados;
		private $codigo_anterior;
		function LoadPatrimonio($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `patrimonio` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
			$this->codigo_anterior = $this->dados[codigo];
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `patrimonio` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->codigo_anterior."'");
				}
			}
			if($this->codigo_anterior != $this->dados[codigo]) {
				mysqli_query($conn, "UPDATE `patrimonio` SET `codigo` = '".$this->dados[codigo]."' WHERE `codigo` = '".$this->codigo_anterior."'");
				$this->codigo_anterior = $this->dados[codigo];
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			mysqli_query($conn, "INSERT INTO `patrimonio` (`codigo`) VALUES ('".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			$this->codigo_anterior = $this->dados[codigo];
		}
		function ListPatrimonio($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `patrimonio` ORDER BY `descricao` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][descricao] = $row[descricao];
				$lista[$i][codigo] = $row[codigo];
				$lista[$i][valor] = $row[valor];
				$lista[$i][setor] = $row[setor];
				$i++;
			}
			return $lista;
		}
		function ValorTotal() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$saldo = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`valor`) as `saldo` FROM `patrimonio`"));
			$saldo = $saldo[saldo];
			return($saldo);
		}
	}
	/**
	 * Classe da Agenda de Consultas
	 *
	 */
	class TAgendas {
		private $dados;

		public function getConsultasHoje()
		{
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			$dataHoje = "2017-01-21";//date("Y-m-d");

			$retorno = array();

			$query = $conn->query("SELECT * FROM agenda AS a INNER JOIN pacientes AS p ON a.codigo_paciente=p.codigo WHERE data='$dataHoje' AND codigo_paciente!=''");

			while($resul = mysqli_fetch_array($query))
			{
				$retorno[] = $resul;
			}

			return $retorno;
		}

		function LoadAgenda($datData, $timHora, $intCodigo) {

			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `agenda` WHERE `data` = '".$datData."' AND  `hora` = '".$timHora."' AND  `codigo_dentista` = '".$intCodigo."'"));
			$this->dados = $row;
			$this->dados[data] = $datData;
			$this->dados[hora] = $timHora;
			$this->dados[codigo_dentista] = $intCodigo;
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			
			foreach($this->dados as $chave => $valor) {
				if($chave != 'data' && $chave != 'hora' && $chave != 'codigo_dentista') {
					mysqli_query($conn, "UPDATE `agenda` SET `".$chave."` = '".$valor."' WHERE `data` = '".$this->dados[data]."' AND `hora` = '".$this->dados[hora]."' AND  `codigo_dentista` = '".$this->dados[codigo_dentista]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			mysqli_query($conn, "INSERT INTO `agenda` (`data`, `hora`, `codigo_dentista`, status) VALUES ('".$this->dados[data]."', '".$this->dados[hora]."', '".$this->dados[codigo_dentista]."', 0)") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
		}
		
		function ListAgenda($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `agenda` ORDER BY `data`, `hora` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][descricao] = $row[descricao];
				$lista[$i][procedimento] = $row[procedimento];
				$lista[$i][data] = $row[data];
				$lista[$i][hora] = $row[hora];
				$lista[$i][codigo_dentista] = $row[codigo_dentista];
				$i++;
			}
			return $lista;
		}
		function ExistHorario() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			return(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `agenda` WHERE `data` = '".$this->dados[data]."' AND `hora` = '".$this->dados[hora]."' AND  `codigo_dentista` = '".$this->dados[codigo_dentista]."'")));
		}
	}
	/**
	 * Classe das contas a pagar e a receber
	 * 
	 */
	class TContas {
		private $dados;
		private $dbase;

		function statusReceber($cod) {

			switch ($cod) {
				case 0:
				return "<b><span style='color: #539498;'><span class='glyphicon glyphicon-info-sign'></span> Aguardando pagamento</span></b>";
				break;

				case 1:
				return "<b><span style='color: #00ad18;'><span class='glyphicon glyphicon-ok-sign'></span> Recebido</span></b>";
				break;
				
				default:

				break;
			}

		}

		function statusPagar($cod) {

			switch ($cod) {
				case 0:
				return "<b><span style='color: #539498;'><span class='glyphicon glyphicon-info-sign'></span> Aguardando pagamento</span></b>";
				break;

				case 1:
				return "<b><span style='color: #00ad18;'><span class='glyphicon glyphicon-ok-sign'></span> Paga</span></b>";
				break;
				
				default:

				break;
			}

		}

		function __construct($strOpcao, $strEntrada = '') {
			if($strOpcao == 'dentista' && $strEntrada == '') {
				$this->dbase = 'contaspagar_dent';
			} elseif($strOpcao == 'clinica' && $strEntrada == '') {
				$this->dbase = 'contaspagar';
			} elseif($strOpcao == 'dentista' && $strEntrada != '') {
				$this->dbase = 'contasreceber_dent';
			} elseif($strOpcao == 'clinica' && $strEntrada != '') {
				$this->dbase = 'contasreceber';
			}
		}
		function LoadConta($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `".$this->dbase."` WHERE `codigo` = ".$intCodigo));
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo' && $chave != 'codigo_dentista') {
					mysqli_query($conn, "UPDATE `".$this->dbase."` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'");
				}
			}
		}

		function getVencimentos() // Obtem o vencimento de contas a receber (clientes)
		{
			$sistema = new sistema(); 
			$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

			$dataHoje = date("Y-m-d");

			$retorno = array();

			$query = $conn->query("SELECT * FROM contasreceber AS c INNER JOIN pacientes AS p ON c.paciente=p.codigo WHERE datavencimento='$dataHoje' AND c.status='0'");

			while($resul = mysqli_fetch_array($query))
			{
				$retorno[] = $resul;
			}

			return $retorno;
		}

		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados[codigo] = next_autoindex($this->dbase);
			if($this->dbase == 'contaspagar_dent') {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`, `codigo_dentista`) VALUES ('".$this->dados[codigo]."', '".$this->dados[codigo_dentista]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			} elseif($this->dbase == 'contaspagar') {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`) VALUES ('".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			} elseif($this->dbase == 'contasreceber_dent') {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`, `codigo_dentista`) VALUES ('".$this->dados[codigo]."', '".$this->dados[codigo_dentista]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			} elseif($this->dbase == 'contasreceber') {
				//echo $this->dbase;
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`) VALUES ('".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			}
		}
		function ListConta($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `".$this->dbase."` ORDER BY `datavencimento` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][codigo] = $row[codigo];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos cheques recebidos
	 * 
	 */
	class TCheques {
		private $dados;
		private $dbase;
		function __construct($strOpcao = '') {
			if($strOpcao == 'dentista') {
				$this->dbase = 'cheques_dent';
			} else {
				$this->dbase = 'cheques';
			}
		}
		function LoadCheque($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `".$this->dbase."` WHERE `codigo` = ".$intCodigo));
		}
		function RetornaDados($strCampo) {

			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `".$this->dbase."` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados[codigo] = next_autoindex($this->dbase);
			if($this->dbase == 'cheques_dent') {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`, `codigo_dentista`) VALUES ('".$this->dados[codigo]."', '".$this->dados[codigo_dentista]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			} else {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`) VALUES ('".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			}
		}
		function ListCheque($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `".$this->dbase."` ORDER BY `codigo` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][codigo] = $row[codigo];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe do controle de estoques
	 *
	 */
	class TEstoque {
		private $dados;
		private $dbase;
		function __construct($strOpcao) {
			if($strOpcao == 'dentista') {
				$this->dbase = 'estoque_dent';
			} elseif($strOpcao == 'clinica') {
				$this->dbase = 'estoque';
			}
		}
		function LoadConta($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `".$this->dbase."` WHERE `codigo` = ".$intCodigo));
		}
		function RetornaDados($strCampo) {

			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo' && $chave != 'codigo_dentista') {
					mysqli_query($conn, "UPDATE `".$this->dbase."` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados[codigo] = next_autoindex($this->dbase);
			if($this->dbase == 'estoque_dent') {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`, `codigo_dentista`) VALUES ('".$this->dados[codigo]."', '".$this->dados[codigo_dentista]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			} elseif($this->dbase == 'estoque') {
				mysqli_query($conn, "INSERT INTO `".$this->dbase."` (`codigo`) VALUES ('".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
			}
		}
		function ListConta($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `".$this->dbase."` ORDER BY `descricao` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i][codigo] = $row[codigo];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe do controle da tablea de honorários
	 *
	 */
	class THonorarios {
		private $dados;
		function LoadInfo($strCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `honorarios` WHERE `codigo` = '".$strCodigo."'"));
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `honorarios` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados['codigo']."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			mysqli_query($conn, "INSERT INTO `honorarios` (codigo) VALUES ('".$this->dados['codigo']."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
		}
		function Consulta($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `honorarios` ORDER BY `codigo` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i]['codigo'] = $row['codigo'];
				$lista[$i]['procedimento'] = $row['procedimento'];
				$lista[$i]['valor_particular'] = $row['valor_particular'];
				$lista[$i]['valor_convenio'] = $row['valor_convenio'];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe para controle de orçamento
	 * 
	 */
	class TOrcamento {
		private $dados;
		private $dadosPagamentos;
		private $dadosProcedimentos;

		function LoadOrcamento($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `orcamento` WHERE `codigo` = ".$intCodigo));
			$i = 0;
			$query = mysqli_query($conn, "SELECT * FROM `procedimentos_orcamento` WHERE `codigo_orcamento` = ".$intCodigo);
			while($row = mysqli_fetch_assoc($query)) {
				foreach($row as $chave => $valor) {
					$this->dadosProcedimentos[$i][$chave] = $valor;
				}
				$i++;
			}
			$i = 0;
			$query = mysqli_query($conn, "SELECT * FROM `pagamentos_orcamento` WHERE `codigo_orcamento` = ".$intCodigo);
			while($row = mysqli_fetch_assoc($query)) {
				foreach($row as $chave => $valor) {
					$this->dadosPagamentos[$i][$chave] = $valor;
				}
				$i++;
			}
		}
		function RetornaDados($strCampo) {
			return($this->dados[$strCampo]);
		}
		function RetornaDadosPagamentos($strCampo, $intIndice) {
			return($this->dadosPagamentos[$intIndice][$strCampo]);
		}
		function RetornaDadosProcedimentos($strCampo, $intIndice) {
			return($this->dadosProcedimentos[$intIndice][$strCampo]);
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function SetDadosPagamentos($strCampo, $strValor, $intIndice) {
			$this->dadosPagamentos[$intIndice][$strCampo] = $strValor;
		}
		function SetDadosProcedimentos($strCampo, $strValor, $intIndice) {
			$this->dadosProcedimentos[$intIndice][$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `orcamento` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados['codigo']."'");
				}
			}
		}
		function SalvarPagamentos() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dadosPagamentos as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `orcamento` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados['codigo']."'");
				}
			}
		}
	}
	/**
	 * Classe dos exames objetivos dos pacientes da clínica
	 *
	 */
	class TExObjetivo {
		private $dados;
		function LoadExObjetivo($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `exameobjetivo` WHERE `codigo_paciente` = '".$intCodigo."'"));
			$this->dados = $row;
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo_paciente') {
					mysqli_query($conn, "UPDATE `exameobjetivo` SET `".$chave."` = '".$valor."' WHERE `codigo_paciente` = '".$this->dados[codigo_paciente]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			mysqli_query($conn, "INSERT INTO `exameobjetivo` (`codigo_paciente`) VALUES ('".$this->dados[codigo_paciente]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
		}
	}
	/**
	 * Classe da evolução no tratamento dos pacientes da clínica
	 *
	 */
	class TEvolucao {
		private $dados;
		function LoadEvolucao($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `evolucao` WHERE `codigo` = '".$intCodigo."'"));
			$this->dados = $row;
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function ApagaDados() {
			mysqli_query($conn, "DELETE FROM evolucao WHERE codigo = ".$this->dados['codigo']);
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo') {
					mysqli_query($conn, "UPDATE `evolucao` SET `".$chave."` = '".$valor."' WHERE `codigo` = '".$this->dados[codigo]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$this->dados[codigo] = next_autoindex('evolucao');
			mysqli_query($conn, "INSERT INTO `evolucao` (`codigo_paciente`, `codigo`) VALUES ('".$this->dados[codigo_paciente]."', '".$this->dados[codigo]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
		}
		function ListEvolucao($sql = "") {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$i = 0;
			if($sql == "") {
				$sql = "SELECT * FROM `evolucao` WHERE `codigo_paciente` = '".$this->dados[codigo_paciente]."' ORDER BY `data` ASC";
			}
			$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($query)) {
				$lista[$i] = $row[codigo];
				$i++;
			}
			return $lista;
		}
	}
	/**
	 * Classe dos inquéritos de saúde dos pacientes da clínica
	 *
	 */
	class TInquerito {
		private $dados;
		function LoadInquerito($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `inquerito` WHERE `codigo_paciente` = '".$intCodigo."'"));
			$this->dados = $row;
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				if($chave != 'codigo_paciente') {
					mysqli_query($conn, "UPDATE `inquerito` SET `".$chave."` = '".$valor."' WHERE `codigo_paciente` = '".$this->dados[codigo_paciente]."'");
				}
			}
		}
		function SalvarNovo() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			mysqli_query($conn, "INSERT INTO `inquerito` (`codigo_paciente`) VALUES ('".$this->dados[codigo_paciente]."')") or die("Erro em SalvarNovo(): ".mysqli_error($conn));
		}
	}
	/**
	 * Classe da Ortodontia dos pacientes da clínica
	 *
	 */
	class TOrtodontia {
		private $dados;
		function LoadOrtodontia($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `ortodontia` WHERE `codigo_paciente` = '".$intCodigo."'"));
			$this->dados = $row;
			if($this->dados == '') {
				$this->dados['codigo_paciente'] = $intCodigo;
			}
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				$set[] = $chave;
				$value[] = "'".$valor."'";
			}
			$set = implode(', ', $set);
			$value = implode(', ', $value);
			mysqli_query($conn, "REPLACE INTO ortodontia (".$set.") VALUES (".$value.")") or die('Line 933: '.mysqli_error($conn));
		}
	}
	/**
	 * Classe da Implantodontia dos pacientes da clínica
	 *
	 */
	class TImplantodontia {
		private $dados;
		function LoadImplantodontia($intCodigo) {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `implantodontia` WHERE `codigo_paciente` = '".$intCodigo."'"));
			$this->dados = $row;
			if($this->dados == '') {
				$this->dados['codigo_paciente'] = $intCodigo;
			}
		}
		function RetornaDados($strCampo) {
			return $this->dados[$strCampo];
		}
		function RetornaTodosDados() {
			return $this->dados;
		}
		function SetDados($strCampo, $strValor) {
			$this->dados[$strCampo] = $strValor;
		}
		function Salvar() {
			$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
			foreach($this->dados as $chave => $valor) {
				$set[] = $chave;
				$value[] = "'".$valor."'";
			}
			$set = implode(', ', $set);
			$value = implode(', ', $value);
			mysqli_query($conn, "REPLACE INTO implantodontia (".$set.") VALUES (".$value.")") or die('Line 965: '.mysqli_error($conn));
		}
	}
	/**
	 * Classe dos dados da clínica para cabeçalhos
	 *
	 */
	class TClinica {
		private $cnpj;
		private $razaosocial;
		private $fantasia;
		private $proprietario;
		private $endereco;
		private $bairro;
		private $cidade;
		private $estado;
		private $cep;
		private $pais;
		private $fundacao;
		private $telefone1;
		private $telefone2;
		private $fax;
		private $email;
		private $web;
		private $banco1;
		private $agencia1;
		private $conta1;
		private $banco2;
		private $agencia2;
		private $conta2;
		private $idioma;
		private $logomarca;
        /**
         * Declara os atributos
         *
         */
        function getCNPJ() { return $this->cnpj; }
        function setCNPJ($strCNPJ) { $this->cnpj = $strCNPJ; }

        function getRazaoSocial() { return $this->razaosocial; }
        function setRazaoSocial($strRazaoSocial) { $this->razaosocial = $strRazaoSocial; }

        function getFantasia() { return $this->fantasia;}
        function setFantasia($strFantasia) { $this->fantasia = $strFantasia; }

        function getProprietario() { return $this->proprietario; }
        function setProprietario($strProprietario) { $this->proprietario = $strProprietario; }

        function getEndereco() { return $this->endereco; }
        function setEndereco($strEndereco) { $this->endereco = $strEndereco; }

        function getBairro() { return $this->bairro; }
        function setBairro($strBairro) { $this->bairro = $strBairro; }

        function getCidade() { return $this->cidade; }
        function setCidade($strCidade) { $this->cidade = $strCidade; }

        function getEstado() { return $this->estado; }
        function setEstado($strEstado) { $this->estado = $strEstado; }

        function getCEP() { return $this->cep; }
        function setCEP($strCEP) { $this->cep = $strCEP; }
        
        function getPais() { return $this->pais; }
        function setPais($strPais) { $this->pais = $strPais; }

        function getFundacao() { return $this->fundacao; }
        function setFundacao($strFundacao) { $this->fundacao = $strFundacao; }

        function getTelefone1() { return $this->telefone1; }
        function setTelefone1($strTelefone1) { $this->telefone1 = $strTelefone1; }

        function getTelefone2() { return $this->telefone2; }
        function setTelefone2($strTelefone2) { $this->telefone2 = $strTelefone2; }

        function getFax() { return $this->fax; }
        function setFax($strFax) { $this->fax = $strFax; }

        function getEmail() { return $this->email; }
        function setEmail($strEmail) { $this->email = $strEmail; }

        function getWeb() { return $this->web; }
        function setWeb($strWeb) { $this->web = $strWeb; }

        function getBanco1() { return $this->banco1; }
        function setBanco1($strBanco1) { $this->banco1 = $strBanco1; }

        function getAgencia1() { return $this->agencia1; }
        function setAgencia1($strAgencia1) { $this->agencia1 = $strAgencia1; }

        function getConta1() { return $this->conta1; }
        function setConta1($strConta1) { $this->conta1 = $strConta1; }

        function getBanco2() { return $this->banco2; }
        function setBanco2($strBanco2) { $this->banco2 = $strBanco2; }

        function getAgencia2() { return $this->agencia2; }
        function setAgencia2($strAgencia2) { $this->agencia2 = $strAgencia2; }

        function getConta2() { return $this->conta2; }
        function setConta2($strConta2) { $this->conta2 = $strConta2; }

        function getIdioma() { return $this->idioma; }
        function setIdioma($strIdioma) { $this->idioma = $strIdioma; }

        function getLogomarca() { return $this->logomarca; }
        function setLogomarca($strLogomarca) { $this->logomarca = $strLogomarca; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); 
        	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dados_clinica"))or die(mysqli_error($conn));      	

        	$this->CNPJ = $row['cnpj'];
        	$this->RazaoSocial = $row['razaosocial'];
        	$this->Fantasia = $row['fantasia'];
        	$this->Proprietario = $row['proprietario'];
        	$this->Endereco = $row['endereco'];
        	$this->Bairro = $row['bairro'];
        	$this->Cidade = $row['cidade'];
        	$this->Estado = $row['estado'];
        	$this->Cep = $row['cep'];
        	$this->Pais = $row['pais'];
        	$this->Fundacao = $row['fundacao'];
        	$this->Telefone1 = $row['telefone1'];
        	$this->Telefone2 = $row['telefone2'];
        	$this->Fax = $row['fax'];
        	$this->Email = $row['email'];
        	$this->Web = $row['web'];
        	$this->Banco1 = $row['banco1'];
        	$this->Agencia1 = $row['agencia1'];
        	$this->Conta1 = $row['conta1'];
        	$this->Banco2 = $row['banco2'];
        	$this->Agencia2 = $row['agencia2'];
        	$this->Conta2 = $row['conta2'];
        	$this->Idioma = $row['idioma'];
        	$this->Logomarca = $row['logomarca'];

        	return $row; 
        }
        function Salvar() {

        	$sistema = new sistema(); 
        	$conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));

        	$sql  = "UPDATE dados_clinica SET ";
        	$sql .= "cnpj = '".$this->CNPJ."', ";
        	$sql .= "razaosocial = '".$this->RazaoSocial."', ";
        	$sql .= "fantasia = '".$this->Fantasia."', ";
        	$sql .= "proprietario = '".$this->Proprietario."', ";
        	$sql .= "endereco = '".$this->Endereco."', ";
        	$sql .= "bairro = '".$this->Bairro."', ";
        	$sql .= "cidade = '".$this->Cidade."', ";
        	$sql .= "estado = '".$this->Estado."', ";
        	$sql .= "cep = '".$this->Cep."', ";
        	$sql .= "pais = '".$this->Pais."', ";
        	$sql .= "fundacao = '".$this->Fundacao."', ";
        	$sql .= "telefone1 = '".$this->Telefone1."', ";
        	$sql .= "telefone2 = '".$this->Telefone2."', ";
        	$sql .= "fax = '".$this->Fax."', ";
        	$sql .= "email = '".$this->Email."', ";
        	$sql .= "web = '".$this->Web."', ";
        	$sql .= "banco1 = '".$this->Banco1."', ";
        	$sql .= "agencia1 = '".$this->Agencia1."', ";
        	$sql .= "conta1 = '".$this->Conta1."', ";
        	$sql .= "banco2 = '".$this->Banco2."', ";
        	$sql .= "agencia2 = '".$this->Agencia2."', ";
        	$sql .= "conta2 = '".$this->Conta2."', ";
        	$sql .= "idioma = '".$this->Idioma."'";

        	echo $this->Telefone1;

        	mysqli_query($conn, $sql) or die('Line 1001: '. mysqli_error($conn));
        }
    }
	/**
	 * Classe do texto da receita
	 *
	 */
	class TReceita {
		private $receita;
		private $codigo_paciente;
        /**
         * Declara os atributos
         *
         */
        function getReceita() { return $this->receita; }
        function setReceita($strReceita) { $this->receita = $strReceita; }

        function getCodigo_Paciente() { return $this->codigo_paciente; }
        function setCodigo_Paciente($strCodigo_Paciente) { $this->codigo_paciente = $strCodigo_Paciente; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `receitas` WHERE codigo_paciente = ".$this->Codigo_Paciente));
        	$this->Receita = $row['receita'];
        }
        function SalvarNovo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "INSERT INTO receitas ";
        	$sql .= "(receita, codigo_paciente) ";
        	$sql .= "VALUES ('Amoxil(Amoxicilina).................................................... 500 mg ............................................................ 1cx
        	1(uma) cápsula de 8(oito) em 8(oito) horas durante 7(sete) dias




        	Cataflam(Diclofenaco).................................................. 50mg ............................................................. 1cx
        	1(um) comprimido de 8(oito) em 8(oito) horas durante 7(sete) dias




        	Tylenol(Paracetamol).................................................... 750mg ............................................................ 1cx
        	1(um) comprimido de 4(quatro) em 4(quatro) horas durante 2(dois) dias ou quando houver dor.', ";
        	$sql .= "'".$this->Codigo_Paciente."')";
        	mysqli_query($conn, $sql) or die('Line 1152: '. mysqli_error($conn));
        }
        function Salvar() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "UPDATE receitas SET ";
        	$sql .= "receita = '".$this->Receita."' ";
        	$sql .= "WHERE codigo_paciente = ".$this->Codigo_Paciente;
        	mysqli_query($conn, $sql) or die('Line 1158: '. mysqli_error($conn));
        }
    }
	/**
	 * Classe do texto do atestado
	 *
	 */
	class TAtestado {
		private $atestado;
		private $codigo_paciente;
        /**
         * Declara os atributos
         *
         */
        function getAtestado() { return $this->atestado; }
        function setAtestado($strAtestado) { $this->atestado = $strAtestado; }

        function getCodigo_Paciente() { return $this->codigo_paciente; }
        function setCodigo_Paciente($strCodigo_Paciente) { $this->codigo_paciente = $strCodigo_Paciente; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM atestados WHERE codigo_paciente = ".$this->Codigo_Paciente));
        	$this->Atestado = $row['atestado'];
        }
        function SalvarNovo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "INSERT INTO atestados ";
        	$sql .= "(atestado, codigo_paciente) ";
        	$sql .= "VALUES ('Atesto para fins trabalhistas e/ou escolares que o paciente supracitada esteve sob meus cuidados durante este dia realizando uma cirurgia oral avançada de exodontia (extração) de dentes cisos, e deverá ficar 4(quatro) dias em repouso absoluto a partir desta data.


        	Ressalto que este repouso será importante para o sucesso do tratamento e tranquilidade pós-operatória.



        	Sem mais, fico a disposição para quaisquer esclarecimentos.





        	CID (Código Internacional de Doenças) = K07.3', ";
        	$sql .= "'".$this->Codigo_Paciente."')";
        	mysqli_query($conn, $sql) or die('Line 1203: '. mysqli_error($conn));
        }
        function Salvar() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "UPDATE atestados SET ";
        	$sql .= "atestado = '".$this->Atestado."' ";
        	$sql .= "WHERE codigo_paciente = ".$this->Codigo_Paciente;
        	mysqli_query($conn, $sql) or die('Line 1209: '. mysqli_error($conn));
        }
    }
	/**
	 * Classe do texto do pedido de exames
	 *
	 */
	class TExame {
		private $exame;
		private $codigo_paciente;
        /**
         * Declara os atributos
         *
         */
        function getExame() { return $this->exame; }
        function setExame($strExame) { $this->exame = $strExame; }

        function getCodigo_Paciente() { return $this->codigo_paciente; }
        function setCodigo_Paciente($strCodigo_Paciente) { $this->codigo_paciente = $strCodigo_Paciente; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM exames WHERE codigo_paciente = ".$this->Codigo_Paciente));
        	$this->Exame = $row['exame'];
        }
        function SalvarNovo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "INSERT INTO exames ";
        	$sql .= "(exame, codigo_paciente) ";
        	$sql .= "VALUES ('Solicito para o(a) paciente supracitado os seguintes exames laboratoriais para finalidade de tratamento odontológico:


        	- Hemograma Completo

        	- Coagulograma

        	- Colesterol

        	- Triglicérides

        	- Glicose

        	- Anti-H.I.V.', ";
        	$sql .= "'".$this->Codigo_Paciente."')";
        	mysqli_query($conn, $sql) or die('Line 1254: '. mysqli_error($conn));
        }
        function Salvar() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "UPDATE exames SET ";
        	$sql .= "exame = '".$this->Exame."' ";
        	$sql .= "WHERE codigo_paciente = ".$this->Codigo_Paciente;
        	mysqli_query($conn, $sql) or die('Line 1260: '. mysqli_error($conn));
        }
    }
	/**
	 * Classe do texto do pedido de encaminhamento
	 *
	 */
	class TEncaminhamento {
		private $encaminhamento;
		private $codigo_paciente;
        /**
         * Declara os atributos
         *
         */
        function getEncaminhamento() { return $this->encaminhamento; }
        function setEncaminhamento($strEncaminhamento) { $this->encaminhamento = $strEncaminhamento; }

        function getCodigo_Paciente() { return $this->codigo_paciente; }
        function setCodigo_Paciente($strCodigo_Paciente) { $this->codigo_paciente = $strCodigo_Paciente; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM encaminhamentos WHERE codigo_paciente = ".$this->Codigo_Paciente));
        	$this->Encaminhamento = $row['encaminhamento'];
        }
        function SalvarNovo() {
        	/*$sql  = "INSERT INTO encaminhamentos ";
        	$sql .= "(encaminhamento, codigo_paciente) ";
        	$sql .= "VALUES ('Prezado colega,

        	Encaminho o(a) paciente para a realização de um tratamento endodôntico no dente 26 conforme imagem radiográfica visível na radiografia em anexo.

        	Fico a disposição para qualquer esclarecimento.

        	Atenciosamente.', ";
        	$sql .= "'".$this->Codigo_Paciente."')";
        	mysqli_query($conn, $sql) or die('Line 1298: '. mysqli_error($conn));*/
        }
        function Salvar() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "UPDATE encaminhamentos SET ";
        	$sql .= "encaminhamento = '".$this->Encaminhamento."' ";
        	$sql .= "WHERE codigo_paciente = ".$this->Codigo_Paciente;
        	mysqli_query($conn, $sql) or die('Line 1304: '. mysqli_error($conn));
        }
    }
	/**
	 * Classe do texto do pedido de laudos
	 *
	 */
	class TLaudo {
		private $laudo;
		private $codigo_paciente;
        /**
         * Declara os atributos
         *
         */
        function getLaudo() { return $this->laudo; }
        function setLaudo($strLaudo) { $this->laudo = $strLaudo; }

        function getCodigo_Paciente() { return $this->codigo_paciente; }
        function setCodigo_Paciente($strCodigo_Paciente) { $this->codigo_paciente = $strCodigo_Paciente; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM laudos WHERE codigo_paciente = ".$this->Codigo_Paciente));
        	$this->Laudo = $row['laudo'];
        }
        function SalvarNovo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "INSERT INTO laudos ";
        	$sql .= "(laudo, codigo_paciente) ";
        	$sql .= "VALUES ('Paciente apresenta-se com lesão periapical radiolúcida sugestiva de cisto.

        	Recomendamos que faça o tratamento adequado para a regressão desta lesão.

        	Fico à disposição para qualquer esclarecimento.

        	Atenciosamente.', ";
        	$sql .= "'".$this->Codigo_Paciente."')";
        	mysqli_query($conn, $sql) or die('Line 1342: '. mysqli_error($conn));
        }
        function Salvar() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "UPDATE laudos SET ";
        	$sql .= "laudo = '".$this->Laudo."' ";
        	$sql .= "WHERE codigo_paciente = ".$this->Codigo_Paciente;
        	mysqli_query($conn, $sql) or die('Line 1348: '. mysqli_error($conn));
        }
    }
	/**
	 * Classe do texto do pedido de laudos
	 *
	 */
	class TAgradecimento {
		private $agradecimento;
		private $codigo_paciente;
        /**
         * Declara os atributos
         *
         */
        function getAgradecimento() { return $this->agradecimento; }
        function setAgradecimento($strAgradecimento) { $this->agradecimento = $strAgradecimento; }

        function getCodigo_Paciente() { return $this->codigo_paciente; }
        function setCodigo_Paciente($strCodigo_Paciente) { $this->codigo_paciente = $strCodigo_Paciente; }
        /**
         * Métodos da classe
         *
         */
        function LoadInfo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM agradecimentos WHERE codigo_paciente = ".$this->Codigo_Paciente));
        	$this->Agradecimento = $row['agradecimento'];
        }
        function SalvarNovo() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "INSERT INTO agradecimentos ";
        	$sql .= "(agradecimento, codigo_paciente) ";
        	$sql .= "VALUES ('Agradeço o encaminhamento do(a) paciente em questão e encaminho de volta para a continuidade do tratamento após ter executado todo o tratamento solicitado.

        	Fico à disposição para esclarecimentos.

        	Atenciosamente.', ";
        	$sql .= "'".$this->Codigo_Paciente."')";
        	mysqli_query($conn, $sql) or die('Line 1384: '. mysqli_error($conn));
        }
        function Salvar() {
        	$sistema = new sistema(); $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));
        	$sql  = "UPDATE agradecimentos SET ";
        	$sql .= "agradecimento = '".$this->Agradecimento."' ";
        	$sql .= "WHERE codigo_paciente = ".$this->Codigo_Paciente;
        	mysqli_query($conn, $sql) or die('Line 1390: '. mysqli_error($conn));
        }
    }
    ?>
