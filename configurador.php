<?php
   
    if(!isset($_GET['idioma'])) {
        $_GET['idioma'] = 'pt_br';
    }

    require_once('lang/'.$_GET['idioma'].'.php');

    function extraiSQL($arquivo) {
        $file = file($arquivo);
        $sql = array();
        $j = 0;
        $sql[$j] = "";
        for($i = 0; $i < count($file); $i++) {
            $file[$i] = trim($file[$i]);
            if(substr($file[$i], 0, 1) != '#' && substr($file[$i], 0, 2) != '--') {
                $sql[$j] .= $file[$i];
            }
            if(substr($file[$i], -1) == ';' ||substr($file[$i], -2) == '$$') {
                $sql[$j] = trim($sql[$j], " ;\n");
                $j++;
                $sql[$j] = "";
            }
        }
        array_pop($sql);
        return($sql);
    }
    $caminho = 'lib/config.inc.php';
    require_once ( $caminho );
    if(isset($_POST['send'])) {
        if(is_writable($caminho)) {
            $conn = @mysqli_connect($_POST['server'], $_POST['user'], $_POST['pass']);
            if(mysqli_errno($conn) == 1045) {
                $myerro++;
                $r[4] = ' color="#FF0000"';
                $msg[] = $LANG['config']['err_access_denied_to_the_database_server'];
                //Acesso negado
            } elseif(mysqli_errno($conn) == 2005) {
                $myerro++;
                $r[3] = ' color="#FF0000"';
                $msg[] = $LANG['config']['err_database_server_not_found'];
                //Servidor não encontrado
            }
            if($_POST['versao'] == 'novo') {
              //echo $myerro;
                if(!empty($_POST['fantasia']) && $_POST['senha'] == $_POST['resenha'] && strlen($_POST['senha']) >= 6) {
                    
                    $config = file($caminho);
                    $config[51] = "\n    \$server = '".$_POST['server']."';\n";
                    $config[52] = "    \$user = '".$_POST['user']."';\n";
                    $config[53] = "    \$pass = '".$_POST['pass']."';\n";
                    $config[54] = "    \$bd = '".$_POST['bd']."';\n";
                    $config[64] = "    \$install = true;\n";
                    $config[65] = "class sistema {\n";
                    $config[66] = " public \$server = '".$_POST['server']."';\n";
                    $config[67] = " public \$user = '".$_POST['user']."';\n";
                    $config[68] = " public \$pass = '".$_POST['pass']."';\n";
                    $config[69] = " public \$bd = '".$_POST['bd']."';\n";
                    $config[70] = "}\n";

                    file_put_contents($caminho, $config);
                    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS ".$_POST['bd']) or die('Criação da base: '.mysqli_error($conn));
                   
                    mysqli_select_db($conn, $_POST['bd']) or die('Seleção da base: '.mysqli_error($conn));

                    $sqls = extraiSQL('bases/bd_novo.sql');

                    foreach($sqls as $sql) {
                        mysqli_query($conn, $sql) or die('Importação nova: '.mysqli_error($conn));
                    }

                    $sql = "INSERT INTO funcionarios (nome, cpf, usuario, senha) VALUES ('Administrador', '11111111111', 'admin', '".md5($_POST['senha'])."')";
                    mysqli_query($conn, $sql) or die('Alteração de senha: '.mysqli_error($conn));

                    $sql  = "INSERT INTO dados_clinica (cnpj, razaosocial, fantasia, proprietario, endereco, bairro, cidade, estado, cep, fundacao, telefone1, telefone2, fax, email, web, idioma) VALUES ('".$_POST['cnpj']."', '".$_POST['razaosocial']."', '".$_POST['fantasia']."', '".$_POST['proprietario']."', '".$_POST['endereco']."', ";
                    $sql .= "'".$_POST['bairro']."', '".$_POST['cidade']."', '".$_POST['estado']."', '".$_POST['cep']."', '".$_POST['fundacao']."', '".$_POST['telefone1']."', '".$_POST['telefone2']."', ";
                    $sql .= "'".$_POST['fax']."', '".$_POST['email']."', '".$_POST['web']."', '".$_GET['idioma']."')";
                    
                    echo $sql;

                    mysqli_query($conn, $sql) or die('Alteração de dados da clínica: '.mysqli_error($conn));
                    header('Location: ./');

                } else {

                    if(empty($_POST['senha']) || $_POST['senha'] != $_POST['resenha'] || strlen($_POST['senha']) < 6) {
                        $r[0] = ' color="#FF0000"';
                        $msg[] = $LANG['config']['err_password_must_have_at_least_6_characters_and_must_be_retyped'];
                    }
                    if(empty($_POST['fantasia'])) {
                        $r[1] = ' color="#FF0000"';
                        $msg[] = $LANG['config']['err_name_shall_be_filled'];
                    }
                }
            } else {
                if($myerro === 0) {
                    //mysqli_select_db($conn, $_POST['bd']) or die('Seleção da base: '.mysqli_error($conn));
                    $sqls = extraiSQL('bases/bd_atu_'.$_POST['versao'].'.sql');
                    foreach($sqls as $sql) {
                        mysqli_query($conn, $sql) or die('Importação Atualização: '.mysqli_error($conn).' - '.$sql);
                    }
                    $config = file($caminho);
                    $config[51] = "\n    \$server = '".$_POST['server']."';\n";
                    $config[52] = "    \$user = '".$_POST['user']."';\n";
                    $config[53] = "    \$pass = '".$_POST['pass']."';\n";
                    $config[54] = "    \$bd = '".$_POST['bd']."';\n";
                    $config[64] = "    \$install = true;\n";
                    $config[65] = "class sistema {\n";
                    $config[66] = " public \$server = '".$_POST['server']."';\n";
                    $config[67] = " public \$user = '".$_POST['user']."';\n";
                    $config[68] = " public \$pass = '".$_POST['pass']."';\n";
                    $config[69] = " public \$bd = '".$_POST['bd']."';\n";
                    $config[70] = "}\n";
                    file_put_contents($caminho, $config);
                    header('Location: ./');
                }
            }
        }

        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="css/bootstrap.min.css">
<title>GCO :: <?php echo $LANG['config']['configuration_wizard']?></title>
<script language="javascript" type="text/javascript" src="lib/script.js"></script>
<link href="css/smile.css" rel="stylesheet" type="text/css" />
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
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <form method="POST" action="configurador.php?idioma=<?php echo $_GET['idioma']?>">
    <div class="conteudo" id="table dados">
<?php
    if(count($msg) > 0) {
?>
      <div class="sobre" id="div5">
        <fieldset>
        <legend><strong><?php echo $LANG['config']['errors']?></strong></legend>
          <p><?php echo $LANG['config']['below_follow_the_errors_found_in_the_system']?></p>
            <p align="left">
<?php
    foreach($msg as $erro) {
        echo '&nbsp;&nbsp;- '.$erro."<br />\n";
    }
?>
            </p>
        </fieldset>
      </div>
<?php
    }
?>
<br />
      <div class="sobre" id="div5">
        <fieldset>
        <legend><strong><?php echo $LANG['config']['language']?></strong></legend>
          <p><?php echo $LANG['config']['select_your_language']?></p>
            <p align="center">
<?php
    $handle = opendir('./lang');
    while ($file = readdir($handle)) {
        if(strpos($file, '.') !== 0) {
            $nome_file = explode('.', $file);
            $idiomas[] = '<a href="?idioma='.$nome_file[0].'">'.$nome_file[0].'</a>';
        }
  }
    $idiomas = implode(' | ', $idiomas);
    echo $idiomas;
?>
            </p>
        </fieldset>
      </div>
<br />
      <div class="sobre" id="div5">
        <fieldset>
        <legend><strong><?php echo $LANG['config']['initial_information']?></strong></legend>
          <p>Bem vindo ao menu de instalação do sistema odontológico.<br />
            <br />
            <?php echo $LANG['config']['in_case_unix']?>:<br />
            /configurador.php<?php echo is_writable('configurador.php')?' - <i><font color="#009900">'.$LANG['config']['you_have_pemission_to_write_in_this_file'].'</font></i>':' - <i><font color="#FF0000">'.$LANG['config']['you_dont_have_permission_to_write_in_this_files'].'</font></i>'?><br />
            /lib/config.inc.php<?php echo is_writable('lib/config.inc.php')?' - <i><font color="#009900">'.$LANG['config']['you_have_pemission_to_write_in_this_file'].'</font></i>':' - <i><font color="#FF0000">'.$LANG['config']['you_dont_have_permission_to_write_in_this_files'].'</font></i>'?></p>
          <p><?php echo $LANG['config']['in_case_windows']?><br />
            </p>
        </fieldset>
      </div>
<br />
      <div class="sobre" id="sobre">
            
            <fieldset>
            <legend><strong><?php echo $LANG['config']['installation_type']?> </strong></legend>
            <p>É a sua primeira vez utilizando o Sistema Odontológico? Selecione Nova Instalação. Caso já utilize o sistema selecione a versão atual do seu sistema para atualizar.</p>
            <p align="center">
              <select name="versao" id="versao" class="form-control" onchange="if(this.selectedIndex==0) {document.getElementById('info').style.display='block'} else {document.getElementById('info').style.display='none'}">
<?php
    $versoes = array();
    $versoes[] = $LANG['config']['new_installation'];
    $versoes[] = $LANG['config']['update_from_0_18_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_1_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_2_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_2_2_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_3_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_3_5_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_4_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_5_0_to_current'] . ' ' . $version;
    $valores = array();
    $valores[] = 'novo';
    $valores[] = '0_18';
    $valores[] = '1_0';
    $valores[] = '2_0';
    $valores[] = '2_2';
    $valores[] = '3_0';
    $valores[] = '3_5';
    $valores[] = '4_0';
    $valores[] = '5_0';
    for($i=0; $i<count($versoes); $i++) {
        echo '<option value="'.$valores[$i].'"'.(($_POST['versao'] == $valores[$i])?' selected':'').'>'.$versoes[$i].'</option>';
    }
?>
              </select>
              <br />
            </p>
          </fieldset></div>
            <br />
            <div class="sobre" id="mysql">
              <fieldset>
              <legend><strong><?php echo $LANG['config']['mysqli_information']?> </strong> </legend>
                <p>Para instalar o sistema odontológico, informe as configurações de acesso ao seu servidor MySQL.</p>
                <p align="center"><span class="texto"><font<?php echo $r[3]?>><?php echo $LANG['config']['server']?></font><br />
                  <input name="server" type="text" class="form-control" id="server" value="<?php echo ((empty($_POST['server']))?'localhost':$_POST['server'])?>" />
                  <br />
                  <br />
                  <?php echo $LANG['config']['database']?><br />
                  <input name="bd" type="text" class="form-control" id="bd" value="<?php echo ((empty($_POST['bd']))?'gerenciador':$_POST['bd'])?>" />
                  <br />
                  <br />
                  <font<?php echo $r[4]?>><?php echo $LANG['config']['user']?></font><br />
                  <input name="user" type="text" class="form-control" id="user" value="<?php echo ((empty($_POST['user']))?'root':$_POST['user'])?>" />
                  <br />
                  <br />
                  <font<?php echo $r[4]?>><?php echo $LANG['config']['password']?></font><br />
                  <input name="pass" type="text" class="form-control" id="pass" value="<?php echo ((empty($_POST['pass']))?'':$_POST['pass'])?>" />
                  <br /></span>
              </p>
              </fieldset>
            </div>
      <br />
          <div class="sobre" id="info">
            <fieldset>
              <legend><strong><?php echo $LANG['config']['admin_information']?></strong> </legend>
                <p>O usuário administrador (admin) tem acesso irrestrito ao sistema. É aconselhável que você utilize uma senha acima de 6 caracteres incluindo letras, números e caracteres especiais.</p>
              <p align="center"><?php echo $LANG['config']['username']?>: <strong>admin </strong><br />
                <br />
                  <br />
                  <span class="texto"><font<?php echo $r[0]?>>* <?php echo $LANG['config']['new_password']?>:</font><br />
                  <input name="senha" type="password" class="form-control" id="senha" />
                  <br />
                  <br />
                  <font<?php echo $r[0]?>>* <?php echo $LANG['config']['retype_new_password']?>: </font><br />
                  <input name="resenha" type="password" class="form-control" id="resenha" />
                  <br /></span>
            </p>
            </fieldset>
      <br />
              <fieldset>
              <legend><strong><?php echo $LANG['config']['clinic_information']?></strong> </legend>
                <p><?php echo $LANG['config']['as_it_is_your_first']?></p>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table" id="sem">
                  
                  <tr>
                    <td><span class="texto"><font<?php echo $r[0]?>>* <?php echo $LANG['config']['company_name']?></font><br />
                      <label>
                      <input name="fantasia" value="<?php echo $_POST['fantasia']?>" type="text" class="form-control" id="fantasia" size="45" maxlength="80" />
                      </label>
                      <br />
                      <label></label></td>
                    <td><font<?php echo $r[2]?> class="texto">
                        <?php echo $LANG['config']['document1']?></font>
                      <br />
                      <input name="cnpj" value="<?php echo $_POST['cnpj']?>" type="text" class="form-control" id="cnpj" size="30" maxlength="18" />
                    </td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['legal_name']?> <br />
                        <input name="razaosocial" value="<?php echo $_POST['razaosocial']?>" type="text" class="form-control" id="razaosocial" size="45" /></td>
                    <td><?php echo $LANG['config']['owner']?><br />
                      <input name="proprietario" value="<?php echo $_POST['proprietario']?>" type="text" class="form-control" id="proprietario" size="40" /></td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['address1']?><br />
                        <input name="endereco" value="<?php echo $_POST['endereco']?>" type="text" class="form-control" id="endereco" size="45" maxlength="150" /></td>
                    <td><?php echo $LANG['config']['address2']?><br />
                        <input name="bairro" value="<?php echo $_POST['bairro']?>" type="text" class="form-control" id="bairro" /></td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['city']?><br />
                        <input name="cidade" value="<?php echo $_POST['cidade']?>" type="text" class="form-control" id="cidade" size="30" maxlength="50" />
                        <br /></td>
                    <td><?php echo $LANG['config']['state']?><br />
                        <input name="estado" value="<?php echo $_POST['estado']?>" type="text" class="form-control" id="estado" /></td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['country']?><br />
                        <input name="pais" value="<?php echo $_POST['pais']?>" type="text" class="form-control" id="pais" size="30" maxlength="50" />
                        <br /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['zip']?><br />
                        <input name="cep" value="<?php echo $_POST['cep']?>" type="text" class="form-control" id="cep" size="10" maxlength="9" onkeypress="return Ajusta_CEP(this, event);" /></td>
                    <td><?php echo $LANG['config']['year_of_foundation']?><br />
                        <input name="fundacao" value="<?php echo $_POST['fundacao']?>" type="text" class="form-control" id="fundacao" maxlength="4" /></td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['phone1']?><br />
                        <input name="telefone1" value="<?php echo $_POST['telefone1']?>" type="text" class="form-control" id="telefone1" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" /></td>
                    <td><?php echo $LANG['config']['phone_2']?><br />
                        <input name="telefone2" value="<?php echo $_POST['telefone2']?>" type="text" class="form-control" id="telefone2" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" /></td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['fax']?> <br />
                        <input name="fax" value="<?php echo $_POST['fax']?>" type="text" class="form-control" id="fax" size="25" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" /></td>
                    <td><?php echo $LANG['config']['website']?><br />
                        <input name="web" value="<?php echo $_POST['web']?>" type="text" class="form-control" id="web" size="40" /></td>
                  </tr>
                  <tr>
                    <td><?php echo $LANG['config']['email']?><br />
                        <input name="email" value="<?php echo $_POST['email']?>" type="text" class="form-control" id="email" size="40" /></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <p align="left">Para personalizar ainda mais o sistema, posteriormente você poderá colocar a logomarca da sua clínica e outras informações acessando o menu: Configurações > Dados da Clínica.<br />
                </p>
              </fieldset>
            </div>
            <br />
                      <div class="sobre" id="div4">
                        <fieldset>
                        <legend><strong><?php echo $LANG['config']['finishing_and_saving_information']?> </strong> </legend>
                          <p>Após preencher todos campos acima, será iniciado o processo de configuração do sistema. Mantenha sempre as informações de sua clínica atualizada. Envie sempre seu feedback para que o sistema possa ser melhorado a cada dia.</p>
                          <br />

                          <p align="center">                            
                            <input name="send" type="submit" class="btn btn-primary" id="enviar" value="Aceito instalar o sistema odontológico" />
                            
                        </p>
                        </fieldset>
                      </div>
    </div>
    </div></form></td>
  </tr>
</table>

<script>
if(document.getElementById('versao').selectedIndex==0) {
    document.getElementById('info').style.display='block';
} else {
    document.getElementById('info').style.display='none';
}
</script>
</body>
</html>
