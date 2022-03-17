<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	//header("Content-type: text/html; charset=ISO-8859-1", true);
	if(!verifica_nivel('permissoes', 'V')) {
        echo $LANG['general']['you_tried_to_access_a_restricted_area'];
        die();
    }

    $sistema = new sistema(); 
    $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd);

    $_GET['nivel'] = (($_GET['nivel'] != '')?$_GET['nivel']:'Dentista');
	if(isset($_POST['enviar'])) {
        unset($_POST['nivel'], $_POST['enviar']);
        mysqli_query($conn, "DELETE FROM permissoes WHERE nivel = '".$_GET['nivel']."'");
        echo mysqli_error();
        foreach($_POST as $area => $perm) {
            mysqli_query($conn, "INSERT INTO permissoes VALUES ('".$_GET['nivel']."', '".$area."', '".implode(',', $perm)."')");
        }
        echo '<script type="text/javascript">Ajax("configuracoes/permissoes", "conteudo", "nivel='.$_GET['nivel'].'")</script>';
        /*echo '<pre>';
        print_r($_POST);
        echo '</pre>';*/
	}
?>
<style>
.texto1 tr:hover {
    background-color: #C0C0C0;
}
</style>

<div class="panel panel-default" id="conteudo_central">
  <div class="panel-heading"><span class="glyphicon glyphicon-edit"></span> <b><?php echo $LANG['settings']['permissions']?></b></div>
  <div class="panel-body">

 
  <table class="table">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="configuracoes/permissoes_ajax.php?nivel=<?php echo $_GET['nivel']?>" onsubmit="formSender(this, 'conteudo'); return false;">
        <fieldset>
        <legend><span class="style1"><?php echo $LANG['settings']['permissions']?></span></legend>
        <table class="table">
          <tr>
            <td align="center"><select name="nivel" class="form-control" onchange="javascript:Ajax('configuracoes/permissoes', 'conteudo', '&nivel='+this.value)">
<?php
    $valores = array('Dentista' => $LANG['settings']['professionals'], 'Funcionario' => $LANG['settings']['employees']);
    foreach($valores as $chave => $valor) {
        echo '            <option value="'.$chave.'" '.(($_GET['nivel'] == $chave)?'selected':'').'>'.$valor.'</option>'."\n";
    }
