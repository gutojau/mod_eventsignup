<?php
/**
 * Define a tarefa de restauro para a atividade eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/eventsignup/backup/moodle2/restore_eventsignup_stepslib.php');

class restore_eventsignup_activity_task extends restore_activity_task {

    protected function define_my_settings() {
        // Nada de especial aqui.
    }

    protected function define_my_steps() {
        // O único passo é restaurar a estrutura do eventsignup.
        $this->add_step(new restore_eventsignup_activity_structure_step('eventsignup_structure', 'eventsignup.xml'));
    }
    
    /**
     * Decodifica os links de conteúdo após o restauro.
     *
     * @param string $content O conteúdo com os links codificados.
     * @return string O conteúdo com os links decodificados.
     */
    static public function decode_content_links($content) {
        global $CFG;

        $search = "/(\$@EVENTSIGNUPVIEWBYID\*)(\d+)(\$@)/";
        $content = preg_replace_callback($search, 'restore_eventsignup_activity_task::decode_content_links_callback', $content);

        return $content;
    }

    /**
     * Função de callback para decodificar os links.
     *
     * @param array $matches O array de correspondências da expressão regular.
     * @return string O URL decodificado.
     */
    static public function decode_content_links_callback($matches) {
        global $CFG;
        // Obtém o ID do course_module a partir do mapeamento.
        $cmid = restore_by_id_mapper::get_instance()->get_new_id('course_module', $matches[2]);
        if ($cmid) {
            return $CFG->wwwroot . '/mod/eventsignup/view.php?id=' . $cmid;
        }
        return '<!-- eventsignup link broken -->';
    }
}
