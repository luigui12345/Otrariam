<?php
/**
* Archivo de carga
* Este Archivo define rutas relativas y la información de la base de datos
*
* @package Flash-back/JuegoEnProceso
* @subpackage jangovc/JuegoEnProceso
*
*/
 
session_start();
/* DEFINE RUTAS RELATIVAS */
define( 'PATH', dirname(__FILE__) . '/' );
define ('INC', 'include/');



// ** Ajustes de MySQL. ** //
/** El nombre la base de datos */
define('DB_NAME', 'juego_navegador');

/** Tu nombre de usuario de MySQL */
define('SQL_USER', 'root');

/** Tu contraseña de MySQL */
define('SQL_PASS', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

?>