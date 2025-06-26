<?php
/**
 * Procedimentos de atualização para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Função principal de atualização.
 * O nome da função deve ser xmldb_NOMEPLUGIN_upgrade.
 *
 * @param int $oldversion A versão da qual estamos a atualizar.
 * @return bool
 */
function xmldb_eventsignup_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Gestor DDL

    // Os passos de atualização para versões futuras seriam adicionados aqui.
    // Exemplo:
    // if ($oldversion < 2024062503) {
    //     // ... código para alterar a base de dados ...
    //     upgrade_mod_savepoint(true, 2024062503, 'eventsignup');
    // }

    return true;
}
