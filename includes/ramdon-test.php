<?php
require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/course/ld-course-functions.php');

require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/sfwd_lms.php');
require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/admin/class-learndash-admin-data-upgrades.php');
require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/themes/class-ld-themes-register.php');
require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/settings/class-ld-settings-sections.php');
require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/shortcodes/ld_course_list.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/shortcodes/ld_quiz_list.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/quiz/ld-quiz-functions.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/class-ldlms-post-types.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/course/ld-course-steps-functions.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/course/ld-course-navigation.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/classes/class-ldlms-factory.php');
	require_once(ABSPATH . 'wp-content/plugins/sfwd-lms/includes/classes/class-ldlms-factory-post.php');

function impPrueba($idTemas){

	$tema=intval($idTemas[0]);
	$preguntas=learndash_course_get_quizzes(44,$tema);
	print_r($preguntas);
	
}
