<?php
/**
 * Ficheiro de idioma para o módulo eventsignup.
 *
 * @package   mod_eventsignup
 * @copyright 2024 Seu Nome
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Inscrição para Eventos';
$string['modulename'] = 'Inscrição para Eventos';
$string['modulename_plural'] = 'Inscrições para Eventos';

// Capacidades
$string['eventsignup:addinstance'] = 'Adicionar uma nova inscrição para eventos';
$string['eventsignup:manage'] = 'Gerir perguntas do evento';
$string['eventsignup:submit'] = 'Submeter uma inscrição';
$string['eventsignup:viewreports'] = 'Ver relatórios de inscrição';

// Formulário de Inscrição
$string['fullname'] = 'Nome Completo';
$string['cpf'] = 'CPF';
$string['email'] = 'E-mail';
$string['phone'] = 'Telefone (Opcional)';
$string['submit_registration'] = 'Submeter Inscrição';
$string['registration_form'] = 'Formulário de Inscrição';
$string['eventnotavailable'] = 'As inscrições para este evento não estão disponíveis neste momento.';

// Validação e Erros
$string['erroremailexists'] = 'Este e-mail já foi utilizado para se inscrever neste evento.';
$string['errorcpfexists'] = 'Este CPF já foi utilizado para se inscrever neste evento.';
$string['errorinvalidcpf'] = 'O CPF fornecido não é válido.';
$string['invalidemail'] = 'O endereço de e-mail não é válido.';
$string['error_saving_registration'] = 'Ocorreu um erro ao guardar a sua inscrição. Por favor, tente novamente.';
$string['registrationsuccess'] = 'Inscrição Realizada com Sucesso!';
$string['registrationsuccess_message'] = 'Obrigado por se inscrever no evento.';

// Gestão de Perguntas
$string['editquestions'] = 'Editar Perguntas';
$string['question'] = 'Pergunta';
$string['questions'] = 'Perguntas Adicionais';
$string['type'] = 'Tipo';
$string['required'] = 'Obrigatório';
$string['action'] = 'Ação';
$string['position'] = 'Posição';
$string['noquestions'] = 'Ainda não foram adicionadas perguntas.';
$string['addnewquestion'] = 'Adicionar nova pergunta';
$string['questiontype'] = 'Tipo de Pergunta';
$string['questiontext'] = 'Texto da Pergunta';
$string['commonsettings'] = 'Definições Comuns';
$string['confirmdeletequestion'] = 'Tem a certeza de que quer excluir esta pergunta?';
$string['moveup'] = 'Mover para cima';
$string['movedown'] = 'Mover para baixo';

// Tipos de Pergunta
$string['questiontype_file'] = 'Envio de Ficheiro';
$string['questiontype_text'] = 'Campo de Texto';
$string['questiontype_textarea'] = 'Área de Texto';
$string['file_maxfiles'] = 'Número máximo de ficheiros';
$string['file_maxsize'] = 'Tamanho máximo do ficheiro';
$string['file_filetypes'] = 'Tipos de ficheiro aceites';
$string['file_filetypes_help'] = 'Insira uma lista de extensões de ficheiro separadas por vírgula (ex: .pdf,.docx).';

// Relatórios
$string['reports'] = 'Relatórios';
$string['noresponses'] = 'Ainda não há respostas.';
$string['submitted'] = 'Data de Submissão';
$string['downloadreport'] = 'Descarregar Relatório';
$string['exportcsv'] = 'Exportar para CSV';
