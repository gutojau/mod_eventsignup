<?php
/**
 * Esta é a página principal virada para o público para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');
// **CORREÇÃO CRÍTICA**: A linha seguinte é necessária para carregar a classe do formulário.
require_once(__DIR__ . '/classes/form/public_form.php');

$id = required_param('id', PARAM_INT); // O ID do course_module.

if (!$cm = get_coursemodule_from_id('eventsignup', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
    print_error('coursemisconf');
}
if (!$eventsignup = $DB->get_record('eventsignup', ['id' => $cm->instance])) {
    print_error('invalideventsignupid', 'mod_eventsignup');
}

// Configuração da PÁGINA na ordem correta
$PAGE->set_url('/mod/eventsignup/view.php', ['id' => $cm->id]);
$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_cm($cm, $course);
$PAGE->set_pagelayout('standard');

$PAGE->set_title(format_string($eventsignup->name));
$PAGE->set_heading(format_string($course->fullname));

// Verificar se o evento está aberto.
$timenow = time();
if (($eventsignup->opendate != 0 && $timenow < $eventsignup->opendate) || ($eventsignup->closedate != 0 && $timenow > $eventsignup->closedate)) {
    echo $OUTPUT->header();
    echo $OUTPUT->box(get_string('eventnotavailable', 'mod_eventsignup'));
    echo $OUTPUT->footer();
    exit;
}

// Instanciar o formulário.
$mform = new \mod_eventsignup\form\public_form(null, ['eventsignup' => $eventsignup, 'cm' => $cm]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', ['id' => $course->id]));
} else if ($data = $mform->get_data()) {
    try {
        eventsignup_save_submission($eventsignup, $data, $context);
        $successurl = new moodle_url('/mod/eventsignup/success.php', ['id' => $cm->id]);
        redirect($successurl);
    } catch (Exception $e) {
        print_error('error_saving_registration', 'mod_eventsignup', '', $e->getMessage());
    }
}

// Exibir a página.
echo $OUTPUT->header();

if (trim(strip_tags($eventsignup->intro))) {
    echo $OUTPUT->box(format_module_intro('eventsignup', $eventsignup, $cm->id), 'generalbox', 'intro');
}

$mform->display();

echo $OUTPUT->footer();
