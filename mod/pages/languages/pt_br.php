<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Páginas',
	'collection:object:page' => 'Páginas',
	'collection:object:page:all' => "Todas as Páginas do Site",
	'collection:object:page:owner' => "Páginas de %s",
	'collection:object:page:friends' => "Páginas dos Amigos",
	'collection:object:page:group' => "Páginas do Grupo",
	'add:object:page' => "Adicionar uma Página",
	'edit:object:page' => "Editar esta Página",
	'menu:pages_nav:header' => "Subpáginas ",
	'notification:object:page:create' => "Enviar Notificação quando uma Página for criada",
	'notifications:mute:object:page' => "sobre a Página '%s'",
	
	'entity:edit:object:page:success' => 'Página salva com sucesso!',

	'groups:tool:pages' => 'Ativar Páginas do Grupo',
	'groups:tool:pages:description' => 'Permitir que os Membros trabalhem juntos nas Páginas deste Grupo.',
	
	'annotation:delete:page:success' => 'Revisão da Página removida com sucesso!',
	'annotation:delete:page:fail' => 'Não foi possível remover a Revisão da Página',

	'pages:history' => "Histórico",
	'pages:revision' => "Revisão",

	'pages:navigation' => "Navegação",

	'pages:notify:summary' => 'Nova Página chamada %s',
	'pages:notify:subject' => "Uma nova Página: %s",
	'pages:notify:body' => '%s adicionou uma nova Página: %s,

%s

View and comment on the page:
%s',
	
	'notification:mentions:object:page:subject' => '%s mencionou você em uma página',

	'pages:more' => 'Mais Páginas',
	'pages:none' => 'Nenhuma Página foi criada ainda...',

	/**
	* River
	**/

	'river:object:page:create' => '%s criou uma página %s',
	'river:object:page:update' => '%s atualizou uma página %s',
	'river:object:page:comment' => '%s comentou em uma página chamada %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Título da Página',
	'pages:description' => 'Texto da Página',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Página Principal',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Você não pode editar esta Página',
	'pages:saved' => 'Página salva com sucesso!',
	'pages:notsaved' => 'Não foi possível salvar a Página',
	'entity:delete:object:page:success' => 'A Página foi excluída com sucesso!',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisão criada em %s por %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Número de Páginas para Exibir',
	'widgets:pages:name' => 'Páginas',
	'widgets:pages:description' => "Esta é uma Lista das suas Páginas.",

	'pages:newchild' => "Criar uma SubPágina",
);
