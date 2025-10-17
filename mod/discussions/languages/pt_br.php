<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Tópico de Discussão",
	'collection:object:discussion' => 'Tópicos de Discussão',
	
	'add:object:discussion' => 'Adicionar Tópico de Discussão',
	'edit:object:discussion' => 'Editar Tópico ',
	'collection:object:discussion:group' => 'Discussões no Grupo',
	'collection:object:discussion:my_groups' => 'Discussões em Meus Grupos',
	
	'notification:object:discussion:create' => "Enviar uma notificação quando uma discussão for criada",
	'notifications:mute:object:discussion' => "sobre a discussão '%s'",
	
	'discussion:settings:enable_global_discussions' => 'Habilitar Discussões Globais',
	'discussion:settings:enable_global_discussions:help' => 'Permitir que discussões sejam criadas fora dos grupos',

	'discussion:latest' => 'Últimas discussões',
	'discussion:updated' => "Último comentário por %s %s",

	'discussion:topic:created' => 'O tópico de discussão foi criado.',
	'discussion:topic:updated' => 'O tópico de discussão foi atualizado.',
	'entity:delete:object:discussion:success' => 'O tópico de discussão foi excluído.',
	
	'entity:edit:object:discussion:success' => 'A discussão foi salva com sucesso',
	
	'discussion:topic:notfound' => 'Tópico de discussão não encontrado',
	'discussion:error:notsaved' => 'Não é possível salvar este tópico',
	'discussion:error:missing' => 'Título e mensagem são campos obrigatórios',
	'discussion:error:permissions' => 'Você não tem permissão para executar esta ação',
	'discussion:error:no_groups' => "Você não é membro de nenhum grupo.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s adicionou um novo tópico de discussão %s',
	'river:object:discussion:comment' => '%s comentou no tópico de discussão %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Novo tópico de discussão chamado %s',
	'discussion:topic:notify:subject' => 'Novo tópico de discussão: %s',

	'discussion:comment:notify:summary' => 'Novo comentário no tópico: %s',
	'discussion:comment:notify:subject' => 'Novo comentário no tópico: %s',
	
	'notification:mentions:object:discussion:subject' => '%s mencionou você em uma discussão',

	'groups:tool:forum' => 'Habilitar discussões em grupo',
	'groups:tool:forum:description' => 'Permita que os membros do grupo iniciem uma discussão neste grupo.',

	'discussions:groups:edit:add_group_subscribers_to_discussion_comments' => 'Adicionar assinante do grupo às notificações de comentários da discussão',
	
	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status do Tópico',
	'discussion:topic:closed:title' => 'Esta discussão está encerrada.',
	'discussion:topic:closed:desc' => 'Esta discussão está encerrada e não está aceitando novos comentários.',
	'discussion:topic:container' => 'Selecione um grupo opcional para iniciar esta discussão',
	'discussion:topic:container:help' => 'Iniciar uma discussão em um grupo limitará o acesso aos membros do grupo por padrão',

	'discussion:topic:description' => 'Mensagem do Tópico',
	'discussion:topic:toggle_status:open' => 'O tópico de discussão foi reaberto com sucesso',
	'discussion:topic:toggle_status:open:confirm' => 'Tem certeza de que deseja reabrir este tópico?',
	'discussion:topic:toggle_status:closed' => 'O tópico de discussão foi fechado com sucesso',
	'discussion:topic:toggle_status:closed:confirm' => 'Tem certeza de que deseja fechar este tópico?',
	
	// widgets
	'widgets:discussions:name' => 'Discussões',
	'widgets:discussions:description' => 'Mostra discussões recentes',
);
