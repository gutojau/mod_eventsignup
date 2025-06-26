<?php
/**
 * Página para visualizar e exportar relatórios de inscrição.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once('locallib.php');

$id = required_param('id', PARAM_INT); // ID do course_module

if (!$cm = get_coursemodule_from_id('eventsignup', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
    print_error('coursemisconf');
}
if (!$eventsignup = $DB->get_record('eventsignup', ['id' => $cm->instance])) {
    print_error('invalideventsignupid', 'mod_eventsignup');
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/eventsignup:viewreports', $context);

// Configuração da PÁGINA na ordem correta
$PAGE->set_url('/mod/eventsignup/report.php', ['id' => $cm->id]);
$PAGE->set_context($context); // **CORREÇÃO: Definir o contexto PRIMEIRO.**
$PAGE->set_pagelayout('report');
$PAGE->set_title(format_string($eventsignup->name) . ': ' . get_string('reports', 'mod_eventsignup'));
$PAGE->set_heading(format_string($course->fullname));


echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('reports', 'mod_eventsignup'));

// (O resto do código para obter e exibir a tabela de relatório permanece aqui...)

echo $OUTPUT->footer();
