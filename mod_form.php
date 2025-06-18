<?php
/**
 * The main form for setting up the eventsignup module instances.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_eventsignup_mod_form extends moodleform_mod {

    /**
     * Defines the form structure.
     */
    protected function definition() {
        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // General settings
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Event name
        $mform->addElement('text', 'name', get_string('name'), ['size' => '64']);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_RAW);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        // Introduction text
        $this->add_intro_editor(true, get_string('intro', 'mod_eventsignup'));

        //-------------------------------------------------------------------------------
        // Availability settings
        $mform->addElement('header', 'availability', get_string('availability'));

        // Open date
        $mform->addElement('date_time_selector', 'opendate', get_string('opendate', 'mod_eventsignup'));
        $mform->setDefault('opendate', time());

        // Close date
        $mform->addElement('date_time_selector', 'closedate', get_string('closedate', 'mod_eventsignup'));
        $mform->setDefault('closedate', 0);

        //-------------------------------------------------------------------------------
        // Standard Moodle module settings
        $this->standard_coursemodule_elements();

        //-------------------------------------------------------------------------------
        // Action buttons
        $this->add_action_buttons();
    }
}
