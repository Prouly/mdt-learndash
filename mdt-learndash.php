<?php
/*
Plugin Name: MDT-Learndash
Plugin URI: https://wordpress.org/plugins/mdt-learndash/
Description: Plugin exclusivo para Testeate donde crea una integracion de tests aleatorios creados por los Alumnos, todo esto con los hooks de LearnDash y obteniendo las preguntas realizadas en LearnDash.
Version: 0.1.1
Requires PHP: 7.2 o superior
Author: Mindestec
Author URI: https://mindestec.com
License: GPLv2 o anterior
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: Mindestec LearnDash

MDT-Learndash es software exclusivo para Testeate: los alumnos pueden crear tests de Learndash 
con una generacion dependiendo de los temarios seleccionados y su peso de forma aleatoria.

*/
require_once ABSPATH.'wp-content/plugins/sfwd-lms/sfwd_lms.php';
// Incluir y ejecutar los archivos principales del complemento
include(plugin_dir_path(__FILE__).'includes/function.php');

// Archivo para calcular puntuaciones
include(plugin_dir_path(__FILE__).'includes/puntuaciones-function.php');

// Incluir y crear hook de Desinstalar el complemento
