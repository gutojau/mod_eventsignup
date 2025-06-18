<?php
/**
 * Define a tarefa de backup para a atividade eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/eventsignup/backup/moodle2/backup_eventsignup_stepslib.php');

class backup_eventsignup_activity_task extends backup_activity_task {

    /**
     * Define a estrutura da tarefa de backup.
     */
    protected function define_my_settings() {
        // Nada de especial aqui, a tarefa principal trata disto.
    }

    /**
     * Define os passos da tarefa de backup.
     */
    protected function define_my_steps() {
        // O único passo é adicionar a estrutura do eventsignup ao backup.
        $this->add_step(new backup_eventsignup_activity_structure_step('eventsignup_structure', 'eventsignup.xml'));
    }

    /**
     * Codifica os caminhos dos ficheiros para serem relativos ao curso/atividade.
     *
     * @param string $content O conteúdo que pode conter URLs de ficheiros.
     * @return string O conteúdo com os caminhos dos ficheiros codificados.
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');
        // Link para view.php.
        $search = "/(".$base."\/mod\/eventsignup\/view.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@EVENTSIGNUPVIEWBYID*$2@$', $content);

        return $content;
    }
}
