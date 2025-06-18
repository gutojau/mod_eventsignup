<?php
/**
 * Define os passos da estrutura do restauro para a atividade eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class restore_eventsignup_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {
        $paths = [];
        
        $paths[] = new restore_path_element('eventsignup', '/activity/eventsignup');
        $paths[] = new restore_path_element('question', '/activity/eventsignup/questions/question');
        
        if ($this->get_setting_value('userinfo')) {
            $paths[] = new restore_path_element('registrant', '/activity/eventsignup/registrants/registrant');
            $paths[] = new restore_path_element('response', '/activity/eventsignup/responses/response');
            $paths[] = new restore_path_element('text_response', '/activity/eventsignup/responses/response/text_responses/text_response');
            $paths[] = new restore_path_element('file_response', '/activity/eventsignup/responses/response/file_responses/file_response');
        }

        return $this->prepare_activity_structure($paths);
    }
    
    public function process_eventsignup($data) {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $data->opendate = $this->apply_date_offset($data->opendate);
        $data->closedate = $this->apply_date_offset($data->closedate);
        
        $newitemid = $DB->insert_record('eventsignup', $data);
        $this->apply_activity_instance($newitemid);
    }

    public function process_question($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        
        $data->survey_id = $this->get_new_parentid('eventsignup');
        
        $newitemid = $DB->insert_record('eventsignup_question', $data);
        $this->set_mapping('question', $oldid, $newitemid);
    }
    
    public function process_registrant($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;

        $data->eventsignup_id = $this->get_new_parentid('eventsignup');
        
        $newitemid = $DB->insert_record('eventsignup_registrants', $data);
        $this->set_mapping('registrant', $oldid, $newitemid);
    }
    
    public function process_response($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;

        $data->eventsignup_id = $this->get_new_parentid('eventsignup');
        $data->registrant_id = $this->get_mappingid('registrant', $data->registrant_id);
        
        $newitemid = $DB->insert_record('eventsignup_response', $data);
        $this->set_mapping('response', $oldid, $newitemid);
    }

    public function process_text_response($data) {
        global $DB;
        $data = (object)$data;
        
        $data->response_id = $this->get_new_parentid('response');
        $data->question_id = $this->get_mappingid('question', $data->question_id);
        
        $DB->insert_record('eventsignup_response_text', $data);
    }
    
    public function process_file_response($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        
        $data->response_id = $this->get_new_parentid('response');
        $data->question_id = $this->get_mappingid('question', $data->question_id);
        
        $newitemid = $DB->insert_record('eventsignup_response_file', $data);
        $this->set_mapping('file_response', $oldid, $newitemid, true); // Mapeia ficheiros
    }

    protected function after_execute() {
        // Adiciona o mapeamento dos ficheiros de contexto.
        $this->add_related_files('mod_eventsignup', 'response_attachment', 'file_response');
    }
}
