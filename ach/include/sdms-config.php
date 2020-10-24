<?php
ini_set('display_errors', 0);
#Disable direct access.
if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__)) || !defined('ROOT_PATH')) die('kwaheri rafiki!');

#Install flag
define('OSTINSTALLED',TRUE);
if(OSTINSTALLED!=TRUE){
    if(!file_exists(ROOT_PATH.'setup/install.php')) die('Error: Contact system admin.'); //Something is really wrong!
    //Invoke the installer.
    header('Location: '.ROOT_PATH.'setup/install.php');
    exit;
}

# Encrypt/Decrypt secret key - randomly generated during installation.
define('SECRET_SALT','8758214F9FEFA');

#Default admin email. Used only on db connection issues and related alerts.
define('ADMIN_EMAIL','a.haseeb@multibizservices.com');

#Mysql Login info

define('DBTYPE','mysql');
define('DBHOST','localhost'); 
define('DBNAME','sdms_scep');
define('DBUSER','root');
define('DBPASS','lastfight@secp321$');
/*
define('DBTYPE','mysql');
define('DBHOST','localhost'); 
define('DBNAME','x1w5m6g5_scep');
define('DBUSER','x1w5m6g5_scep');
define('DBPASS','{7;N8C)m9wWe');
*/
/*define('DBTYPE','mysql');
define('DBHOST','localhost'); 
define('DBNAME','sdms_scep');
define('DBUSER','ahsan');
define('DBPASS','ubuntu');*/

#Table prefix
define('TABLE_PREFIX','sdms_');
?>
