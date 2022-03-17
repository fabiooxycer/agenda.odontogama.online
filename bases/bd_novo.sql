-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 19/12/2016 às 08:49
-- Versão do servidor: 5.7.16-0ubuntu0.16.10.1
-- Versão do PHP: 7.0.14-2+deb.sury.org~yakkety+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gerenciador`  
--

-- --------------------------------------------------------

-- 
-- Estrutura para tabela 'sms'
--

CREATE TABLE `tb_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `paciente` text NOT NULL,
  `tipo` varchar(3) NOT NULL,
  `data` date NOT NULL,
  `mensagem` text NOT NULL,
  `hora` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `agenda`
--

CREATE TABLE `agenda` (
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `codigo_dentista` varchar(11) NOT NULL,
  `codigo_paciente` int(10) DEFAULT NULL,
  `descricao` varchar(90) DEFAULT NULL,
  `procedimento` varchar(15) DEFAULT NULL,
  `faltou` enum('Sim','Não') NOT NULL DEFAULT 'Sim',
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `agenda_obs`
--

CREATE TABLE `agenda_obs` (
  `data` date NOT NULL,
  `codigo_dentista` varchar(11) NOT NULL,
  `obs` longtext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `agradecimentos`
--

CREATE TABLE `agradecimentos` (
  `agradecimento` text,
  `codigo_paciente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `ajuda`
--

CREATE TABLE `ajuda` (
  `codigo` int(10) NOT NULL,
  `topico` varchar(200) NOT NULL,
  `texto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `codigo` int(20) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `tamanho` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atestados`
--

CREATE TABLE `atestados` (
  `atestado` longtext,
  `codigo_paciente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `caixa`
--

CREATE TABLE `caixa` (
  `codigo` int(15) NOT NULL,
  `data` date DEFAULT NULL,
  `dc` enum('+','-') DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `modo_pagamento` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `caixa_dent`
--

CREATE TABLE `caixa_dent` (
  `codigo` int(15) NOT NULL,
  `codigo_dentista` varchar(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `dc` enum('+','-') DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cheques`
--

CREATE TABLE `cheques` (
  `codigo` int(50) NOT NULL,
  `valor` float DEFAULT NULL,
  `nometitular` varchar(80) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(20) DEFAULT NULL,
  `recebidode` varchar(80) DEFAULT NULL,
  `encaminhadopara` varchar(80) DEFAULT NULL,
  `compensacao` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cheques_dent`
--

CREATE TABLE `cheques_dent` (
  `codigo` int(50) NOT NULL,
  `codigo_dentista` varchar(11) NOT NULL,
  `valor` float DEFAULT NULL,
  `nometitular` varchar(80) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(20) DEFAULT NULL,
  `recebidode` varchar(80) DEFAULT NULL,
  `encaminhadopara` varchar(80) DEFAULT NULL,
  `compensacao` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contaspagar`
--

CREATE TABLE `contaspagar` (
  `codigo` int(20) NOT NULL,
  `datavencimento` date DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `datapagamento` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `forma_pagamento` int(1) NOT NULL DEFAULT '0',
  `fornecedor` int(11) NOT NULL DEFAULT '0',
  `valor_entrada` varchar(12) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `contaspagar_dent`
--

CREATE TABLE `contaspagar_dent` (
  `codigo` int(20) NOT NULL,
  `codigo_dentista` varchar(11) DEFAULT NULL,
  `datavencimento` date DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `datapagamento` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contasreceber`
--

CREATE TABLE `contasreceber` (
  `codigo` int(20) NOT NULL,
  `datavencimento` date DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `datapagamento` date DEFAULT NULL,
  `paciente` int(11) NOT NULL DEFAULT '0',
  `dentista` int(11) NOT NULL DEFAULT '0',
  `ordem` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `comissao` varchar(11) NOT NULL DEFAULT 's',
  `forma_pagamento` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `contasreceber_dent`
--

CREATE TABLE `contasreceber_dent` (
  `codigo` int(20) NOT NULL,
  `codigo_dentista` varchar(11) DEFAULT NULL,
  `datavencimento` date DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `datapagamento` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `convenios`
--

CREATE TABLE `convenios` (
  `codigo` int(15) NOT NULL,
  `nomefantasia` varchar(80) DEFAULT NULL,
  `cpf` varchar(50) NOT NULL DEFAULT '',
  `razaosocial` varchar(80) DEFAULT NULL,
  `atuacao` varchar(80) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `cidade` varchar(40) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `inscricaoestadual` varchar(40) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomerepresentante` varchar(80) DEFAULT NULL,
  `apelidorepresentante` varchar(50) DEFAULT NULL,
  `emailrepresentante` varchar(100) DEFAULT NULL,
  `celularrepresentante` varchar(15) DEFAULT NULL,
  `telefone1representante` varchar(15) DEFAULT NULL,
  `telefone2representante` varchar(15) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(15) DEFAULT NULL,
  `conta` varchar(15) DEFAULT NULL,
  `favorecido` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `dados_clinica`
--

CREATE TABLE `dados_clinica` (
  `id` int(1) NOT NULL,
  `chave` varchar(90) DEFAULT NULL,
  `cnpj` varchar(50) DEFAULT NULL,
  `razaosocial` varchar(80) DEFAULT NULL,
  `fantasia` varchar(90) DEFAULT NULL,
  `proprietario` varchar(50) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `cidade` varchar(40) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `fundacao` varchar(4) DEFAULT NULL,
  `telefone1` varchar(20) DEFAULT NULL,
  `telefone2` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `banco1` varchar(50) DEFAULT NULL,
  `agencia1` varchar(15) DEFAULT NULL,
  `conta1` varchar(15) DEFAULT NULL,
  `banco2` varchar(50) DEFAULT NULL,
  `agencia2` varchar(15) DEFAULT NULL,
  `conta2` varchar(15) DEFAULT NULL,
  `idioma` varchar(50) DEFAULT NULL,
  `logomarca` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `dentistas`
--

CREATE TABLE `dentistas` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(80) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `usuario` varchar(15) CHARACTER SET latin7 COLLATE latin7_general_cs DEFAULT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `sexo` enum('Masculino','Feminino') NOT NULL,
  `nomemae` varchar(80) DEFAULT NULL,
  `rg` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comissao` float DEFAULT NULL,
  `codigo_areaatuacao1` varchar(5) NOT NULL DEFAULT '0',
  `codigo_areaatuacao2` varchar(5) NOT NULL DEFAULT '0',
  `codigo_areaatuacao3` varchar(5) NOT NULL DEFAULT '0',
  `conselho_tipo` varchar(30) DEFAULT NULL,
  `conselho_estado` varchar(30) DEFAULT NULL,
  `conselho_numero` varchar(30) DEFAULT NULL,
  `ativo` enum('Sim','Não') DEFAULT NULL,
  `data_inicio` varchar(11) NOT NULL DEFAULT '1990-01-01',
  `ultacesso` varchar(11) NOT NULL DEFAULT '1990-01-01',
  `data_fim` varchar(11) NOT NULL DEFAULT '1990-01-01',
  `foto` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `dentista_atendimento`
--

CREATE TABLE `dentista_atendimento` (
  `codigo_dentista` int(11) NOT NULL,
  `dia_semana` tinyint(1) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `ativo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `encaminhamentos`
--

CREATE TABLE `encaminhamentos` (
  `encaminhamento` text,
  `codigo_paciente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `especialidades`
--

CREATE TABLE `especialidades` (
  `codigo` int(5) NOT NULL,
  `descricao` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Extraindo dados da tabela `especialidades`
#

INSERT INTO `especialidades` (`codigo`, `descricao`) VALUES
(1, 'Cirurgia e Traumatologia Buco Maxilo Faciais'),
(2, 'Clínica Geral'),
(3, 'Dentistica'),
(4, 'Dentistica Restauradora'),
(5, 'Disfuncao Temporo-Mandibular e Dor-Orofacial'),
(6, 'Endodontia'),
(7, 'Estomatologia'),
(8, 'Implantodontia'),
(9, 'Odontologia do Trabalho'),
(10, 'Odontologia em Saude Coletiva'),
(11, 'Odontologia Legal'),
(12, 'Odontologia para Pacientes com Necessidades Especiais'),
(13, 'Odontogeriatria'),
(14, 'Odontopediatria'),
(15, 'Ortodontia'),
(16, 'Ortodontia e Ortopedia Facial'),
(17, 'Ortopedia Funcional dos Maxilares'),
(18, 'Patologia Bucal'),
(19, 'Periodontia'),
(20, 'Protese Buco Maxilo Facial'),
(21, 'Protese Dentaria'),
(22, 'Radiologia'),
(23, 'Radiologia Odontologica e Imaginologia'),
(24, 'Saúde Coletiva');

# ############################

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `codigo` int(15) NOT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `quantidade` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque_dent`
--

CREATE TABLE `estoque_dent` (
  `codigo` int(15) NOT NULL,
  `codigo_dentista` varchar(11) DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `quantidade` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `evolucao`
--

CREATE TABLE `evolucao` (
  `codigo_paciente` int(10) NOT NULL,
  `codigo` int(10) NOT NULL,
  `procexecutado` varchar(150) DEFAULT NULL,
  `procprevisto` varchar(150) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `codigo_dentista` varchar(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `exameobjetivo`
--

CREATE TABLE `exameobjetivo` (
  `codigo_paciente` int(10) NOT NULL,
  `pressao` varchar(150) DEFAULT NULL,
  `peso` varchar(150) DEFAULT NULL,
  `altura` varchar(150) DEFAULT NULL,
  `edema` varchar(150) DEFAULT NULL,
  `face` varchar(150) DEFAULT NULL,
  `atm` varchar(150) DEFAULT NULL,
  `linfonodos` varchar(150) DEFAULT NULL,
  `labio` varchar(150) DEFAULT NULL,
  `mucosa` varchar(150) DEFAULT NULL,
  `soalhobucal` varchar(150) DEFAULT NULL,
  `palato` varchar(150) DEFAULT NULL,
  `orofaringe` varchar(150) DEFAULT NULL,
  `lingua` varchar(150) DEFAULT NULL,
  `gengiva` varchar(150) DEFAULT NULL,
  `higienebucal` varchar(150) DEFAULT NULL,
  `habitosnocivos` varchar(150) DEFAULT NULL,
  `aparelho` enum('Sim','Não') DEFAULT NULL,
  `lesaointra` varchar(150) DEFAULT NULL,
  `observacoes` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `exames`
--

CREATE TABLE `exames` (
  `exame` text,
  `codigo_paciente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `codigo` int(15) NOT NULL,
  `nomefantasia` varchar(80) DEFAULT NULL,
  `cpf` varchar(50) NOT NULL DEFAULT '',
  `razaosocial` varchar(80) DEFAULT NULL,
  `atuacao` varchar(80) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `cidade` varchar(40) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `inscricaoestadual` varchar(40) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomerepresentante` varchar(80) DEFAULT NULL,
  `apelidorepresentante` varchar(50) DEFAULT NULL,
  `emailrepresentante` varchar(100) DEFAULT NULL,
  `celularrepresentante` varchar(15) DEFAULT NULL,
  `telefone1representante` varchar(15) DEFAULT NULL,
  `telefone2representante` varchar(15) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(15) DEFAULT NULL,
  `conta` varchar(15) DEFAULT NULL,
  `favorecido` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `fotospacientes`
--

CREATE TABLE `fotospacientes` (
  `codigo_paciente` int(10) NOT NULL,
  `codigo` int(10) NOT NULL,
  `foto` mediumblob NOT NULL,
  `legenda` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `foto_padrao`
--

CREATE TABLE `foto_padrao` (
  `foto` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(80) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `usuario` varchar(15) CHARACTER SET latin7 COLLATE latin7_general_cs DEFAULT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `rg` varchar(50) DEFAULT NULL,
  `estadocivil` enum('solteiro','casado','divorciado','viuvo') DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `sexo` enum('Masculino','Feminino') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomemae` varchar(80) DEFAULT NULL,
  `nascimentomae` date DEFAULT NULL,
  `nomepai` varchar(80) DEFAULT NULL,
  `nascimentopai` date DEFAULT NULL,
  `enderecofamiliar` varchar(220) DEFAULT NULL,
  `funcao1` varchar(80) DEFAULT NULL,
  `funcao2` varchar(80) DEFAULT NULL,
  `admissao` date DEFAULT NULL,
  `demissao` date DEFAULT NULL,
  `observacoes` text,
  `ultacesso` datetime DEFAULT NULL,
  `ativo` enum('Sim','Não') DEFAULT NULL,
  `foto` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `honorarios`
--

CREATE TABLE `honorarios` (
  `codigo` varchar(10) NOT NULL DEFAULT '',
  `procedimento` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `honorarios_convenios`
--

CREATE TABLE `honorarios_convenios` (
  `codigo_convenio` int(11) NOT NULL,
  `codigo_procedimento` varchar(10) NOT NULL,
  `valor` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `implantodontia` (
  `codigo_paciente` int(10) NOT NULL,
  `tratamento` enum('Sim','Não') DEFAULT NULL,
  `regioes` varchar(200) DEFAULT NULL,
  `expectativa` varchar(200) DEFAULT NULL,
  `areas` varchar(200) DEFAULT NULL,
  `marca` varchar(200) DEFAULT NULL,
  `enxerto` enum('Sim','Não') DEFAULT NULL,
  `tipoenxerto` varchar(200) DEFAULT NULL,
  `observacoes` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `inquerito`
--

CREATE TABLE `inquerito` (
  `codigo_paciente` int(10) NOT NULL,
  `tratamento` enum('Sim','Não') DEFAULT NULL,
  `motivotrat` varchar(150) DEFAULT NULL,
  `hospitalizado` enum('Sim','Não') DEFAULT NULL,
  `motivohosp` varchar(150) DEFAULT NULL,
  `cardiovasculares` enum('Sim','Não') DEFAULT NULL,
  `sanguineo` enum('Sim','Não') DEFAULT NULL,
  `reumatico` enum('Sim','Não') DEFAULT NULL,
  `respiratorio` enum('Sim','Não') DEFAULT NULL,
  `qualresp` varchar(150) DEFAULT NULL,
  `gastro` enum('Sim','Não') DEFAULT NULL,
  `qualgastro` varchar(150) DEFAULT NULL,
  `renal` enum('Sim','Não') DEFAULT NULL,
  `diabetico` enum('Sim','Não') DEFAULT NULL,
  `contagiosa` enum('Sim','Não') DEFAULT NULL,
  `qualcont` varchar(150) DEFAULT NULL,
  `anestesia` enum('Sim','Não') DEFAULT NULL,
  `complicacoesanest` varchar(150) DEFAULT NULL,
  `alergico` enum('Sim','Não') DEFAULT NULL,
  `qualalergico` varchar(150) DEFAULT NULL,
  `observacoes` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `laboratorios`
--

CREATE TABLE `laboratorios` (
  `codigo` int(15) NOT NULL,
  `nomefantasia` varchar(80) DEFAULT NULL,
  `cpf` varchar(50) NOT NULL DEFAULT '',
  `razaosocial` varchar(80) DEFAULT NULL,
  `atuacao` varchar(80) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `cidade` varchar(40) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `inscricaoestadual` varchar(40) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomerepresentante` varchar(80) DEFAULT NULL,
  `apelidorepresentante` varchar(50) DEFAULT NULL,
  `emailrepresentante` varchar(100) DEFAULT NULL,
  `celularrepresentante` varchar(15) DEFAULT NULL,
  `telefone1representante` varchar(15) DEFAULT NULL,
  `telefone2representante` varchar(15) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(15) DEFAULT NULL,
  `conta` varchar(15) DEFAULT NULL,
  `favorecido` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratorios_procedimentos`
--

CREATE TABLE `laboratorios_procedimentos` (
  `codigo` int(11) NOT NULL,
  `codigo_laboratorio` int(11) NOT NULL,
  `codigo_paciente` int(11) NOT NULL,
  `codigo_dentista` int(11) NOT NULL,
  `procedimento` text NOT NULL,
  `datahora` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratorios_procedimentos_status`
--

CREATE TABLE `laboratorios_procedimentos_status` (
  `codigo` int(11) NOT NULL,
  `codigo_procedimento` int(11) NOT NULL,
  `status` text NOT NULL,
  `datahora` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `laudos`
--

CREATE TABLE `laudos` (
  `laudo` text,
  `codigo_paciente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `odontograma`
--

CREATE TABLE `odontograma` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `idPaciente` int(11) DEFAULT NULL,
  `dente` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `orcamento`
--

CREATE TABLE `orcamento` (
  `codigo` int(10) NOT NULL,
  `codigo_paciente` int(10) NOT NULL,
  `data` date DEFAULT NULL,
  `formapagamento` enum('À vista','Cheque pré-datado','Promissória','Desconto em folha','Cartão') DEFAULT NULL,
  `aserpago` enum('Particular','Convênio') DEFAULT NULL,
  `valortotal` float DEFAULT NULL,
  `parcelas` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20') DEFAULT NULL,
  `desconto` float DEFAULT NULL,
  `codigo_dentista` varchar(11) DEFAULT NULL,
  `confirmado` enum('Sim','Não') NOT NULL DEFAULT 'Sim',
  `entrada` float DEFAULT '0',
  `entrada_tipo` enum('R$','%') NOT NULL DEFAULT 'R$',
  `baixa` enum('Sim','Não') NOT NULL DEFAULT 'Sim'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `ortodontia`
--

CREATE TABLE `ortodontia` (
  `codigo_paciente` int(10) NOT NULL,
  `tratamento` enum('Sim','Não') DEFAULT NULL,
  `previsao` varchar(200) DEFAULT NULL,
  `razoes` varchar(200) DEFAULT NULL,
  `motivacao` varchar(200) DEFAULT NULL,
  `perfil` varchar(200) DEFAULT NULL,
  `simetria` varchar(200) DEFAULT NULL,
  `tipologia` varchar(200) DEFAULT NULL,
  `classe` varchar(200) DEFAULT NULL,
  `mordida` varchar(200) DEFAULT NULL,
  `spee` varchar(200) DEFAULT NULL,
  `overbite` varchar(200) DEFAULT NULL,
  `overjet` varchar(200) DEFAULT NULL,
  `media` varchar(200) DEFAULT NULL,
  `atm` varchar(200) DEFAULT NULL,
  `radio` text,
  `modelo` text,
  `observacoes` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `codigo` int(10) NOT NULL,
  `nome` varchar(80) DEFAULT NULL,
  `cpf` varchar(50) NOT NULL DEFAULT '0',
  `rg` varchar(50) NOT NULL DEFAULT '0',
  `estadocivil` enum('solteiro','casado','divorciado','viuvo') DEFAULT NULL,
  `sexo` enum('Masculino','Feminino') DEFAULT NULL,
  `etnia` enum('africano','asiatico','caucasiano','latino','orientemedio','multietnico') DEFAULT NULL,
  `profissao` varchar(80) DEFAULT NULL,
  `naturalidade` varchar(80) DEFAULT NULL,
  `nacionalidade` varchar(80) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `cidade` varchar(40) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `falecido` enum('Sim','Não') DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `hobby` varchar(250) DEFAULT NULL,
  `indicadopor` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `obs_etiqueta` varchar(90) DEFAULT NULL,
  `tratamento` set('Ortodontia','Implantodontia','Dentística','Prótese','Odontopediatria','Cirurgia','Endodontia','Periodontia','Radiologia','DTM','Odontogeriatria','Ortopedia') DEFAULT NULL,
  `codigo_dentistaprocurado` int(11) NOT NULL DEFAULT '-1',
  `codigo_dentistaatendido` int(11) NOT NULL DEFAULT '-1',
  `codigo_dentistaencaminhado` int(11) NOT NULL DEFAULT '-1',
  `nomemae` varchar(80) DEFAULT NULL,
  `nascimentomae` date DEFAULT NULL,
  `profissaomae` varchar(150) DEFAULT NULL,
  `nomepai` varchar(80) DEFAULT NULL,
  `nascimentopai` date DEFAULT NULL,
  `profissaopai` varchar(150) DEFAULT NULL,
  `telefone1pais` varchar(15) DEFAULT NULL,
  `telefone2pais` varchar(15) DEFAULT NULL,
  `enderecofamiliar` varchar(150) DEFAULT NULL,
  `datacadastro` date DEFAULT NULL,
  `dataatualizacao` date DEFAULT NULL,
  `status` enum('Avaliação','Em tratamento','Concluído','Em revisão') DEFAULT NULL,
  `objetivo` text,
  `observacoes` text,
  `codigo_convenio` int(11) NOT NULL DEFAULT '-1',
  `outros` varchar(100) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `titular` varchar(80) DEFAULT NULL,
  `validadeconvenio` varchar(25) DEFAULT NULL,
  `foto` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `parcelas_orcamento`
--

CREATE TABLE `parcelas_orcamento` (
  `codigo` int(100) NOT NULL,
  `codigo_orcamento` int(10) NOT NULL,
  `datavencimento` date DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `pago` enum('Sim','Não') DEFAULT NULL,
  `datapgto` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `parcelas_ordem`
--

CREATE TABLE `parcelas_ordem` (
  `id` int(11) NOT NULL,
  `id_ordem` int(11) NOT NULL,
  `data` date NOT NULL,
  `valor` varchar(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comissao` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `patrimonio`
--

CREATE TABLE `patrimonio` (
  `codigo` int(10) NOT NULL,
  `setor` varchar(40) DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `dataaquisicao` date DEFAULT NULL,
  `tempogarantia` varchar(30) DEFAULT NULL,
  `cor` varchar(30) DEFAULT NULL,
  `quantidade` varchar(20) DEFAULT NULL,
  `fornecedor` varchar(50) DEFAULT NULL,
  `numeronotafiscal` varchar(30) DEFAULT NULL,
  `dimensoes` varchar(30) DEFAULT NULL,
  `observacoes` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `nivel` varchar(30) NOT NULL,
  `area` enum('profissionais','pacientes','funcionarios','fornecedores','agenda','patrimonio','estoque','laboratorios','convenios','honorarios','contas_pagar','contas_receber','caixa','cheques','pagamentos','arquivos_clinica','manuais','contatos','backup_gerar','backup_restaurar','informacoes','idiomas') NOT NULL DEFAULT 'profissionais',
  `permissao` set('L','V','E','I','A') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `permissoes`
--

INSERT INTO `permissoes` (`nivel`, `area`, `permissao`) VALUES
('Dentista', 'idiomas', 'L,E'),
('Dentista', 'informacoes', 'L,E'),
('Dentista', 'backup_restaurar', 'L'),
('Dentista', 'backup_gerar', 'L'),
('Dentista', 'contatos', 'L,V,E,I,A'),
('Dentista', 'manuais', 'L,V'),
('Dentista', 'arquivos_clinica', 'L,V,I,A'),
('Dentista', 'pagamentos', 'L'),
('Dentista', 'cheques', 'L,V,E,I,A'),
('Dentista', 'caixa', 'L,I,A'),
('Dentista', 'contas_receber', 'L,V,E,I,A'),
('Dentista', 'contas_pagar', 'L,V,E,I,A'),
('Dentista', 'honorarios', 'L,V,E,I,A'),
('Dentista', 'convenios', 'L,V,E,I,A'),
('Dentista', 'laboratorios', 'L,V,E,I,A'),
('Dentista', 'estoque', 'L,V,E,I,A'),
('Dentista', 'patrimonio', 'L,V,E,I,A'),
('Dentista', 'agenda', 'L,E'),
('Dentista', 'fornecedores', 'L,V,E,I,A'),
('Dentista', 'funcionarios', 'L,V,E,I,A'),
('Dentista', 'pacientes', 'L,V,E,I,A'),
('Dentista', 'profissionais', 'L,V,E,I,A'),
('Funcionario', 'backup_gerar', 'L'),
('Funcionario', 'contatos', 'L,V,I'),
('Funcionario', 'manuais', 'L'),
('Funcionario', 'arquivos_clinica', 'L'),
('Funcionario', 'pagamentos', 'L'),
('Funcionario', 'honorarios', 'L,V'),
('Funcionario', 'convenios', 'L,V'),
('Funcionario', 'laboratorios', 'L,V,E,I'),
('Funcionario', 'estoque', 'L,V,E,I,A'),
('Funcionario', 'fornecedores', 'L,V,E,I'),
('Funcionario', 'funcionarios', 'L,V'),
('Funcionario', 'pacientes', 'L,V,E,I'),
('Funcionario', 'profissionais', 'L,V');

-- --------------------------------------------------------

--
-- Estrutura para tabela `procedimentos_orcamento`
--

CREATE TABLE `procedimentos_orcamento` (
  `codigo` int(10) NOT NULL,
  `codigo_orcamento` int(10) NOT NULL,
  `codigoprocedimento` varchar(10) DEFAULT NULL,
  `dente` varchar(15) DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `particular` float DEFAULT NULL,
  `convenio` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `radiografias`
--

CREATE TABLE `radiografias` (
  `codigo` int(11) NOT NULL,
  `codigo_paciente` int(11) NOT NULL,
  `foto` longblob NOT NULL,
  `legenda` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `modelo` enum('Panoramica','Oclusal','Periapical','Interproximal','ATM','PA','AP','Lateral') NOT NULL,
  `diagnostico` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `receitas`
--

CREATE TABLE `receitas` (
  `receita` longtext,
  `codigo_paciente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estrutura para tabela `tb_comissao`
--

CREATE TABLE `tb_comissao` (
  `id` int(11) NOT NULL,
  `id_ordem` int(11) NOT NULL,
  `id_dentista` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `valor` varchar(11) NOT NULL,
  `data` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `tb_ordens`
--

CREATE TABLE `tb_ordens` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `paciente` int(11) NOT NULL,
  `dentista` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `modo_pagamento` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `tb_ordens_procedimentos`
--

CREATE TABLE `tb_ordens_procedimentos` (
  `id` int(11) NOT NULL,
  `id_ordem` int(11) NOT NULL,
  `valor` varchar(11) NOT NULL,
  `tag` varchar(11) NOT NULL,
  `procedimento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estrutura para tabela `telefones`
--

CREATE TABLE `telefones` (
  `codigo` int(10) NOT NULL,
  `nome` varchar(80) DEFAULT NULL,
  `endereco` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE VIEW v_agenda AS ( SELECT tp.codigo AS codigo_paciente, ta.data AS data, ta.hora AS hora, ta.descricao AS descricao, ta.procedimento AS procedimento, ta.faltou AS faltou, td.nome AS nome_dentista, td.sexo AS sexo_dentista FROM agenda ta INNER JOIN pacientes tp ON tp.codigo = ta.codigo_paciente INNER JOIN dentistas td ON td.codigo = ta.codigo_dentista );

# ############################

#
# Estrutura para visualizar `v_evolucao`
#

CREATE VIEW v_evolucao AS ( SELECT tp.codigo AS codigo_paciente, tp.nome AS paciente, td.sexo AS sexo_dentista, td.nome AS dentista, te.procexecutado AS executado, te.procprevisto AS previsto, te.data AS data FROM evolucao te INNER JOIN dentistas td ON te.codigo_dentista = td.codigo INNER JOIN pacientes tp ON te.codigo_paciente = tp.codigo );

# ############################

#
# Estrutura para visualizar `v_orcamento`
#

CREATE VIEW v_orcamento AS ( SELECT tpo.codigo_orcamento AS codigo_orcamento, tor.parcelas AS parcelas, tor.confirmado AS confirmado, tor.baixa AS baixa, tpo.codigo AS codigo_parcela, tpo.datavencimento AS data, tpo.valor AS valor, tpo.pago AS pago, tpo.datapgto AS datapgto, tp.codigo AS codigo_paciente, tp.nome AS paciente, td.nome AS dentista, td.sexo AS sexo_dentista FROM parcelas_orcamento tpo INNER JOIN orcamento tor ON tpo.codigo_orcamento = tor.codigo INNER JOIN pacientes tp ON tor.codigo_paciente = tp.codigo JOIN dentistas td ON tor.codigo_dentista = td.codigo );

ALTER TABLE `agenda`
  ADD PRIMARY KEY (`data`,`hora`,`codigo_dentista`);

--
-- Índices de tabela `agenda_obs`
--
ALTER TABLE `agenda_obs`
  ADD PRIMARY KEY (`data`,`codigo_dentista`);

--
-- Índices de tabela `agradecimentos`
--
ALTER TABLE `agradecimentos`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `ajuda`
--
ALTER TABLE `ajuda`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `atestados`
--
ALTER TABLE `atestados`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `caixa_dent`
--
ALTER TABLE `caixa_dent`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `cheques`
--
ALTER TABLE `cheques`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `cheques_dent`
--
ALTER TABLE `cheques_dent`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `contaspagar`
--
ALTER TABLE `contaspagar`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `contaspagar_dent`
--
ALTER TABLE `contaspagar_dent`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `contasreceber`
--
ALTER TABLE `contasreceber`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `contasreceber_dent`
--
ALTER TABLE `contasreceber_dent`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `convenios`
--
ALTER TABLE `convenios`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `dados_clinica`
--
ALTER TABLE `dados_clinica`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `dentistas`
--
ALTER TABLE `dentistas`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `dentista_atendimento`
--
ALTER TABLE `dentista_atendimento`
  ADD PRIMARY KEY (`codigo_dentista`,`dia_semana`);

--
-- Índices de tabela `encaminhamentos`
--
ALTER TABLE `encaminhamentos`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `estoque_dent`
--
ALTER TABLE `estoque_dent`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `evolucao`
--
ALTER TABLE `evolucao`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `exameobjetivo`
--
ALTER TABLE `exameobjetivo`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `exames`
--
ALTER TABLE `exames`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `fotospacientes`
--
ALTER TABLE `fotospacientes`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `honorarios`
--
ALTER TABLE `honorarios`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `honorarios_convenios`
--
ALTER TABLE `honorarios_convenios`
  ADD PRIMARY KEY (`codigo_convenio`,`codigo_procedimento`);

--
-- Índices de tabela `implantodontia`
--
ALTER TABLE `implantodontia`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `inquerito`
--
ALTER TABLE `inquerito`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `laboratorios_procedimentos`
--
ALTER TABLE `laboratorios_procedimentos`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `laboratorios_procedimentos_status`
--
ALTER TABLE `laboratorios_procedimentos_status`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `laudos`
--
ALTER TABLE `laudos`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `odontograma`
--
ALTER TABLE `odontograma`
  ADD PRIMARY KEY (`id`,`dente`);

--
-- Índices de tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `ortodontia`
--
ALTER TABLE `ortodontia`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `parcelas_orcamento`
--
ALTER TABLE `parcelas_orcamento`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `parcelas_ordem`
--
ALTER TABLE `parcelas_ordem`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `patrimonio`
--
ALTER TABLE `patrimonio`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`nivel`,`area`);

--
-- Índices de tabela `procedimentos_orcamento`
--
ALTER TABLE `procedimentos_orcamento`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `radiografias`
--
ALTER TABLE `radiografias`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices de tabela `receitas`
--
ALTER TABLE `receitas`
  ADD PRIMARY KEY (`codigo_paciente`);

--
-- Índices de tabela `tb_comissao`
--
ALTER TABLE `tb_comissao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_ordens`
--
ALTER TABLE `tb_ordens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_ordens_procedimentos`
--
ALTER TABLE `tb_ordens_procedimentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `telefones`
--
ALTER TABLE `telefones`
  ADD PRIMARY KEY (`codigo`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `ajuda`
--
ALTER TABLE `ajuda`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `codigo` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de tabela `caixa_dent`
--
ALTER TABLE `caixa_dent`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `cheques`
--
ALTER TABLE `cheques`
  MODIFY `codigo` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de tabela `cheques_dent`
--
ALTER TABLE `cheques_dent`
  MODIFY `codigo` int(50) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `contaspagar`
--
ALTER TABLE `contaspagar`
  MODIFY `codigo` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de tabela `contaspagar_dent`
--
ALTER TABLE `contaspagar_dent`
  MODIFY `codigo` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `contasreceber`
--
ALTER TABLE `contasreceber`
  MODIFY `codigo` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `contasreceber_dent`
--
ALTER TABLE `contasreceber_dent`
  MODIFY `codigo` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `convenios`
--
ALTER TABLE `convenios`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `dados_clinica`
--
ALTER TABLE `dados_clinica`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `dentistas`
--
ALTER TABLE `dentistas`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de tabela `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `estoque_dent`
--
ALTER TABLE `estoque_dent`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `evolucao`
--
ALTER TABLE `evolucao`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `fotospacientes`
--
ALTER TABLE `fotospacientes`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de tabela `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `codigo` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `laboratorios_procedimentos`
--
ALTER TABLE `laboratorios_procedimentos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `laboratorios_procedimentos_status`
--
ALTER TABLE `laboratorios_procedimentos_status`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `odontograma`
--
ALTER TABLE `odontograma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de tabela `parcelas_orcamento`
--
ALTER TABLE `parcelas_orcamento`
  MODIFY `codigo` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT de tabela `parcelas_ordem`
--
ALTER TABLE `parcelas_ordem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de tabela `patrimonio`
--
ALTER TABLE `patrimonio`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `procedimentos_orcamento`
--
ALTER TABLE `procedimentos_orcamento`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de tabela `radiografias`
--
ALTER TABLE `radiografias`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tb_comissao`
--
ALTER TABLE `tb_comissao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `tb_ordens`
--
ALTER TABLE `tb_ordens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `tb_ordens_procedimentos`
--
ALTER TABLE `tb_ordens_procedimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `telefones`
--
ALTER TABLE `telefones`
  MODIFY `codigo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

INSERT INTO `foto_padrao` (`foto`) VALUES (0x89504e470d0a1a0a0000000d49484452000000c8000000c80806000000ad58ae9e0000000473424954080808087c086488000000097048597300000b1300000b1301009a9c18000011e349444154789ced9d7b945e5575c07f9944328149300f9210080f4312089487851a4091208286e2232a6d5564d165b0cbea5ad65a6d4bdaa50bdbfaac14aa56b096562a1a14041fd5a0f8280d18a949550884400279910c84494232338199e91f7b3ef8f2e5dcefbbe7becee3eedf5a7bcdb7eedcc7befb9e7d1fe7ecb3f718942a980ebc1c380598071c0b1c054c052601e3812ee079a01fe8037a814dc046602df01b60cde8ff152568a6006f07fe0d7814182948f603bf043e095c008cabea8414252f3dc095c05dc07314e714ed64277013f01a604ce967a8281958007c11d843354e91241b80bf0026977bba8a928e33813b8161dc3a46abec013e051c51dea92b4a322f0396e3de113ac96ee06f8009e59841510e643cf0316000f78ddff6d5eb0d25d843515ee015c083b86fec79e4ebc0b4a20da3d49b2e6019323ee1ba8117215b90ee6145c9cd64e007b86fd445cb107035da2dace4603eb01ef78db94cb915e82eca604a7d3807781af70db80a59898cfa2b4a2a2e04f6e2bee15629bf066614613c256e2e4402015d375817b21609a8541423e750bf2747abfc1f70785e432af1319ffa7c737492bb8143f299538989c9c4df5b652bff9acba24a347411e7384711f2a739ecaa44c232dc37445f65109901a9d49485c4133e52963c8c460233d6b5020e9800fc109d2fd189a9c044e43554a9117f8ffbbb7328328444322b35e14424f181eb861792aca69e6f1a40fd4efc26c44994f4cc44d20ffdcab5224ab99c8ffbbb71a8b20d38d4dae21150a727c85781635c2b11283dc033c0bdae1551cae102dcdf854397edd470fe48976b052ae243ae158880e9c0e5ae95508a671efee5ae0a557e6b69fbe0a9c313e42aece75fff18b88c1713c3854e1ff04124a95c1e4e06cecdaf8ee20be39077679bbbe45fb7ece378647071abe57e7c91db904cf2204e92777f1aed1b11176377f1af6fb3afb1c062e03f81672df7eb42ee47bab69b1987cc1eccb3df3e24899e12015f26fd85df021c9672bf138025c07fe0df64ab9f00af6ba3fbe2028e71694a3b291ed385ddebd555198f331699b2fb31e01e2454bc6aa7781cf80ce9a3047e94f37837a4378fe22b6792fe82ef4522578b6002701ef011e09bc06314df8bb617991e7b357056061dcfc8a9d3a60cc70c92982b14d9a4d8fc0ef97b781af4033f1f95063dc8dd7d2e921d7e36f2e17c0492976a223208370e69b8cf21df39bb91a7e036c4d1d621697ad62291b659598d64a8ff838cdb1f8d749fafcba183e298ef92fe8eb8d4918e2e994fbe496375b45954ec20fdc59ee74847d7dc4c7607b9d181be4a41ccc6ee7dbeae899c4f22fbb7c86a07fa2a05f17ad25fe8358e74f485dbc9e62003d4201a3cd65093932cd65d5f9a1661f0e98cdb8d473a1ca22656079963b1eef6d2b408839548edf52c1c57a01e5e12ab83ccb658b7b7342dc2e1f319b7b3b17390c4ea2036e9fcfb4ad3221c9603bb326c177dd984581d64aac5ba83a569110efd48914f5b6cec1c24b13a884dd8c840695a84c5d7326c33a9702d3c235607a9dddce902b807fb0e8be8ed1cab83d8f4cf47df979f9261e07b96dbc41ccb07c4eb2036d364a3bfc816acb05c3f86e9c86d89d5416cbe2bd24e92aa03f7242cef45a28b5bd95fa22e5e10ab83982e6612ea202fb20509f26ca61f994168eaed2b6a8a80b7c4ea20cf58acab852b0fa4798ec730f00ee017987b066dec1c24b13a88cde878f47df99634f764fd3912cc7818e6e29ead4f9be888d541b65aac3bad342dc2a4f12a751d70ede8efa41aea36760e92581de4718b758f2c4d8b3099047c1bf8b3a6654949bf6dec1c24b13ac8168b756795a64598ec06dece815db849418936760e92581de4c984e52b809d2dcb66607ebfae233dc007909eab66661ad67d1e78aa748d1c13ab8324bd1b7f02b9d86f45e641804cb73db60aa502e059cc1d1c2607e945070a8325c9418e4252ea7c0b49c27c09d2ad6933c1aa8e98be413657ae8503627590ed9807b65a2ff4f781d3909ae04a32c719966dac580727c4ea2023c006c3f2130ccb0612d6555ee438c3b28d15ebe084581d04e051c332938328ed998264806ca516c92e627610535acc05956b113e4909b1d756aa8523627690070ccba6a20383b69c92b0fca14ab57044cc0e92544fefd44ab5089f330ccbb653936c30313bc86f306740cf522ea0ce9c6e58569bb4a3313bc83eccefc9bf57b52201331ef313441d24124c1903cfa6bec9aa6d3913733dc255552be28ad81de45ec3b269686f565a5e95b07c65c2f2e888dd41fe3b61f9f9552a1130af312c5b4f0d264ad5095321cf3b9c6a1406e391ef382d9c1339b770f045de8386b87722a9c64ad6ba864112fb2b1698733df56057e4b38e986aa18f20d5759588380af39df05f5c2ae539639070f6569bfdc2a5524a79dccfc1177b3b9a5531895762bea92c73a9940beaf08a059284a095e9c085552b120849df197756aa8552192761be23deec52294f190f3ccdc1b6aa4570629d59c3c117bd1f78a94ba53ce46d986f26d7b8544a299f0f63bef0ef77a99487dc8dd94ef35d2aa594cf2c24554deb857f188dcd6a90f42a6a0ad95122e43b981bc062974a79c48d98edb3d4a5524a755c8ab901fccca5529e30134960d16a9b5d688988dad0053c86d949ce75a8970f7c1ab35dae73a994523d1fc4dc10ea1c423103d8cbc1361902e63ad44b71c044a4f08bc949ea3a70781d667b7ccba5528a3b3e8eb941aca13ed1050de621b5064df6d0e9c935651a12f2ae3d36f05dcc76f8be4ba514f75c83b9613c457daa4ebd11b30df4e9a17038e20ca6c6f155877a55c524e009cce7ff4d877a291ef17e92efa06f70a857157c09f379ef47cb4128a38c031ec4dc509e24b97865e8240d988e20e3218af202e793dc5856105f9cd62c246da8e97c3723d39115e500fe9d6427f9a83bb50ae71024f030e95c97b8534df19929c036cc8d6698781ace5748768ee50ef55202e0f7496e3c7b8185ee542b84ab493ebfed988be428ca017c91e446d40b9cec4eb55c2c25f9bc8681d7b9534d09896ecc53731bb28df066d6bd0b093a4c3aa7cfb8534d099139c04edabf8e986a66f8c89f204f88a473b91b4d7da464e022ccd3731bd287df91bf63480ec86cc806f4bb43c9c155b46f60cf01ef73a65d323d488f543bdd77925c9c5351529314d0d82c5f47e2ba7ce0349223031ab28fe4ba1f8a62cde7e8ec244f0017bb52107809f057c020edf51cc4ad9e4aa45c4b672719016e058ead58b70b908abe9d74db879434509452f828e99c641099c23abb647d5e09fc30a54ebbd0aa5a4a05fc31c9d3534d1ff15f43ca981515f078187025ede3a95ae571e094828eaf281d7935120a9fb6818e005b907a246fc6be6b751ef06ee076cc25d1dac94f918c254a46620be5ae8a2391a7c3f919b7df0c3c006c429c6d2ff264ea4666facd028e47eefc59926b0f23f33a9621e3398a52395d488ead7eeceeea65cb63c079259eb712394700670397231fde9373ee6f0ef03ddc3bc67ee01f8043739e4f33d3101bbd138966ae4b328bda3003782bf04fc02aa437a7b5615d54d0b12e06fed7b0ffb2651879dd2b631eb9690a401f52abf05ae02dc43b2d394aba910fe1af008f90ae81fd6d81c71f8334aa9fa63c761e1900be8c942c288b4ef15c0d79785497372215aa148f380449427033b01bfb86b60919892e9a05c88772526a9dac4f8b554836962925e8dc4c37c9b32cdb491f32857931e5d85549c909c0f524e7d9b5912b4ad6f5e5c05f22df2a490913921c623de2fc4b81a34bd6b399f758e899243b9157b1e32bd4bb5042ece63d0fe93dba94e2f2e86e40ba54f715b4bf4ecc40b2a6cf423e7c27204fc201e059a4eb7733b00ee902ae9a8948377451d10043c01dc067819505ed5369e112e4f5a2ac77facf55772aded36eea715eb90f9de25b28a7013fa2bc0bd69021c24fcc5004afa6fd2cc4a2e40768084c2e8e447aa3dacda72e5a3650fec7afcf4c473a2daab2f7f3c00d68388c35ef21b93c411577b6bad50801184b7209e8b265171284a974e048a42e858b8bd42c1f2ffb443d24a9466195f26d74e03191b7915c8ec0855c51eee97a45a7f9f755ca0ee04de59e6e58bc84e454fc2e653fc585a1f8cca5b4cfe0e24afe194d49c454aa09cbc82a7b9041be585988b9baad2f7217f98349836501f028ee2f4227d931aa6b6c9c4efb0479bec83ac2cb66999bd7628eacf555b62133fb62e114ec425e5ccb33c0a2522ce121af47422a5c1bdd5636032f2bc11e55331ffb29c33ec83efcce665908a13a47439e4062a8426501b015f776cce324af2ddc2a9eb098b09da3215b09f39be474e47bcab5fdf24a3f113ac922e2708e86ec209c0cef2035cf43f820b7719268d2a7ce23ae8bd3906780730bb453592c22ac0e91b4d24b04df8493916e3ad7c62c4bf62161f8beb284b89edcadf200fe240eb7661cf063dc1bb16c790ec990e21befc6cf11f2a2e5bf9040cbe0b81ef7c6ab4a86810f1763b64258867b9b5429c195915b8c7ba3b990cfe3f66e360eb8d1a057ec328ce4400e826964cb86118bdc41b1c9dbd2d283bc6eb83e7f57b28940e2b66ec3bdb15ccb2a60665e435a7034b0ba84f3084d6ec96bc8b2b902f746f2451e074ecd67ce549c49d8a3e345cb1fe63367794cc6af094f3ec86ecaed065e82dfe1ea2ee449246d9177a4a9e5574719023e94c3ae492ca39aec2321ca2772d8b514e691bef2525de5268ac95b3b01f88607e7e3b30ce05936c73b716f941064259298222bb37193413e44599ed1c685b308f7c60849b600afc860e75701db3dd03f243927839d0b6705ee0d119a0c200541d3f25ef415368bdc6961e3523815f7460859be8424ad4e6202524ac0b59ea1ca308ee7b3ebc5cb2ff761cea27e3cf02b0ff40b5d6e30d8b61266a18ffda2a4970367c95d429c73685c483f39b235e609acfb08d9cb202b077228f08ed1df17015f405eaf94fc8c4306537f9665e33c05743602c7e6d85e51aae21132a66dca9ac5fc6cd4399470980bfc6e960db33a88b701614aa93c8a742824c917dca9d6914c6d364b72e02ee0b22c075382e77924815e127baa5224039721b33e476c36caf204398f6ae73a284a111c837c1a5891c541ea5012408913eb8473599f208a1222d66dd7d641ba81b36c0fa2289eb01029d8941a5b075948fbd82145f199439129caa9b175107dbd5242c7aa0ddb3a48a6c11645f108ab366c1b6af2207092e5364a186c054eeeb0ce10edc73aba47a51d9f02965ae855346b8033d2ae6c3350d84504d9b495444680be9cfb18189576eccf798cbc9c60b3b2cd2bd63114937440515cd283455e001b0709b9f498a23493ba2ddb38c89c0c8a288a8fa46ecb360e322583228ae223a9dbb28d83f4645044517c24755bb6e9c55207899b6ee04d1dd6d983540d4b62019d67eef990f13075ee5e7510a5c154e0f60eeb3c0c9cd8e6ffef427215f84eeab6acaf584a1d29c54134485189854ea3fd2f60e3207b3328a2283ef26cda156d1c6477064514c54752b7651b07f17942bea2d8508a83e813448985d4377b7d82287524f5cdde661c646b064594b89804bcb3cdff3bcd27f185d46dd966c2d489c05a7b5d14c53be6008fa559d1c641c622dd63a9fb9015c543f600879332c3a2cd37c81032e5565142e6b758a41fb54ddaf06bcbf515c537acdab0ad83dc63b9bea2f8c6ffd8ac6c9bd5642af024d9b2c22b8a6b068119c0aeb41bd83e419e06eeb6dc46517c610516ce01d99257df9a611b45f181e5b61b64a9513805d884e439559450d8031c8d65c8549627c84ee0ba0cdb298a4bfe910cf18459abdcbe1419899c9c717b45a9925e64f4dc3a9e306b9df40164b0c5ba628fa2386019f0f32c1be6a993de0dac027e27c73e14a56c5623b50907b36c9cc741401201df8fc4b6288a6fec44ca1d6cccba83ac75d21bac47c29fad4aeb2a4a050c037f440ee780ecdf20cdac43a27cb5faade20b23c007805b5c2bd2cc1548ed871115158732883c39bce422a4afd9b59154ea297dc0223c672ef013dc1b4ba55e72178195e8b812096e746d3895b8a517b89c403902f83bd451548a97a7806b902918c17318f03ea45bd8b56155c2964780f75251b06cde81c22cc73b0b5802bc99ceb5241405e021a434c36dc8c0746554ed20ad2c002e001622e1005a665a0179d3b80fb81799a0f7902b455c3b482bd39127cc7ce4e93277f4ef51f8a7ab928f11603332d0fcc8e8df87805f22df175e104aa33b04994b3cb349a62399fe1a3271f4ef04a49efbf8d1ed1abfc722a1355d2dbf9b6d90f4bb0e8cb4f93d3c2a434d7f879081e1c15169fcee47c6c27623e1e5bb9169ae3b907c06db9bfeee2fed6c0ae2ff018ba96615b391f1a40000000049454e44ae426082);

