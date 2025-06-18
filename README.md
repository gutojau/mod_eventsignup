Módulo de Inscrição para Eventos (mod_eventsignup) para Moodle

Um módulo de atividade flexível para o Moodle que permite a criação de formulários de inscrição para eventos, palestras, workshops e conferências. Desenhado para ser acessível publicamente, permitindo que utilizadores sem conta na plataforma possam inscrever-se de forma simples e segura.

Este plugin foi inspirado e estruturado a partir do popular módulo mod_questionnaire, herdando a sua flexibilidade na criação de perguntas e expandindo a sua funcionalidade para o registo de participantes externos.
Funcionalidades Principais

    Inscrições Públicas: Permite que qualquer pessoa com o link se inscreva, sem necessidade de autenticação no Moodle.

    Controlo de Duplicidade: Valida as inscrições por e-mail para garantir que cada participante se inscreve apenas uma vez por evento.

    Construtor de Formulários Dinâmico: Crie formulários personalizados com diferentes tipos de perguntas para recolher a informação de que necessita.

    Tipo de Pergunta "Upload de Ficheiro": Permite que os inscritos anexem documentos à sua inscrição. Ideal para submissão de artigos, comprovativos ou currículos.

        Defina o número máximo de ficheiros.

        Limite o tamanho máximo de cada ficheiro (respeitando os limites do servidor).

        Restrinja os tipos de ficheiro permitidos (ex: .pdf, .docx, .jpg).

    Gestão Completa de Perguntas:

        Adicione, edite e remova perguntas a qualquer momento.

        Reordene as perguntas facilmente com controlos de "mover para cima/baixo".

        Marque perguntas como obrigatórias.

    Relatórios e Exportação:

        Visualize todas as inscrições numa tabela clara e organizada.

        Aceda e descarregue os ficheiros enviados diretamente a partir do relatório.

        Exporte todos os dados dos inscritos para o formato CSV com um único clique.

    Integração com Backup e Restauro: A atividade é totalmente compatível com o sistema de backup e restauro do Moodle, garantindo a portabilidade e a segurança dos dados.

    Controlo de Disponibilidade: Configure datas de abertura e fecho para as inscrições.

Requisitos

    Moodle 4.1 ou superior.

Instalação

    Via Ficheiro ZIP:

        Descarregue o ficheiro .zip a partir da página de releases do GitHub.

        Aceda ao seu site Moodle como administrador.

        Navegue até Administração do site > Plugins > Instalar plugins.

        Arraste e solte o ficheiro .zip na área designada e clique em "Instalar plugin a partir do ficheiro ZIP".

        Siga as instruções no ecrã para concluir a instalação.

    Via Git:

        Navegue até o diretório raiz do seu Moodle no terminal.

        Clone o repositório para a pasta mod/:

        git clone https://github.com/SEU_UTILIZADOR/moodle-mod_eventsignup.git mod/eventsignup

        Aceda à área de notificações (Administração do site > Notificações) para que o Moodle detete e instale o novo plugin.

Como Usar

    Num curso, ative o modo de edição.

    Clique em "Adicionar uma atividade ou recurso".

    Selecione "Inscrição para Eventos" na lista.

    Dê um nome e uma descrição ao seu evento. Configure as datas de abertura e fecho na secção "Disponibilidade".

    Clique em "Guardar e mostrar".

    Será redirecionado para a página de gestão de perguntas. Aqui pode:

        Adicionar novas perguntas selecionando um tipo no menu suspenso.

        Editar, mover ou excluir as perguntas existentes utilizando os ícones de ação.

    Após configurar o formulário, partilhe o link da atividade com o seu público. O link pode ser encontrado no seu browser ao visualizar a atividade (ex: https://seumoodle.com/mod/eventsignup/view.php?id=XX).

Screenshots

Interface Pública de Inscrição
	

Gestão de Perguntas
	

Relatório de Inscrições

[Imagem do formulário de inscrição público]
	

[Imagem da interface de edição de perguntas]
	

[Imagem da tabela de relatórios com as respostas]
Licença

Este plugin é distribuído sob a Licença Pública Geral GNU v3 ou posterior.

Desenvolvido com a colaboração de um assistente de IA.
