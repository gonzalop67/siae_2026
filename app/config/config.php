<?php 
/**
 * ----------------------------------------------
 *  Base URL
 * ----------------------------------------------
 */

function baseurl(){
    return 'http://localhost/siae_2026';
}

/**
 * ----------------------------------------------
 *  APP GENERAL CONFIGURATION
 * ----------------------------------------------
 */

$GLOBALS['config'] = array(
    // configuration for database connection

    "mysql" => array(
        "db_host" => "localhost",
        "db_user" => "root",
        "db_pass" => "",
        "db_name" => "siae_2026",
        "db_charset" => "utf8"
    ),
    
);

define('NOMBRESITIO', 'SIAE Web ');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mvc');
?>