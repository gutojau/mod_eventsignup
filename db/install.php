<?php
/**
 * Script de instalação para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_eventsignup_install() {
    global $DB;

    $question_types = [
        ['type' => 'yesno',      'has_choices' => 'y', 'response_table' => 'eventsignup_response_choice'],
        ['type' => 'text',       'has_choices' => 'n', 'response_table' => 'eventsignup_response_text'],
        ['type' => 'textarea',   'has_choices' => 'n', 'response_table' => 'eventsignup_response_text'],
        ['type' => 'numeric',    'has_choices' => 'n', 'response_table' => 'eventsignup_response_numeric'],
        ['type' => 'date',       'has_choices' => 'n', 'response_table' => 'eventsignup_response_date'],
        ['type' => 'radio',      'has_choices' => 'y', 'response_table' => 'eventsignup_response_choice'],
        ['type' => 'checkbox',   'has_choices' => 'y', 'response_table' => 'eventsignup_response_choice'],
        ['type' => 'dropdown',   'has_choices' => 'y', 'response_table' => 'eventsignup_response_choice'],
        ['type' => 'rate',       'has_choices' => 'y', 'response_table' => 'eventsignup_response_choice'],
        ['type' => 'file',       'has_choices' => 'n', 'response_table' => 'eventsignup_response_file'],
        ['type' => 'pagebreak',  'has_choices' => 'n', 'response_table' => null],
    ];

    foreach ($question_types as $qt) {
        if (!$DB->record_exists('eventsignup_question_type', ['type' => $qt['type']])) {
            $DB->insert_record('eventsignup_question_type', (object)$qt);
        }
    }

    return true;
}
