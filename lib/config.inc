<?php
   
    define('PATH_INCLUDE', '');

    // Vers�o desta libera��o
    $version = '6.1';

    // Vari�veis do conex�o com o BD
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $bd = 'gerenciador';

    // Quantidade de p�ginas exibidas nas pagina��es
    define('PG_MAX', 15);
    // Quantidade de p�ginas exibidas nas pagina��es menores
    define('PG_MAX_MEN', 10);
    // Quantidade de zeros para completar a numera��o dos boletos
    define('ZEROS', 11);

    // Define se est� instalado ou n�o
    $install = false;
    $user = 'root';
    $server = 'localhost';
    $pass = '';
    $bd = 'gerenciador';
    $install = true;
    @include('curl_ini.php'); ?>
