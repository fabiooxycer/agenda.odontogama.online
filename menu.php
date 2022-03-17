
<style>
.nav-side-menu {
  overflow: auto;
  font-family: "Roboto", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 12px;
  font-weight: 200;
  background-color: #fff;
  position: fixed;
  top: 0px;
  width: 16%;
  height: 100%;
  color: #595454;
}
.nav-side-menu .brand {
  background-color: #ffffff;
  line-height: 50px;
  display: block;
  text-align: center;
  font-size: 14px;
}
.nav-side-menu .toggle-btn {
  display: none;
}
.nav-side-menu ul,
.nav-side-menu li {
  list-style: none;
  padding: 0px;
  margin: 0px;
  line-height: 35px;
  cursor: pointer;
  /*    
    .collapsed{
       .arrow:before{
                 font-family: FontAwesome;
                 content: "\f053";
                 display: inline-block;
                 padding-left:10px;
                 padding-right: 10px;
                 vertical-align: middle;
                 float:right;
            }
     }
*/
}
.nav-side-menu ul :not(collapsed) .arrow:before,
.nav-side-menu li :not(collapsed) .arrow:before {
  font-family: FontAwesome;
  content: "\f078";
  display: inline-block;
  padding-left: 10px;
  padding-right: 10px;
  vertical-align: middle;
  float: right;
}
.nav-side-menu ul .active,
.nav-side-menu li .active {
  border-left: 3px solid #45C3E0;
  background-color: #fff;
  font-weight: bold;
}
.nav-side-menu ul .sub-menu li.active,
.nav-side-menu li .sub-menu li.active {
  color: #4d7eb9;
}
.nav-side-menu ul .sub-menu li.active a,
.nav-side-menu li .sub-menu li.active a {
  color: #4d7eb9;
}
.nav-side-menu ul .sub-menu li,
.nav-side-menu li .sub-menu li {
  background-color: #fff;
  border: none;
  padding-left: 12px;
  line-height: 28px;
  border-bottom: 1px solid transparent;
  margin-left: 0px;
}
.nav-side-menu ul .sub-menu li:hover,
.nav-side-menu li .sub-menu li:hover {
  background-color: #f1f1f1;
}
.nav-side-menu ul .sub-menu li:before,
.nav-side-menu li .sub-menu li:before {
  font-family: FontAwesome;
  content: "\f105";
  display: inline-block;
  padding-left: 10px;
  padding-right: 10px;
  vertical-align: middle;
}
.nav-side-menu li {
  padding-left: 0px;
  border-left: 3px solid transparent;
  border-bottom: 1px solid transparent;
}
.nav-side-menu li a {
  text-decoration: none;
  color: #545454;
}
.nav-side-menu li a i {
  padding-left: 10px;
  width: 20px;
  padding-right: 20px;
}
.nav-side-menu li:hover {
  border-left: 3px solid #4d7eb9;
  /*background-color: #4f5b69;*/
  -webkit-transition: all 1s ease;
  -moz-transition: all 1s ease;
  -o-transition: all 1s ease;
  -ms-transition: all 1s ease;
  transition: all 1s ease;
}
@media (max-width: 767px) {
  .nav-side-menu {
    position: relative;
    width: 100%;
    margin-bottom: 10px;
  }
  .nav-side-menu .toggle-btn {
    display: block;
    cursor: pointer;
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 10 !important;
    padding: 3px;
    background-color: #ffffff;
    color: #000;
    width: 40px;
    text-align: center;
  }
  .brand {
    text-align: left !important;
    font-size: 22px;
    padding-left: 20px;
    line-height: 50px !important;
  }
}
@media (min-width: 767px) {
  .nav-side-menu .menu-list .menu-content {
    display: block;
  }
}
body {
  margin: 0px;
  padding: 0px;
}
</style>


