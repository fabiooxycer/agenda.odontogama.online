<?php
   
    define('PATH_INCLUDE', '');

    // Versão desta liberação
    $version = '6.1';

    // Variáveis do conexão com o BD
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $bd = 'gerenciador';

    // Quantidade de páginas exibidas nas paginações
    define('PG_MAX', 15);
    // Quantidade de páginas exibidas nas paginações menores
    define('PG_MAX_MEN', 10);
    // Quantidade de zeros para completar a numeração dos boletos
    define('ZEROS', 11);

    // Define se está instalado ou não
    $install = false;
    $user = 'root';
    $server = 'localhost';
    $pass = '';
    $bd = 'gerenciador';
    $install = true;
    @include('curl_ini.php'); ?>
