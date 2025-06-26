<?php
/**
 * Página para editar as perguntas para uma instância de eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

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
require_capability('mod/eventsignup:manage', $context);

// Configuração da PÁGINA na ordem correta
$PAGE->set_url('/mod/eventsignup/edit.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(format_string($eventsignup->name));
$PAGE->set_heading(get_string('editquestions', 'mod_eventsignup'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('editquestions', 'mod_eventsignup'));

// --- Lógica para exibir a tabela de perguntas existentes ---
$questions = $DB->get_records('eventsignup_question', ['survey_id' => $eventsignup->id, 'deleted' => 'n'], 'position ASC');
$totalquestions = count($questions);

if ($questions) {
    $table = new html_table();
    $table->head = [
        get_string('position', 'mod_eventsignup'),
        get_string('question'),
        get_string('type'),
        get_string('required'),
        get_string('action')
    ];
    $table->data = [];

    $currentpos = 1;
    foreach ($questions as $question) {
        $qtype = $DB->get_field('eventsignup_question_type', 'type', ['id' => $question->type_id]);
        $required = $question->required ? get_string('yes') : get_string('no');

        // Ícones de Ação
        $actions = '';
        // Link de Edição
        $actions .= html_writer::link(new moodle_url('/mod/eventsignup/question.php', ['id' => $cm->id, 'qid' => $question->id]), $OUTPUT->pix_icon('t/edit', get_string('edit')));
        
        // Link de Exclusão com confirmação
        $deleteurl = new moodle_url('/mod/eventsignup/delete.php', ['id' => $cm->id, 'qid' => $question->id, 'sesskey' => sesskey()]);
        $actions .= ' ' . html_writer::link($deleteurl, $OUTPUT->pix_icon('t/delete', get_string('delete')), ['onclick' => 'return confirm("'.get_string('confirmdeletequestion', 'mod_eventsignup').'")']);

        // Links de Mover
        if ($currentpos > 1) {
            $upurl = new moodle_url('/mod/eventsignup/move.php', ['id' => $cm->id, 'qid' => $question->id, 'direction' => 'up', 'sesskey' => sesskey()]);
            $actions .= ' ' . html_writer::link($upurl, $OUTPUT->pix_icon('t/up', get_string('moveup')));
        }
        if ($currentpos < $totalquestions) {
            $downurl = new moodle_url('/mod/eventsignup/move.php', ['id' => $cm->id, 'qid' => $question->id, 'direction' => 'down', 'sesskey' => sesskey()]);
            $actions .= ' ' . html_writer::link($downurl, $OUTPUT->pix_icon('t/down', get_string('movedown')));
        }
        
        $row = [
            $question->position,
            $question->content,
            get_string('questiontype_' . $qtype, 'mod_eventsignup'),
            $required,
            $actions
        ];
        $table->data[] = new html_table_row($row);
        $currentpos++;
    }
    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('noquestions', 'mod_eventsignup'));
}

// --- Lógica para adicionar uma nova pergunta ---
echo $OUTPUT->box_start();
echo '<p>' . get_string('addnewquestion', 'mod_eventsignup') . '</p>';

// Usar nomes de tipo como chaves e valores para o menu suspenso
$questiontypes = $DB->get_records_menu('eventsignup_question_type', [], 'type ASC', 'type, type');
// Traduzir os nomes dos tipos para exibição
$displaytypes = [];
foreach ($questiontypes as $key => $value) {
    $displaytypes[$key] = get_string('questiontype_'.$key, 'mod_eventsignup');
}

$addquestionurl = new moodle_url('/mod/eventsignup/question.php', ['id' => $cm->id]);

$select = new single_select($addquestionurl, 'type', $displaytypes, null, ['' => get_string('choosedots')]);
$select->set_label(get_string('questiontype', 'mod_eventsignup'));
echo $OUTPUT->render($select);

echo $OUTPUT->box_end();

echo $OUTPUT->footer();