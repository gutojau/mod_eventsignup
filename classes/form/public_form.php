<?php
/**
 * O formulário virado para o público para a inscrição em eventos.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_eventsignup\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class public_form extends \moodleform {

    protected function definition() {
        global $DB;

        $mform = $this->_form;
        $eventsignup = $this->_customdata['eventsignup'];

        // --- Dados de Identificação ---
        $mform->addElement('header', 'registrantheader', get_string('registration_form', 'mod_eventsignup'));

        $mform->addElement('text', 'fullname', get_string('fullname', 'mod_eventsignup'));
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addRule('fullname', get_string('required'), 'required');

        $mform->addElement('text', 'cpf', get_string('cpf', 'mod_eventsignup'));
        $mform->setType('cpf', PARAM_RAW); // RAW para obter os dígitos puros
        $mform->addRule('cpf', get_string('required'), 'required');

        $mform->addElement('text', 'email', get_string('email', 'mod_eventsignup'));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', get_string('required'), 'required');

        $mform->addElement('text', 'phone', get_string('phone', 'mod_eventsignup'));
        $mform->setType('phone', PARAM_TEXT);
        
        // --- Perguntas Adicionais ---
        $questions = $DB->get_records('eventsignup_question', ['survey_id' => $eventsignup->id, 'deleted' => 'n'], 'position ASC');
        if ($questions) {
            $mform->addElement('header', 'questionsheader', get_string('questions', 'mod_eventsignup'));
            foreach ($questions as $question) {
                // ... (lógica para adicionar perguntas como antes) ...
            }
        }

        $this->add_action_buttons(false, get_string('submit_registration', 'mod_eventsignup'));
    }

    /**
     * Validação personalizada do formulário.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $eventsignup = $this->_customdata['eventsignup'];

        // Validação do CPF
        if (!empty($data['cpf'])) {
            $cpf = preg_replace('/[^0-9]/', '', $data['cpf']);
            if (!\eventsignup_validate_cpf($cpf)) {
                $errors['cpf'] = get_string('errorinvalidcpf', 'mod_eventsignup');
            } else if (\eventsignup_is_cpf_registered($eventsignup->id, $cpf)) {
                $errors['cpf'] = get_string('errorcpfexists', 'mod_eventsignup');
            }
        }
        
        return $errors;
    }
}
