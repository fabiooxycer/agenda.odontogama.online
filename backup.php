<?php
   

    include "logged.php";
    $conn = conecta();
    define('DIR_FS_BACKUP', $BACK_PATH);
    define('DB_DATABASE', $NAME);
    define('DB_SERVER', $HOST);
    $schema = '# ExpandWEB - Expandindo Soluções em WEB' . "\n" .
              '# http://www.expandweb.com' . "\n" .
              '#' . "\n" .
              '# Backup do Banco de dados ' . "\n" .
              '# Copyright (c) ' . date('Y') . "\n" .
              '#' . "\n" .
              '# Banco de dados: ' . DB_DATABASE . "\n" .
              '# Servidor: ' . DB_SERVER . "\n" .
              '#' . "\n" .
              '# Data de Backup: ' . date(d."/".m."/".Y." - ".H.":".i) . "\n\n";
    $tables_query = mysql_query('show tables');
    while ($tables = mysql_fetch_array($tables_query)) {
        list(,$table) = each($tables);
        $schema .= 'drop table if exists `' . $table . '`;' . "\n" . 'create table `' . $table . '` (' . "\n";
        $table_list = array();
        $fields_query = mysql_query("show fields from " . $table);
        while ($fields = mysql_fetch_array($fields_query)) {
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
        $keys_query = mysql_query("show keys from `" . $table . "`");
        while ($keys = mysql_fetch_array($keys_query)) {
            $kname = $keys['Key_name'];
            if (!isset($index[$kname])) {
                $index[$kname] = array('unique' => !$keys['Non_unique'], 'columns' => array());
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
        $rows_query = mysql_query("select `" . implode('`, `', $table_list) . "` from " . $table);
        while ($rows = mysql_fetch_array($rows_query)) {
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
    }
    $backup_file = 'db_' . DB_DATABASE . '-' . date('d_m_Y-H_i') . '.sql';
    header('Content-type: application/x-octet-stream');
    header('Content-disposition: attachment; filename=' . $backup_file);
    echo $schema;
    //headers_sent($backup_file);
    //echo $schema;
    if(!empty($BACK_PATH)) {
        $backup_file = DIR_FS_BACKUP . $backup_file;
        if ($fp = fopen($backup_file, 'w')) {
            fputs($fp, $schema);
            fclose($fp);
        }
    }
?>
