<?php
/**
 * Página de índice para o módulo eventsignup. Redireciona para a página do curso.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = required_param('id', PARAM_INT); // ID do curso

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
$url = new moodle_url('/course/view.php', ['id' => $id]);

redirect($url);
