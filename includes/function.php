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

*/
// Librerias necesarias o requeridas
//require_once(ABSPATH.'wp-content/plugins/sfwd-lms/sfwd_lms.php');

// Carga segura y unica de scripts externos o internos
function mdtf_CargarScript(){
	//Directorio raiz del plugin
	$plugin_dir_uri = plugin_dir_url( 'mdt-learndash/mdt-learndash.php');
	
	//Cargar Chartjs v=4.2.1
	wp_register_script( 'jQuery', $plugin_dir_uri.'node-includes/jquery.min.js', array(), '3.7.0', false);
	wp_enqueue_script('jQuery');
}
add_action('wp_enqueue_scripts', 'mdtf_CargarScript');


//Obtencion de los temas seleccionados y sus valores diferenciales (ID)
add_shortcode('mdt-Learndash1', 'prueba1');

//Prueba 2.0	
function prueba1(){
?>
	<!-- Formulario con tres checkboxes -->
<form method="post" id="select-form">
	<table>
		<tr>
			<td>
				<label for="bloque1">
					<input type="checkbox" name="bloque1" id="bloque1" value="412">
					Bloque I
				</label>
			</td>
			<td>
				<label for="bloque2">
					<input type="checkbox" name="bloque2" id="bloque2" value="420">
					Bloque II
				</label>
			</td>
			<td>
				<label for="bloque3">
					<input type="checkbox" name="bloque3" id="bloque3" value="422">
					Bloque III
				</label>
			</td>
			<td>
    			<input type="submit" name="submit" value="Mostrar Temas">
			</td>
		</tr>
	</table>
</form>

<!-- Contenedor donde se mostrará el selector -->
<style>
	#select-form td, #selector-container td{
		max-width:100%;
		width:25%;
		text-align:center;
	}
	select{
		max-width: 200px;
	}
</style>
<div id="selector-container">
	<table>
		<tr>
    <?php

    // Mostrar el select dependiendo del checkbox seleccionado
    if (isset($_POST['submit'])) {
        if (isset($_POST['bloque1'])) {
			//echo '<th>Selecciona los temas del Bloque I</th>';
            echo '<td>'.obtenerSelectOpcion(412). '</td>';
        }else{
			//echo '<th></th>';
			echo '<td> </td>';
		} 
		if (isset($_POST['bloque2'])) {
			//echo '<th>Selecciona los temas del Bloque II</th>';
            echo '<td>'.obtenerSelectOpcion(420) .'</td>';
        }else{
			//echo '<th></th>';
			echo '<td> </td>';
		} 
		if (isset($_POST['bloque3'])) {
			//echo '<th>Selecciona los temas del Bloque III</th>';
            echo '<td>'.obtenerSelectOpcion(422).'</td>';
        }else{
			//echo '<th></th>';
			echo '<td> </td>';
		}
    }
	echo '<td> </td>';

    ?>
		</tr>
	</table>
</div>

<div id="tabla-temas-container">
    <table id="tabla-temas">
        <thead>
            <tr>
                <th>Bloque</th>
                <th>Tema</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se añadirán las filas -->
        </tbody>
    </table>
</div>

<form method="post" id="tabla-form">
    <input type="hidden" name="tabla_datos" id="tabla-datos">
    <input type="submit" name="guardar_tabla" value="Generar test aleatorio">
</form>

<script>
	// Manejar el evento de click para todos los botones "Mostrar Temas"
	document.getElementById("select-form").addEventListener("submit", function (event) {
    // Obtener todas las casillas (checkbox)
    var checkboxes = document.querySelectorAll("#select-form input[type='checkbox']");
    var alMenosUnaSeleccionada = false;

    // Verificar si al menos una casilla está marcada
    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            alMenosUnaSeleccionada = true;
            // Si al menos una casilla está marcada, podemos detener el bucle
            return;
        }
    });

    // Si ninguna casilla está marcada, mostrar una alerta y evitar que el formulario se envíe
    if (!alMenosUnaSeleccionada) {
        event.preventDefault(); // Evitar el envío del formulario
        alert("Debes seleccionar al menos una casilla antes de continuar.");
    }
});
	
    // Manejar el evento de click para todos los botones "Agregar Tema"
    var temasAgregados=[];
    var botonesAgregarTemas = document.querySelectorAll(".agregar-temas");
    botonesAgregarTemas.forEach(function (boton) {
        boton.addEventListener("click", function () {
            var bloque = boton.getAttribute("data-bloque");
            var select = document.querySelector("select[name='temas[" + bloque + "]']");
            var selectedOption = select.options[select.selectedIndex];
			var valorTema=selectedOption.value;
			if (temasAgregados.includes(valorTema)) {
				alert("Error: El tema ya ha sido agregado.");
				return; // Salir de la función si el tema ya existe
        	}
            var newRow = document.createElement("tr");
            newRow.setAttribute("value", selectedOption.value); // Añadir el atributo data con el valor del option seleccionado
            newRow.innerHTML = "<td>" + bloque + "</td><td>" + selectedOption.textContent + "</td>" + "<td><button class='eliminar-linea' type='button'><i class='fa fa-trash'></i></td>";
            document.querySelector("#tabla-temas tbody").appendChild(newRow);
			var botonEliminar = newRow.querySelector(".eliminar-linea");
        		botonEliminar.addEventListener("click", function () {
            		newRow.remove(); // Eliminar la fila al hacer clic en el botón "Eliminar"
        		});
			temasAgregados.push(valorTema);
        });
    });
	
	document.getElementById("tabla-form").addEventListener("submit", function () {
        var tablaDatos = [];
		var temasUnicos = {};
        var filas = document.querySelectorAll("#tabla-temas tbody tr");
        filas.forEach(function (fila) {
			var valorTema = fila.getAttribute("value");
            var bloque = fila.children[0].textContent;
            var tema = fila.children[1].textContent;
            tablaDatos.push({ bloque: bloque, value: valorTema,  tema: tema });
        });

        document.getElementById("tabla-datos").value = JSON.stringify(tablaDatos);
    });
