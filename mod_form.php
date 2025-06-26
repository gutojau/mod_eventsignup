<?php
/**
 * O formulário principal para configurar as instâncias do módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_eventsignup_mod_form extends moodleform_mod {

    /**
     * Define a estrutura do formulário.
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->add_intro_editor(true, get_string('intro', 'mod_eventsignup'));

        $mform->addElement('header', 'availability', get_string('availability'));

        // Tornar as datas opcionais para evitar erros se não forem definidas.
        $mform->addElement('date_time_selector', 'opendate', get_string('opendate', 'mod_eventsignup'), ['optional' => true]);
        $mform->addElement('date_time_selector', 'closedate', get_string('closedate', 'mod_eventsignup'), ['optional' => true]);

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}
