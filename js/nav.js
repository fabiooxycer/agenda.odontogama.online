var hash = location.hash;

switch(hash)
{
	case "#incluirFornecedor":
		javascript:Ajax('fornecedores/incluir', 'conteudo', '');
		break;

	case "#incluirLaboratorio":
		javascript:Ajax('laboratorio/incluir', 'conteudo', '');
		break;
		
	case "#honorario":
		javascript:Ajax('honorarios/honorarios', 'conteudo', 'codigo_convenio=1');
		break;

	case "#infoClinica":
		javascript:Ajax('configuracoes/dadosclinica','conteudo','');
		break;

	case "#odontograma":
		javascript:Ajax('pacientes/odontograma','conteudo','codigo=1&acao=editar');
		break;

	case "#exameObjetivo":
		javascript:Ajax('pacientes/objetivo','conteudo','codigo=1&acao=editar')
		break;

	case "#evolucao":
		javascript:Ajax('pacientes/evolucao','conteudo','codigo=1&acao=editar')
		break;

	case "#orcamento":
		javascript:Ajax('pacientes/orcamento','conteudo','codigo=1&acao=editar');
		break;

	case "#fecharOrcamento":
		javascript:Ajax('pacientes/orcamentofechar', 'conteudo', 'codigo=1&acao=editar')
		break;

	case "#testeorcamento":
		javascript:Ajax('pacientes/orcamentofechar', 'conteudo', 'codigo=1&indice_orc=2&acao=editar&subacao=editar&codigo_orc=72');
		break;

	case "#inquerito":
		javascript:Ajax('pacientes/inquerito','conteudo','codigo=1&acao=editar');
		break;

	case "#ortodontia":
		javascript:Ajax('pacientes/ortodontia','conteudo','codigo=1&acao=editar');
		break;

	case "#implante":
		javascript:Ajax('pacientes/implantodontia','conteudo','codigo=1&acao=editar');
		break;

	case "#fotos":
		javascript:Ajax('pacientes/fotos','conteudo','codigo=1&acao=editar');
		break;

	case "#radio":
		javascript:Ajax('pacientes/radio','conteudo','codigo=1&acao=editar');
		break;
		

	default:
		break;

}
