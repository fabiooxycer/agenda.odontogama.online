<?php
   
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';

  $sistema = new sistema(); 
  $conn = mysqli_connect($sistema->server, $sistema->user, $sistema->pass, $sistema->bd) or die(mysqli_error($conn));


	if(!checklog()) {
        echo '<script>Ajax("wallpapers/index", "conteudo", "");</script>';
        die();
	}
    define('DB_DATABASE', $bd);
    define('DB_SERVER', $server);
    define('DB_DATE', date('Y-m-d_H-i-s'));
    $backup_file = 'db_' . DB_DATABASE . '_' . DB_DATE . '.sql';
    header('Content-type: application/x-octet-stream');
    header('Content-disposition: attachment; filename=' . $backup_file);
        $schema = '# Gerenciador Odontologico' . "\n" .
                  '# http://www.mdevsistemas.com.br/' . "\n" .
                  '#' . "\n" .
                  '# Backup do Banco de dados ' . "\n" .
                  '# Copyright (c) ' . date('Y') . "\n" .
                  '#' . "\n" .
                  '# Banco de dados: ' . DB_DATABASE . "\n" .
                  '# Servidor: ' . DB_SERVER . "\n" .
                  '#' . "\n" .
                  '# Data de Backup: ' . DB_DATE . "\n\n";
        $tables_query = mysqli_query($conn, 'show tables');
        while ($tables = mysqli_fetch_array($tables_query)) {
          list(,$table) = each($tables);
          $schema .= 'drop table if exists `' . $table . '`;' . "\n" .
                     'create table `' . $table . '` (' . "\n";
          $table_list = array();
          $fields_query = mysqli_query($conn, "show fields from " . $table);
          while ($fields = mysqli_fetch_array($fields_query)) {
            $table_list[] = $fields['Field'];
            $schema .= '  `' . $fields['Field'] . '` ' . $fields['Type'];
            if (strlen($fields['Default']) > 0) $schema .= ' default \'' . $fields['Default'] . '\'';
            if ($fields['Null'] != 'YES') $schema .= ' not null';
            if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];
            $schema .= ',' . "\n";
          }
          $schema = ereg_replace(",\n$", '', $schema);

          // Add the keys
          $index = array();
          $keys_query = mysqli_query($conn, "show keys from `" . $table . "`");
          while ($keys = mysqli_fetch_array($keys_query)) {
            $kname = $keys['Key_name'];
            if (!isset($index[$kname])) {
              $index[$kname] = array('unique' => !$keys['Non_unique'],
                                     'columns' => array());
            }
            $index[$kname]['columns'][] = $keys['Column_name'];
          }
          while (list($kname, $info) = each($index)) {
            $schema .= ',' . "\n";
            $columns = implode($info['columns'], ', ');
            if ($kname == 'PRIMARY') {
              $schema .= '  PRIMARY KEY (' . $columns . ')';
            } elseif ($info['unique']) {
              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
            } else {
              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
            }
          }
          $schema .= "\n" . ');' . "\n\n";
          /*
          echo implode('`, `', $table_list);
          */
          $rows_query = mysqli_query($conn, "select `" . implode('`, `', $table_list) . "` from " . $table);
          while ($rows = mysqli_fetch_array($rows_query)) {
            $schema_insert = 'insert into `' . $table . '` (`' . implode('`, `', $table_list) . '`) values (';
            reset($table_list);
            while (list(,$i) = each($table_list)) {
              if (!isset($rows[$i])) {
                $schema_insert .= 'NULL, ';
              } elseif ($rows[$i] != '') {
                $row = addslashes($rows[$i]);
                $row = ereg_replace("\n#", "\n".'\#', $row);
                $schema_insert .= '\'' . $row . '\', ';
              } else {
                $schema_insert .= '\'\', ';
              }
            }
            $schema_insert = ereg_replace(', $', '', $schema_insert) . ');' . "\n";
            $schema .= $schema_insert;
          }
          $schema .= "\n";
          echo $schema;
          $schema = '';
        }
?>
