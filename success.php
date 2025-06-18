<?php
/**
 * Displays a success message after a registration is submitted.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = required_param('id', PARAM_INT); // course_module ID

if (!$cm = get_coursemodule_from_id('eventsignup', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
    print_error('coursemisconf');
}
if (!$eventsignup = $DB->get_record('eventsignup', ['id' => $cm->instance])) {
    print_error('invalideventsignupid', 'mod_eventsignup');
}

$PAGE->set_url('/mod/eventsignup/success.php', ['id' => $cm->id]);
$PAGE->set_title(get_string('registrationsuccess', 'mod_eventsignup'));
$PAGE->set_heading(format_string($course->fullname));
$context = context_module::instance($cm->id);
$PAGE->set_context($context);


echo $OUTPUT->header();

$message = get_string('registrationsuccess_message', 'mod_eventsignup');
echo $OUTPUT->notification($message, 'notifysuccess');

// Provide a link back to the course page.
$link = new moodle_url('/course/view.php', ['id' => $course->id]);
echo $OUTPUT->continue_button($link);

echo $OUTPUT->footer();
