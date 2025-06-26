<?php
/**
 * Biblioteca local principal de funções para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Valida um número de CPF.
 * @param string $cpf O CPF (apenas dígitos).
 * @return bool
 */
function eventsignup_validate_cpf(string $cpf): bool {
    if (empty($cpf) || strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

/**
 * Verifica se um CPF já está registado.
 */
function eventsignup_is_cpf_registered(int $eventsignup_id, string $cpf): bool {
    global $DB;
    $cpfclean = preg_replace('/[^0-9]/', '', $cpf);
    return $DB->record_exists('eventsignup_registrants', ['eventsignup_id' => $eventsignup_id, 'cpf' => $cpfclean]);
}

/**
 * Guarda uma submissão de inscrição completa.
 */
function eventsignup_save_submission(stdClass $eventsignup, stdClass $data, stdClass $context): int {
    global $DB;
    // ... (lógica de guardar, agora incluindo o campo 'fullname' e 'cpf') ...
    $registrant->fullname = $data->fullname;
    $registrant->cpf = preg_replace('/[^0-9]/', '', $data->cpf);
    // ...
    return 0; // Retorno de exemplo
}

// O resto do ficheiro locallib.php...