</script>
<?php	
}
// Obtener los temas del bloque o bloques seleccionado/s
function obtenerTemasDelBloque($course_id, $lesson_id) {
    $topics = learndash_course_get_topics($course_id, $lesson_id);

    // Retornar los temas
    return $topics;
}
	
// Funciones para obtener y generar los selects con los temas de cada bloque
function obtenerSelectOpcion($lesson_id) {
    $opciones = obtenerTemasDelBloque(44, $lesson_id);
    return generarSelect($opciones, $lesson_id);
}

function generarSelect($opciones, $lesson_id) {
	switch($lesson_id){
		case 412:
			$bloque='bloque1';
			$bloqueTitulo='Bloque I';
			break;
		case 420:
			$bloque='bloque2';
			$bloqueTitulo='Bloque II';
			break;
		case 422:
			$bloque='bloque3';
			$bloqueTitulo='Bloque III';
			break;
	}
    $selectHTML = '<select name="temas[' . $bloque . ']">';
    foreach ($opciones as $opcion) {
        $selectHTML .= '<option value="' . $opcion->ID.'">' . $opcion->post_title . '</option>';
    }
    $selectHTML .= '</select>';
	$selectHTML .= '<button type="button" class="agregar-temas" data-bloque="'.$bloque.'"> Agregar tema</button>';
    return $selectHTML;
}
require_once('ramdon-test.php');
// Guardar los temas seleccionado para el test
if (isset($_POST["guardar_tabla"]) && isset($_POST["tabla_datos"])) {
    $tablaDatos = json_decode($_POST["tabla_datos"], true);
	foreach($tablaDatos as $tema){
		$idTemas[]=$tema['value'];
	}
	if(!empty($tablaDatos)){
		//impPrueba2($idTemas);

	}else{
		echo '<script>alert("No se ha seleccionado ningun tema.");</script>';
		return;
	}
}

function obtener_ids(){
	//$course_id=
}

add_shortcode('mdt-Learndash2', 'impPrueba2');
function impPrueba2() {
	global $wpdb;
	$theme_id=13768;
	$block_id=412;

	$question_ids = learndash_get_quiz_questions($theme_id);
	
	if (!empty($question_ids)) {
		foreach ($question_ids as $question_id) {
			// Obtener la información de la pregunta
			$question = get_post($question_id);
			//$query = $wpdb->prepare("SELECT * FROM wpy9_posts WHERE post_type = %s AND post_status = %s AND ID IN (%d)", $oposiciones, $user_id);
			//$result = $wpdb->get_results($query);

			
			// Verificar si la pregunta es válida y está publicada
			if ($question && is_object($question) && $question->post_type === 'sfwd-question' && $question->post_status === 'publish') {
				// Mostrar el título de la pregunta
				echo '<h3>' . $question->post_title . '</h3>';

				// Mostrar el contenido de la pregunta
				//echo apply_filters('the_content', $question->post_content);
				$contenido_pregunta=$question->post_content;
				echo $contenido_pregunta;

				// Obtener las respuestas de la pregunta
				$answers = get_post_meta($question_id, 'question', true);
				var_dump($answers);

				// Agregar un separador entre preguntas
				echo '<hr>';
			}
		}
	} else {
		echo 'No se encontraron preguntas para el quiz con ID: ' . $quiz_id;
	}
	
	/*var_dump($question_ids);
	foreach($question_ids as $question){
		//print_r($question);
	}*/
}