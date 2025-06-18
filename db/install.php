<?php
/**
 * Installation script for the eventsignup module.
 * Populates the question type table.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Executed upon installation of the plugin.
 *
 * @return bool
 */
function xmldb_mod_eventsignup_install() {
    global $DB;

    // Define the question types that this plugin supports.
    $question_types = [
        ['type' => 'text', 'has_choices' => 'n', 'response_table' => 'eventsignup_response_text'],
        ['type' => 'textarea', 'has_choices' => 'n', 'response_table' => 'eventsignup_response_text'],
        ['type' => 'file', 'has_choices' => 'n', 'response_table' => 'eventsignup_response_file'],
        // Add other types like 'radio', 'checkbox', 'select' here in the future.
    ];

    foreach ($question_types as $qt) {
        if (!$DB->record_exists('eventsignup_question_type', ['type' => $qt['type']])) {
            $record = new stdClass();
            $record->type = $qt['type'];
            $record->has_choices = $qt['has_choices'];
            $record->response_table = $qt['response_table'];
            $DB->insert_record('eventsignup_question_type', $record);
        }
    }

    return true;
}
