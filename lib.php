<?php
/**
 * Biblioteca de funções e constantes para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adiciona uma nova instância de eventsignup.
 *
 * @param stdClass $eventsignup O objeto de dados do formulário de criação.
 * @param mod_eventsignup_mod_form|null $mform O formulário (opcional).
 * @return int O ID da nova instância.
 */
function eventsignup_add_instance(stdClass $eventsignup, mod_eventsignup_mod_form $mform = null) {
    global $DB;
    $eventsignup->timemodified = time();
    $eventsignup->id = $DB->insert_record('eventsignup', $eventsignup);
    return $eventsignup->id;
}

/**
 * Atualiza uma instância existente de eventsignup.
 *
 * @param stdClass $eventsignup O objeto de dados do formulário de atualização.
 * @param mod_eventsignup_mod_form|null $mform O formulário (opcional).
 * @return bool True em caso de sucesso.
 */
function eventsignup_update_instance(stdClass $eventsignup, mod_eventsignup_mod_form $mform = null) {
    global $DB;
    $eventsignup->id = $eventsignup->instance;
    $eventsignup->timemodified = time();
    return $DB->update_record('eventsignup', $eventsignup);
}

/**
 * Exclui uma instância de eventsignup e todos os seus dados associados.
 *
 * @param int $id O ID da instância do eventsignup.
 * @return bool True em caso de sucesso.
 */
function eventsignup_delete_instance($id) {
    global $DB;

    if (!$eventsignup = $DB->get_record('eventsignup', ['id' => $id])) {
        return false;
    }

    $transaction = $DB->start_delegated_transaction();
    
    // Uma implementação mais robusta excluiria os dados de todas as tabelas de resposta.
    $DB->delete_records('eventsignup_question', ['survey_id' => $eventsignup->id]);
    $DB->delete_records('eventsignup_registrants', ['eventsignup_id' => $eventsignup->id]);
    $DB->delete_records('eventsignup_response', ['eventsignup_id' => $eventsignup->id]);
    
    // Finalmente, exclui a própria instância da atividade.
    $DB->delete_records('eventsignup', ['id' => $eventsignup->id]);

    $transaction->commit();

    return true;
}

/**
 * Indica se o módulo suporta a inclusão de dados do utilizador nos backups.
 * @return bool True.
 */
function eventsignup_backup_is_included() {
    return true;
}

/**
 * Estende a navegação para a atividade, adicionando links para edição e relatórios.
 *
 * @param navigation_node $nav O nó de navegação a ser estendido.
 * @param stdClass $course O objeto do curso.
 * @param stdClass $module O objeto do módulo.
 * @param cm_info $cm O objeto de informações do módulo do curso.
 */
function eventsignup_extend_navigation(navigation_node $nav, stdclass $course, stdclass $module, cm_info $cm) {
    $context = $cm->context;
    
    // CORREÇÃO: Adiciona os links diretamente ao nó de navegação da atividade.
    // O Moodle irá colocá-los no menu de administração (engrenagem) automaticamente.
    // Esta abordagem é mais simples e robusta.
    if (has_capability('mod/eventsignup:manage', $context)) {
        $url = new moodle_url('/mod/eventsignup/edit.php', ['id' => $cm->id]);
        $nav->add(get_string('editquestions', 'mod_eventsignup'), $url, navigation_node::TYPE_SETTING);
    }
    if (has_capability('mod/eventsignup:viewreports', $context)) {
        $url = new moodle_url('/mod/eventsignup/report.php', ['id' => $cm->id]);
        $nav->add(get_string('reports', 'mod_eventsignup'), $url, navigation_node::TYPE_SETTING);
    }
}