?>
            </select></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td><table class="table table-hover table-bordered">
              <thead>
                <th align="center"><?php echo $LANG['settings']['module']?></th>
                <th align="center"><?php echo $LANG['settings']['access']?></th>
                <th align="center"><?php echo $LANG['settings']['view']?></th>
                <th align="center"><?php echo $LANG['settings']['edit']?></th>
                <th align="center"><?php echo $LANG['settings']['insert']?></th>
                <th align="center"><?php echo $LANG['settings']['delete']?></th>
              </thead>
              <tbody>
           
            

              <tr>
                <td colspan="6"><b>Cadastros</b></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'profissionais'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['professionals']?></td>
                <td align="center"><input type="checkbox" name="profissionais[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="profissionais[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td   align="center"><input type="checkbox" name="profissionais[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td   align="center"><input type="checkbox" name="profissionais[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td   align="center"><input type="checkbox" name="profissionais[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'pacientes'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['patients']?></td>
                <td align="center"><input type="checkbox" name="pacientes[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="pacientes[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="pacientes[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="pacientes[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="pacientes[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'funcionarios'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['employees']?></td>
                <td align="center"><input type="checkbox" name="funcionarios[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="funcionarios[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="funcionarios[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="funcionarios[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="funcionarios[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'fornecedores'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['supliers']?></td>
                <td align="center"><input type="checkbox" name="fornecedores[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="fornecedores[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="fornecedores[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="fornecedores[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="fornecedores[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'agenda'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['calendar']?></td>
                <td align="center"><input type="checkbox" name="agenda[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"><input type="checkbox" name="agenda[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'patrimonio'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['patrimony']?></td>
                <td align="center"><input type="checkbox" name="patrimonio[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="patrimonio[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="patrimonio[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="patrimonio[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="patrimonio[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'estoque'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['stock_control']?></td>
                <td align="center"><input type="checkbox" name="estoque[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="estoque[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="estoque[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="estoque[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="estoque[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'laboratorios'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['laboratory']?></td>
                <td align="center"><input type="checkbox" name="laboratorios[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="laboratorios[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="laboratorios[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="laboratorios[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="laboratorios[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'convenios'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['plans']?></td>
                <td align="center"><input type="checkbox" name="convenios[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="convenios[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="convenios[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="convenios[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="convenios[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'honorarios'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LANG['menu']['fee_table']?></td>
                <td align="center"><input type="checkbox" name="honorarios[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="honorarios[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="honorarios[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="honorarios[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="honorarios[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
              <tr>
                <td colspan="6"><b><?php echo $LANG['menu']['monetary']?></b></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'contas_pagar'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['accounts_payable']?></td>
                <td align="center"><input type="checkbox" name="contas_pagar[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_pagar[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_pagar[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_pagar[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_pagar[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'contas_receber'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['accounts_receivable']?></td>
                <td align="center"><input type="checkbox" name="contas_receber[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_receber[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_receber[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_receber[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contas_receber[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'caixa'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['cash_flow']?></td>
                <td align="center"><input type="checkbox" name="caixa[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"><input type="checkbox" name="caixa[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="caixa[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'cheques'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['check_control']?></td>
                <td align="center"><input type="checkbox" name="cheques[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="cheques[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="cheques[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="cheques[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="cheques[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'pagamentos'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['payments']?></td>
                <td align="center"><input type="checkbox" name="pagamentos[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
              <tr>
                <td colspan="6"><b><?php echo $LANG['menu']['utilities']?></b></td>
              </tr>
              <tr>
                <td colspan="6">&nbsp;&nbsp;<b><?php echo $LANG['menu']['files']?></b></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'arquivos_clinica'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LANG['menu']['clinic_files']?></td>
                <td align="center"><input type="checkbox" name="arquivos_clinica[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="arquivos_clinica[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"><input type="checkbox" name="arquivos_clinica[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="arquivos_clinica[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'manuais'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LANG['menu']['manuals_and_codes']?></td>
                <td align="center"><input type="checkbox" name="manuais[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="manuais[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'contatos'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['usefull_telephones']?></td>
                <td align="center"><input type="checkbox" name="contatos[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contatos[]" value="V" <?php echo ((in_array('V', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contatos[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contatos[]" value="I" <?php echo ((in_array('I', $perm))?'checked':'')?> /></td>
                <td align="center"><input type="checkbox" name="contatos[]" value="A" <?php echo ((in_array('A', $perm))?'checked':'')?> /></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'backup_gerar'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['backup_generation']?></td>
                <td align="center"><input type="checkbox" name="backup_gerar[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'backup_restaurar'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['backup_restoration']?></td>
                <td align="center"><input type="checkbox" name="backup_restaurar[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
              <tr>
                <td colspan="6"><b><?php echo $LANG['menu']['configuration']?></b></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'informacoes'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['clinic_information']?></td>
                <td align="center"><input type="checkbox" name="informacoes[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"><input type="checkbox" name="informacoes[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
<?php
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissoes WHERE nivel = '".$_GET['nivel']."' AND area = 'idiomas'"));
    $perm = explode(',', $row['permissao']);
?>
              <tr>
                <td>&nbsp;&nbsp;<?php echo $LANG['menu']['language']?></td>
                <td align="center"><input type="checkbox" name="idiomas[]" value="L" <?php echo ((in_array('L', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"><input type="checkbox" name="idiomas[]" value="E" <?php echo ((in_array('E', $perm))?'checked':'')?> /></td>
                <td align="center"></td>
                <td align="center"></td>
              </tr>
            </tbody>
            </table></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><input name="enviar" type="submit" class="btn btn-success" id="enviar" value="<?php echo $LANG['settings']['save']?>" /></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
        </table>
        </fieldset>
      </form>
      </td>
    </tr>
  </table>
</div>
