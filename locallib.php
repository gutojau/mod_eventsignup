<?php
/**
 * Main local library of functions for the eventsignup module.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * Checks if a given email is already registered for a specific event.
 *
 * @param int $eventsignup_id The ID of the event signup instance.
 * @param string $email The email to check.
 * @return bool True if the email exists, false otherwise.
 */
function eventsignup_is_email_registered(int $eventsignup_id, string $email): bool {
    global $DB;
    $params = ['eventsignup_id' => $eventsignup_id, 'email' => trim(strtolower($email))];
    return $DB->record_exists('eventsignup_registrants', $params);
}

/**
 * Saves a full registration submission, including registrant data, question responses, and files.
 *
 * @param stdClass $eventsignup The eventsignup instance object.
 * @param stdClass $data The data submitted from the registration form.
 * @param stdClass $context The module context.
 * @return int The ID of the new response record.
 * @throws dml_exception If any database operation fails.
 */
function eventsignup_save_submission(stdClass $eventsignup, stdClass $data, stdClass $context): int {
    global $DB;

    // Start a transaction for data integrity.
    $transaction = $DB->start_delegated_transaction();

    try {
        // 1. Create the registrant record.
        $registrant = new stdClass();
        $registrant->eventsignup_id = $eventsignup->id;
        $registrant->firstname = $data->registrant_firstname;
        $registrant->lastname = $data->registrant_lastname;
        $registrant->email = trim(strtolower($data->registrant_email));
        $registrant->phone = $data->registrant_phone ?? null;
        $registrant->timecreated = time();
        $registrant->timemodified = $registrant->timecreated;
        $registrant_id = $DB->insert_record('eventsignup_registrants', $registrant);

        // 2. Create the main response record.
        $response = new stdClass();
        $response->eventsignup_id = $eventsignup->id;
        $response->registrant_id = $registrant_id;
        $response->submitted = time();
        $response->complete = 'y';
        $response->grade = 0; // Not used for grading.
        $response_id = $DB->insert_record('eventsignup_response', $response);

        // 3. Process and save responses for each question.
        foreach ($data as $key => $value) {
            // Process text/textarea/numeric responses
            if (preg_match('/^q(\d+)_text$/', $key, $matches)) {
                $question_id = (int)$matches[1];
                if (!empty($value)) {
                    $text_response = new stdClass();
                    $text_response->response_id = $response_id;
                    $text_response->question_id = $question_id;
                    $text_response->response = $value;
                    $DB->insert_record('eventsignup_response_text', $text_response);
                }
            }
            
            // Process file uploads
            if (preg_match('/^q(\d+)_file$/', $key, $matches)) {
                $question_id = (int)$matches[1];
                $draftitemid = (int)$value;

                // Create a record in our file tracking table. The ID of this record will be the itemid.
                $filerecord = new stdClass();
                $filerecord->response_id = $response_id;
                $filerecord->question_id = $question_id;
                $filerecord->id = $DB->insert_record('eventsignup_response_file', $filerecord, true);

                // Now, move the files from the draft area to the permanent file area for our component.
                if ($draftitemid) {
                     file_save_draft_area_files(
                        $draftitemid,
                        $context->id,
                        'mod_eventsignup',
                        'response_attachment', // This is our defined 'filearea'.
                        $filerecord->id, // Use the new record's ID as the itemid.
                        ['subdirs' => 0]
                    );
                }
            }

            // Note: Add similar logic here for other question types (radio, checkbox, etc.)
            // This will involve checking for keys like 'q_radio' or 'q_check' and inserting
            // into tables like 'eventsignup_response_choice'.
        }

        // 4. Commit the transaction if everything succeeded.
        $transaction->commit();
        return $response_id;

    } catch (Exception $e) {
        // Something went wrong, rollback all database changes.
        $transaction->rollback($e);
        // Rethrow the exception to be handled by the caller.
        throw $e;
    }
}
