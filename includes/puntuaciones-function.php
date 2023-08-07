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
add_action( 'learndash_quiz_start', function( $quiz_id ) {

    // Obtener id del cuestionario.
    $questions_count = learndash_get_quiz_questions_count( $quiz_id );

    // Recorrer preguntas y obtener resultados.
    for ( $i = 1; $i <= $questions_count; $i++ ) {

        $question_id = learndash_get_question_id( $quiz_id, $i );
        $question_result = learndash_get_quiz_question_result( $question_id );

        if ( $question_result === 'correct' ) {
            $score += 1;
        } elseif ( $question_result === 'incorrect' ) {
            $score -= 0.25;
        } else {
            $score += 0;
        }

        learndash_update_quiz_question_result( $question_id, $score );
    }

} );

add_action( 'ld_quiz_complete', function( $quiz_id ) {

    // Obtener respuestas correctas.
    $correct_answers = learndash_get_quiz_correct_answers_count( $quiz_id );

    // Obtener respuestas incorrectas.
    $incorrect_answers = learndash_get_quiz_incorrect_answers_count( $quiz_id );

    // Obtener respuestas no contestadas.
    $unanswered_questions = learndash_get_quiz_unanswered_questions_count( $quiz_id );

    // Obtener puntuación total.
    $total_score = $correct_answers - $incorrect_answers + $unanswered_questions;

    // Mostrar resultados.
    echo '<h1>Resultado del test</h1>';
    echo '<p>Respuestas correctas: ' . $correct_answers . '</p>';
    echo '<p>Respuestas incorrectas: ' . $incorrect_answers . '</p>';
    echo '<p>Preguntas no contestadas: ' . $unanswered_questions . '</p>';
    echo '<p>Puntuación total: ' . $total_score . '</p>';

} );
