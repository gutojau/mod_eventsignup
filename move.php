<?php
/**
 * Script para mover uma pergunta para cima ou para baixo na ordem.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id        = required_param('id', PARAM_INT);
$qid       = required_param('qid', PARAM_INT);
$direction = required_param('direction', PARAM_ALPHA);
require_sesskey();

if (!$cm = get_coursemodule_from_id('eventsignup', $id)) {
    print_error('invalidcoursemodule');
}
if (!$eventsignup = $DB->get_record('eventsignup', ['id' => $cm->instance])) {
    print_error('invalideventsignupid', 'mod_eventsignup');
}

$context = context_module::instance($cm->id);
require_login($cm->course, true, $cm);
require_capability('mod/eventsignup:manage', $context);

$question = $DB->get_record('eventsignup_question', ['id' => $qid, 'survey_id' => $eventsignup->id], '*', MUST_EXIST);

$currentpos = $question->position;
$newpos = ($direction == 'up') ? $currentpos - 1 : $currentpos + 1;

if ($newpos < 1) {
    redirect(new moodle_url('/mod/eventsignup/edit.php', ['id' => $cm->id]));
}

if ($otherquestion = $DB->get_record('eventsignup_question', ['survey_id' => $eventsignup->id, 'position' => $newpos, 'deleted' => 'n'])) {
    $transaction = $DB->start_delegated_transaction();
    
    $otherquestion->position = $currentpos;
    $DB->update_record('eventsignup_question', $otherquestion);
    
    $question->position = $newpos;
    $DB->update_record('eventsignup_question', $question);
    
    $transaction->commit();
}

redirect(new moodle_url('/mod/eventsignup/edit.php', ['id' => $cm->id]));
