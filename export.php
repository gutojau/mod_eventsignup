<?php
/**
 * Script para exportar os dados do relatório.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir . '/csvlib.class.php');

$id     = required_param('id', PARAM_INT);
$format = required_param('format', PARAM_ALPHA);
require_sesskey();

if (!$cm = get_coursemodule_from_id('eventsignup', $id)) {
    print_error('invalidcoursemodule');
}
if (!$eventsignup = $DB->get_record('eventsignup', ['id' => $cm->instance])) {
    print_error('invalideventsignupid', 'mod_eventsignup');
}

$context = context_module::instance($cm->id);
require_capability('mod/eventsignup:viewreports', $context);

// --- Obter dados (similar ao report.php) ---
$questions = $DB->get_records('eventsignup_question', ['survey_id' => $eventsignup->id, 'deleted' => 'n'], 'position ASC');
$sql = "SELECT r.id, reg.firstname, reg.lastname, reg.email, r.submitted
        FROM {eventsignup_registrants} reg
        JOIN {eventsignup_response} r ON r.registrant_id = reg.id
        WHERE reg.eventsignup_id = :eventsignupid
        ORDER BY r.submitted DESC";
$registrations = $DB->get_records_sql($sql, ['eventsignupid' => $eventsignup->id]);

// --- Gerar o Ficheiro ---
$filename = 'eventsignup_' . $eventsignup->id . '_report';
$csvexporter = new csv_exporter();

// Cabeçalhos
$headers = [
    get_string('registrant_firstname', 'mod_eventsignup'),
    get_string('registrant_lastname', 'mod_eventsignup'),
    get_string('registrant_email', 'mod_eventsignup'),
    get_string('submitted', 'mod_eventsignup'),
];
foreach ($questions as $question) {
    $headers[] = strip_tags(format_string($question->content));
}
$csvexporter->add_data($headers);

// Linhas de dados
$fs = get_file_storage();
foreach ($registrations as $reg) {
    $row = [
        $reg->firstname,
        $reg->lastname,
        $reg->email,
        userdate($reg->submitted, get_string('strftimedatetime'))
    ];

    foreach ($questions as $question) {
        $celldata = '';
        $qtype_table = $DB->get_field('eventsignup_question_type', 'response_table', ['id' => $question->type_id]);

        if ($qtype_table == 'eventsignup_response_text') {
            $celldata = $DB->get_field('eventsignup_response_text', 'response', ['response_id' => $reg->id, 'question_id' => $question->id]);
        } else if ($qtype_table == 'eventsignup_response_file') {
            $filerecord = $DB->get_record('eventsignup_response_file', ['response_id' => $reg->id, 'question_id' => $question->id]);
            if ($filerecord) {
                $files = $fs->get_area_files($context->id, 'mod_eventsignup', 'response_attachment', $filerecord->id, 'timemodified', false);
                $fileurls = [];
                foreach($files as $file) {
                    $fileurls[] = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename())->out(false);
                }
                $celldata = implode('; ', $fileurls);
            }
        }
        $row[] = $celldata;
    }
    $csvexporter->add_data($row);
}

// Enviar o ficheiro para o browser
$csvexporter->download_file($filename);
exit;
