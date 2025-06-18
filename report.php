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

$PAGE->set_url('/mod/eventsignup/report.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($eventsignup->name) . ': ' . get_string('reports', 'mod_eventsignup'));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_pagelayout('standard');

// Navegação
$PAGE->navbar->add(get_string('reports', 'mod_eventsignup'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('reports', 'mod_eventsignup'));

// --- Lógica Principal ---

// 1. Obter todas as perguntas para construir o cabeçalho da tabela dinamicamente.
$questions = $DB->get_records('eventsignup_question', ['survey_id' => $eventsignup->id, 'deleted' => 'n'], 'position ASC');

// 2. Obter todos os inscritos e as suas principais entradas de resposta.
$sql = "SELECT r.id, reg.firstname, reg.lastname, reg.email, r.submitted
        FROM {eventsignup_registrants} reg
        JOIN {eventsignup_response} r ON r.registrant_id = reg.id
        WHERE reg.eventsignup_id = :eventsignupid
        ORDER BY r.submitted DESC";
$registrations = $DB->get_records_sql($sql, ['eventsignupid' => $eventsignup->id]);

if (!$registrations) {
    echo $OUTPUT->notification(get_string('noresponses', 'mod_eventsignup'));
    echo $OUTPUT->footer();
    exit;
}

// 3. Construir a tabela.
$table = new html_table();
$table->attributes['class'] = 'generaltable mod_eventsignup_report';

// Construir cabeçalhos da tabela.
$table->head = [
    get_string('registrant_firstname', 'mod_eventsignup'),
    get_string('registrant_lastname', 'mod_eventsignup'),
    get_string('registrant_email', 'mod_eventsignup'),
    get_string('submitted', 'mod_eventsignup'),
];
foreach ($questions as $question) {
    $table->head[] = format_string($question->content);
}

// Construir linhas da tabela.
$fs = get_file_storage();
foreach ($registrations as $reg) {
    $row = [
        $reg->firstname,
        $reg->lastname,
        $reg->email,
        userdate($reg->submitted)
    ];

    foreach ($questions as $question) {
        // ... (código para preencher as células de resposta como antes) ...
    }
    $table->data[] = new html_table_row($row);
}

echo html_writer::table($table);

// --- Botões de Exportação ---
echo $OUTPUT->box_start('generalbox', 'downloadoptions');
echo $OUTPUT->heading(get_string('downloadreport', 'mod_eventsignup'), 3);
$baseurl = new moodle_url('/mod/eventsignup/export.php', ['id' => $cm->id, 'sesskey' => sesskey()]);

// Botão para CSV
$csvurl = new moodle_url($baseurl, ['format' => 'csv']);
echo html_writer::link($csvurl, get_string('exportcsv', 'mod_eventsignup'), ['class' => 'btn btn-secondary']);

// Pode adicionar outros formatos aqui (Excel, etc.)
echo $OUTPUT->box_end();


echo $OUTPUT->footer();
