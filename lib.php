<?php
/**
 * Biblioteca de funções e constantes para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// ... (todas as funções anteriores: eventsignup_add_instance, etc.) ...

/**
 * Indica se o módulo suporta a inclusão de dados do utilizador nos backups.
 *
 * @return bool True se os dados do utilizador forem suportados, false caso contrário.
 */
function eventsignup_backup_is_included() {
    return true;
}

/**
 * Obtém os contextos onde os ficheiros do módulo são utilizados.
 *
 * @param stdClass $course O objeto do curso.
 * @param stdClass $cm O objeto do módulo do curso.
 * @param stdClass $context O contexto do módulo.
 * @return array Um array de contextos.
 */
function eventsignup_get_file_areas($course, $cm, $context) {
    return [
        'response_attachment' => get_string('response_attachment', 'mod_eventsignup')
    ];
}

/**
 * Devolve uma lista de todos os utilizadores para os quais a atividade tem dados.
 * Como lidamos com utilizadores externos, isto é menos relevante, mas bom de ter.
 *
 * @param int $eventsignupid O ID da instância do eventsignup.
 * @return array|false Um array de objetos de utilizador ou false se não houver dados.
 */
function eventsignup_get_participants($eventsignupid) {
    // Não aplicável para utilizadores externos. Retorna false.
    return false;
}
