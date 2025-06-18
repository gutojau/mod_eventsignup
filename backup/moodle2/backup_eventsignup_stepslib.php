<?php
/**
 * Define os passos da estrutura do backup para a atividade eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class backup_eventsignup_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Para verificar se os dados do utilizador devem ser incluídos.
        $userinfo = $this->get_setting_value('userinfo');

        // Nó principal 'eventsignup'.
        $eventsignup = new backup_nested_element('eventsignup', ['id'], [
            'course', 'name', 'intro', 'introformat', 'qtype', 'respondenttype', 'sid', 'theme',
            'notifications', 'opendate', 'closedate', 'resume', 'navigate', 'autonum',
            'timemodified', 'grade'
        ]);

        // Nó 'questions'.
        $questions = new backup_nested_element('questions');

        // Nó 'question' individual.
        $question = new backup_nested_element('question', ['id'], [
            'name', 'type_id', 'result_id', 'length', 'precise', 'position', 'content', 'required', 'deleted', 'extradata'
        ]);
        
        // Adiciona a questão como filha das questões.
        $questions->add_child($question);
        // Adiciona as questões como filhas do eventsignup.
        $eventsignup->add_child($questions);

        // Define a fonte dos dados para a tabela principal do eventsignup.
        $eventsignup->set_source_table('eventsignup', ['id' => backup::VAR_ACTIVITYID]);
        
        // Define a fonte para as perguntas.
        $question->set_source_table('eventsignup_question', ['survey_id' => backup::VAR_PARENTID]);

        // Se os dados do utilizador estiverem incluídos, adiciona as tabelas de inscritos e respostas.
        if ($userinfo) {
            // Nó 'registrants'.
            $registrants = new backup_nested_element('registrants');
            $registrant = new backup_nested_element('registrant', ['id'], [
                'firstname', 'lastname', 'email', 'phone', 'timecreated', 'timemodified'
            ]);
            $registrants->add_child($registrant);
            $eventsignup->add_child($registrants);
            $registrant->set_source_table('eventsignup_registrants', ['eventsignup_id' => backup::VAR_PARENTID]);

            // Nó 'responses'.
            $responses = new backup_nested_element('responses');
            $response = new backup_nested_element('response', ['id'], [
                'registrant_id', 'submitted', 'complete', 'grade'
            ]);
            $responses->add_child($response);
            $eventsignup->add_child($responses);
            $response->set_source_table('eventsignup_response', ['eventsignup_id' => backup::VAR_PARENTID]);

            // Respostas de texto.
            $text_responses = new backup_nested_element('text_responses');
            $text_response = new backup_nested_element('text_response', ['id'], ['question_id', 'response']);
            $text_responses->add_child($text_response);
            $response->add_child($text_responses);
            $text_response->set_source_table('eventsignup_response_text', ['response_id' => backup::VAR_PARENTID]);
            
            // Respostas de ficheiro.
            $file_responses = new backup_nested_element('file_responses');
            $file_response = new backup_nested_element('file_response', ['id'], ['question_id']);
            $file_responses->add_child($file_response);
            $response->add_child($file_responses);
            $file_response->set_source_table('eventsignup_response_file', ['response_id' => backup::VAR_PARENTID]);
            
            // Adiciona a anotação para ficheiros.
            $file_response->annotate_files('mod_eventsignup', 'response_attachment', 'id');
        }

        // Devolve a estrutura completa para ser usada pelo backup.
        return $this->prepare_activity_structure($eventsignup);
    }
}