<?php

   include "/lib/config.inc.php";

   $sistema = new sistema();


   $conn = mysqli_connect($sistema->server, $sistema->host, $sistema->pass, $sistema->bd);

  $user = $_SESSION[nome_user];
  $query = mysqli_query($conn, "SELECT cpf, nome FROM funcionarios WHERE nome='$user'");

  $cpf = mysqli_fetch_array($query);

  $nome = mysqli_fetch_array(mysqli_query($conn, "SELECT nome, codigo_dentistaatendido, codigo FROM pacientes WHERE cpf='$cpf[cpf]'"));

  //echo "OK";

  if($_SESSION[nome_user] == "PAC")
  {
    echo'
<div class="nav-side-menu" style="margin-top:48px;">
    <div class="brand"><span>'.substr(strtoupper($nome[nome]), 0, 15).'</span>...<br></div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list" id="menu">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick="location=\'/\'"  class="collapsed active">
                  <a href="/">
                  <i class="fa fa-home fa-lg"></i> Início
                  </a>
                </li>

                <li  data-toggle="collapse" data-target="#products">
                  <a href="#"><i class="fa fa-asterisk fa-lg"></i> Informações do paciente <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="products">
                    <li class="active" onClick="javascript:Ajax(\'mPaciente/meuDentista\',\'conteudo\',\'codigo='.$nome[codigo_dentistaatendido].'&acao=editar\')"><a href="#">Meu Dentista</a></li>
                    <li onclick="javascript:Ajax(\'mPaciente/fichaClinica\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Ficha Clínica</a></li>
                    <li onClick="javascript:Ajax(\'mPaciente/odontograma\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Odontograma</a></li>
                    <li onclick="javascript:Ajax(\'mPaciente/exame\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Exame Objetivo</a></li>
                    
                    <li onclick="javascript:Ajax(\'mPaciente/evolucao\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Evolução do Tratamento</a></li>
                    <li onclick="javascript:Ajax(\'mPaciente/fotos\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Fotos</a></li>
                    <li onclick="javascript:Ajax(\'mPaciente/radio\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Radriografias</a></li>
                    <li onclick="javascript:Ajax(\'mPaciente/orcamento\',\'conteudo\',\'codigo='.$nome[codigo].'&acao=editar\')"><a href="#">Orçamentos</a></li>
                </ul>
                 <li onclick="javascript:Ajax(\'wallpapers/sair\', \'conteudo\', \'\');">
                  <a href="#">
                  <i class="fa fa-sign-out fa-lg" onClick="javascript:Ajax(\'wallpapers/sair\', \'conteudo\', \'\')"></i> Desconectar
                  </a>
                  </li>
                </ul>
                </div>
                </div>
    ';
    
  }else{

  

?>


<div class="nav-side-menu">
    <div class="brand"><!-- TEXTO TITULO --><br></div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list" id="menu">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick="location.reload()"  class="collapsed active">
                  <a href="#">
                  <i class="fa fa-home fa-lg"></i> Início
                  </a>
                </li>

                <li  data-toggle="collapse" data-target="#products">
                  <a href="#"><i class="fa fa-asterisk fa-lg"></i> Cadastros <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="products">
                    <li class="active" onClick="javascript:Ajax('dentistas/gerenciar','conteudo','')"><a href="#">Dentistas</a></li>
                    <li onclick="javascript:Ajax('pacientes/gerenciar','conteudo','')"><a href="#">Pacientes</a></li>
                    <li onclick="javascript:Ajax('pacientes/ordem_servico','conteudo','')"><a href="#">Ordem de Serviço (OS)</a></li>
                    <li onClick="javascript:Ajax('funcionarios/gerenciar','conteudo','')"><a href="#">Funcionários</a></li>
                    <li onclick="javascript:Ajax('fornecedores/gerenciar','conteudo','')"><a href="#">Fornecedores</a></li>
                    
                    <li onclick="javascript:Ajax('patrimonio/gerenciar','conteudo','')"><a href="#">Patrimônio</a></li>
                    <li onclick="javascript:Ajax('estoque/estoque','conteudo','')"><a href="#">Controle de estoque</a></li>
                    <li onclick="javascript:Ajax('laboratorio/gerenciar','conteudo','')"><a href="#">Laboratório</a></li>
                    <li onclick="javascript:Ajax('convenios/gerenciar','conteudo','')"><a href="#">Convênio / Planos</a></li>
                    <li onClick="javascript:Ajax('honorarios/gerenciar','conteudo','')"><a href="#">Tabela de Honorários</a></li>
                </ul>

                  <li onClick="javascript:Ajax('agenda/agenda','conteudo','')"><a href="#"><i class="fa fa-calendar fa-lg"></i> Agenda</a></li>


                <li data-toggle="collapse" data-target="#service" class="collapsed">
                  <a href="#"><i class="fa fa-usd fa-lg"></i> Financeiro <span class="arrow"></span></a>
                </li>  
                <ul class="sub-menu collapse" id="service">
                  <li onClick="javascript:Ajax('contaspagar/contaspagar','conteudo','')">Contas a Pagar</li>
                  <li onclick="javascript:Ajax('contasreceber/contasreceber','conteudo','')">Contas a Receber</li>
                  <li onClick="javascript:Ajax('caixa/caixa','conteudo','')">Fluxo de Caixa</li>
                  <li onClick="javascript:Ajax('dentistas/comissao','conteudo','')">Comissão dos dentistas</li>
                  <li onclick="javascript:Ajax('cheques/cheques','conteudo','')">Controle de Cheques</li>
                  <!--<li onClick="javascript:Ajax('pagamentos/parcelas','conteudo','')">Pagamentos</li>-->
                </ul>

                <li data-toggle="collapse" data-target="#new" class="collapsed">
                  <a href="#"><i class="  glyphicon glyphicon-file"></i> Relatórios <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="new">
                  <li onclick="javascript:Ajax('relatorios/dentistas','conteudo','')">Ganho por dentista</li>
                  <li onclick="javascript:Ajax('relatorios/financeiro','conteudo','')">Financeiro</li>
                  <li onclick="javascript:Ajax('relatorios/comissao','conteudo','')">Comissão</li>
                </ul>

                <li data-toggle="collapse" data-target="#pag" class="collapsed">
                  <a href="#"><i class="glyphicon glyphicon-ok"></i> Pagamentos <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="pag">
                  <li onclick="javascript:Ajax('pagamentos/pagar', 'conteudo','')">Efetuar pagamento</li>
                </ul>

                <li data-toggle="collapse" data-target="#utilitarios" class="collapsed">
                  <a href="#"><i class="fa fa-wrench fa-lg"></i> Utilitários <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="utilitarios">
                  <!--<li onclick="javascript:Ajax('arquivos/daclinica/arquivos','conteudo','')">Arquivos da Clínica</li>-->
                  <li onclick="javascript:Ajax('arquivos/manuais_codigos/manuais','conteudo','')">Manuais e Códigos</li>
                  <li onclick="javascript:Ajax('telefones/gerenciar','conteudo','')">Contatos Úteis</li>
                  <li onclick="javascript:Ajax('backup/backupfazer','conteudo','')">Geração de Backup</li>
                  <li onclick="javascript:Ajax('backup/restaurar','conteudo','')">Restaurar Backup</li>
                </ul>

                <li data-toggle="collapse" data-target="#conf" class="collapsed">
                  <a href="#"><i class="fa fa-cog fa-lg"></i> Configurações <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="conf">
                  <li onclick="javascript:Ajax('configuracoes/senhaadm','conteudo','')">Senha do Administrador</li>
                  <!--<li onclick="javascript:Ajax('configuracoes/licenca','conteudo','')">Informações da licença</li>-->
                  <li onclick="javascript:Ajax('configuracoes/dadosclinica','conteudo','')">Informações da Clínica</li>
                  <li onclick="javascript:Ajax('configuracoes/permissoes','conteudo','')">Permissões</li>
                </ul>


                 <li onclick="javascript:Ajax('wallpapers/sair', 'conteudo', '')">
                  <a href="#">
                  <i class="fa fa-sign-out fa-lg" onClick="javascript:Ajax('wallpapers/sair', 'conteudo', '')"></i> Desconectar
                  </a>
                  </li>


                 
            </ul>
     </div>
</div>
<?php
}
?>
  

  