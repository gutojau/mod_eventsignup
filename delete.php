<?php
/**
 * Script para excluir uma pergunta.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id  = required_param('id', PARAM_INT); // ID do course_module
$qid = required_param('qid', PARAM_INT); // ID da pergunta a ser excluída
require_sesskey();

if (!$cm = get_coursemodule_from_id('eventsignup', $id)) {
    print_error('invalidcoursemodule');
}
// ... (adicionar outras verificações necessárias para $course, $eventsignup) ...

$context = context_module::instance($cm->id);
require_capability('mod/eventsignup:manage', $context);

if ($question = $DB->get_record('eventsignup_question', ['id' => $qid, 'survey_id' => $cm->instance])) {
    // Em vez de excluir, marcamos como excluído para manter os dados de resposta intactos.
    $question->deleted = 'y';
    $DB->update_record('eventsignup_question', $question);
    
    // Reordenar as perguntas restantes
    $sql = "UPDATE {eventsignup_question} SET position = position - 1 
            WHERE survey_id = :surveyid AND position > :oldposition AND deleted = 'n'";
    $DB->execute($sql, ['surveyid' => $cm->instance, 'oldposition' => $question->position]);
}

redirect(new moodle_url('/mod/eventsignup/edit.php', ['id' => $cm->id]));
