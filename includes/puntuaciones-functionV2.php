<?php
// includes/puntuaciones-function.php
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

*/

// Función: Calcular puntuacion 
function calcular_puntuacion_test($quiz_id) {
    $preguntas_totales = 0;
    $preguntas_acertadas = 0;
    $puntuacion = 0;

    // Obtener las preguntas del test
    $preguntas = learndash_get_quiz_questions($quiz_id);

    foreach ($preguntas as $pregunta) {
        $preguntas_totales++;
        $respuesta_correcta = get_post_meta($pregunta->ID, 'correct', true);
        $respuesta_usuario = learndash_get_user_quiz_response($pregunta->ID);

        if ($respuesta_usuario == $respuesta_correcta) {
            $preguntas_acertadas++;
            $puntuacion += 1;
        } elseif ($respuesta_usuario === '') {
            // Respuesta en blanco, no hace nada
        } else {
            // Respuesta incorrecta
            $puntuacion -= 0.25;
        }
    }

    return array(
        'preguntas_totales' => $preguntas_totales,
        'preguntas_acertadas' => $preguntas_acertadas,
        'puntuacion' => $puntuacion
    );
}

// Función: Mostrar puntuacion del test tras finalizarlo
function mostrar_puntuacion_test_al_finalizar($quiz_id, $user_id) {
    $puntuacion_info = calcular_puntuacion_test($quiz_id);
    $preguntas_totales = $puntuacion_info['preguntas_totales'];
    $preguntas_acertadas = $puntuacion_info['preguntas_acertadas'];
    $puntuacion = $puntuacion_info['puntuacion'];

    // Mostrar la puntuación al usuario
    echo '<h2>Puntuación del test realizado</h2>';
    echo '<p>Preguntas totales: ' . $preguntas_totales . '</p><br>';
    echo '<p>Preguntas acertadas: ' . $preguntas_acertadas . '</p><br>';
    echo '<p>Puntuación: ' . $puntuacion . '</p><br>';
}

// short code para mostrar la puntuacion del test realizado
add_action('learndash_user_complete_quiz', 'mostrar_puntuacion_test_al_finalizar', 10, 2);