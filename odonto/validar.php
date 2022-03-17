<?php


	mysql_connect("localhost", "root", "");
	mysql_select_db("gerenciador");


	$licencaCliente = $_GET['licenca'];

	if($_POST[licenca] != "")
	{
		mysql_query("UPDATE dados_clinica SET chave='$_POST[licenca]' WHERE id='0'");
		exit;
	}


	$chaveSecreta = 2541;

	if($licencaCliente == "")
	{
		echo "semLicenca";
		exit;
	}

	$status = "";

	
	$dataAtual = date("Y-m-d");

	$infoClinica = mysql_fetch_array(mysql_query("SELECT * FROM dados_clinica"));

	if($infoClinica[cnpj] != getCNPJ($licencaCliente, $chaveSecreta))
	{
		echo "invalida";
		exit;
	}

	if($dataAtual > getVencimento($licencaCliente, $chaveSecreta))
	{
		echo "faltaPagamento";
		exit;
	}


	echo "sucesso";


	function getComputadores($hash, $secreta)
	{
		$comp = base64_decode($hash);
		$aux = "";

		$qtComputadores = "";

		for($i=0; $i < strlen($comp); $i++) // Loop para reunir a hash da quantidade de computadores.
		{
			$atual = substr($comp, $i, 1);

			//echo $i."=".$atual.", ";

			if($atual == "S") $computadores = ($i+1).".".(strlen($comp)-$i);
		}

		$aux = explode(".", $computadores); // Separa onde inicia e onde termina o index da quantidade de computadores.

		$computadores = substr($comp, $aux[0], $aux[1]);
		$computadores = $computadores/$secreta; // Divisão pela chave secreta (retorna a quantidade de computadores).

		return $computadores;
	}

	function getCNPJ($hash, $secreta) // Função para obter o cnpj
	{
		$hash = base64_decode($hash);
		$aux = "";

		$cnpj = "";

		for($i=0; $i < strlen($hash); $i++) // Loop para reunir a hash do cnpj.
		{
			$atual = substr($hash, $i, 1);

			//echo $i."=".$atual.", ";

			if($atual == "J") $cnpj = ($i+1).".";
			if($atual == "S") $cnpj.=$i;
		}

		$deliCnpj = explode(".", $cnpj); // Separa onde inicia e onde termina o index do cnpj.


		for($i=$deliCnpj[0]; $i<$deliCnpj[1]; $i++) // Loop para reunir a hash do cnpj.
		{
			$tt = substr($hash, $i, 1);
			if(is_numeric($tt)) $aux.=$tt;
		}


		$cnpj = $aux/$secreta; // Divisão da hash pela chave secreta (retorna o cnpj).

		/* FILTRA CNPJ */
		$cnpj = str_replace(".", "", $cnpj);
		$cnpj = str_replace(",", "", $cnpj);
		$cnpj = str_replace("E", "", $cnpj);
		$cnpj = str_replace("+", "", $cnpj);
		$cnpj = str_replace("/", "", $cnpj);
		$cnpj = str_replace("-", "", $cnpj);

		if(strlen($cnpj) == 10) $cnpj = "0".$cnpj;
		return $cnpj;
	}

	function getVencimento($chave,  $secreta)
	{
		// X = MÊS, Z = ANO, F = DIA.


		$vencimento = base64_decode($chave); // decodifica a criptografica base64


		$aux = "";

		$dia = "";
		$mes = "";
		$ano = "";

		for($i = 0; $i < strlen($vencimento); $i++) // Loop para encontrar o dia, mês e ano dentro da hash.
		{

			$atual = substr($vencimento, $i, 1);

			if($atual == "X") $mes = $i.".";
			if($atual == "Z")
			{
				$mes.=$i;
				$ano = $i.".";
			}
			if($atual == "F")
			{
				$ano.=$i;
				$dia = $i.".";
			}
			if($atual == "J")
			{
				$dia.= $i;
			}
		}

		$deliDia = explode(".", $dia); // Separa onde inicia e onde termina o index do dia.

		for($i=$deliDia[0]; $i<$deliDia[1]; $i++) //Loop para reunir a hash do dia.
		{
			$tt = substr($vencimento, $i, 1);
			if(is_numeric($tt)) $aux.=$tt;
		}

		$dia = $aux/$secreta; // Divisão pela chave secreta (retorna o dia do vencimento);
		$aux = "";

		$deliMes = explode(".", $mes); // Separa onde inicia e onde termina o index do mês.

		for($i=$deliMes[0]; $i<$deliMes[1]; $i++) //Loop para reunir a hash do mês.
		{
			$tt = substr($vencimento, $i, 1);
			if(is_numeric($tt)) $aux.=$tt;
		}

		$mes = $aux/$secreta; // Divisão pela chave secreta (retorna o mês do vencimento)
		$aux = "0";

		$deliAno = explode(".", $ano); // Separa onde inicia e onde termina o index do ano.

		for($i=$deliAno[0]; $i<$deliAno[1]; $i++) // Loop para reunir a hash do ano.
		{
			$tt = substr($vencimento, $i, 1);
			if(is_numeric($tt)) $aux.=$tt;
		}

		$ano = $aux/$secreta; // Divisão da hash do ano pela chave secreta (retorna o ano do vencimento)
		$aux = "0";

		$dtVencimento = $ano."-".$mes."-".$dia; // Coloca no formato "aaaa-mm-dd" a data do vencimento
		$dtVencimento = date("Y-m-d", strtotime($dtVencimento)); // Converte para o formato brasileiro de data "dd/mm/yyyy".

		return $dtVencimento;
	}

	/*mysql_connect("localhost", "root", "");
	mysql_select_db("clientes");

	$query = mysql_query("SELECT chave FROM clientes WHERE chave='$licencaCliente'");



	$licencaServer = mysql_fetch_row($query);

	if($licencaServer[0] == $licencaCliente)
	{
		echo "sucesso";
	}else{
		echo "invalida";
		exit;
	}*/

?>