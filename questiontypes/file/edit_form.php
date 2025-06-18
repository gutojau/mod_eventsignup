<?php
/**
 * Form for editing the 'File Upload' question type.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_eventsignup\questiontype\file;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/eventsignup/classes/form/question_edit_form.php');

class edit_form extends \mod_eventsignup\form\question_edit_form {

    /**
     * Add file-upload specific elements to the form.
     */
    protected function add_question_type_specific_elements() {
        $mform = $this->_form;

        $mform->addElement('header', 'filesettings', get_string('questiontype_file', 'mod_eventsignup'));

        $mform->addElement('select', 'extradata[maxfiles]', get_string('file_maxfiles', 'mod_eventsignup'), range(1, 10));
        $mform->setDefault('extradata[maxfiles]', 1);

        // This should ideally use the file_size_selector options from moodlelib.
        $mform->addElement('text', 'extradata[maxsize]', get_string('file_maxsize', 'mod_eventsignup'));
        $mform->setType('extradata[maxsize]', PARAM_INT);
        $mform->setDefault('extradata[maxsize]', 0); // 0 = site limit

        $mform->addElement('text', 'extradata[filetypes]', get_string('file_filetypes', 'mod_eventsignup'));
        $mform->setType('extradata[filetypes]', PARAM_TEXT);
        $mform->addHelpButton('extradata[filetypes]', 'file_filetypes', 'mod_eventsignup');
    }

    /**
     * Prepare data before it is saved.
     * Here we serialize the extra data into a single string for the database.
     */
    public function get_data() {
        $data = parent::get_data();
        if ($data && isset($data->extradata)) {
            $data->extradata = serialize($data->extradata);
        }
        return $data;
    }

    /**
     * Prepare data before it is displayed on the form.
     * Unserialize the extra data from the database.
     */
    public function set_data($default_values) {
        if (is_object($default_values) && !empty($default_values->extradata)) {
            $extradata = unserialize($default_values->extradata);
            foreach ($extradata as $key => $value) {
                $default_values->{'extradata[' . $key . ']'} = $value;
            }
        }
        parent::set_data($default_values);
    }
}
