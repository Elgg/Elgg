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
	'item:object:bookmarks' => 'Marcador',
	'collection:object:bookmarks' => 'Marcadores',
	
	'collection:object:bookmarks:group' => 'Marcadores do Grupo',
	'collection:object:bookmarks:all' => "Todos os Marcadores do Site",
	'collection:object:bookmarks:owner' => "Marcadores de %s",
	'collection:object:bookmarks:friends' => "Marcadores dos Amigos",
	'add:object:bookmarks' => "Adicionar um Marcador",
	'edit:object:bookmarks' => "Editar Marcadores",
	
	'notification:object:bookmarks:create' => "Enviar uma Notificação quando um Marcador for criado",
	'notifications:mute:object:bookmarks' => "sobre o marcador '%s'",

	'bookmarks:this' => "Marcar esta página",
	'bookmarks:this:group' => "Marcado em '%s'",
	'bookmarks:bookmarklet' => "Obter marcadores",
	'bookmarks:bookmarklet:group' => "Obter marcadores do grupo",
	'bookmarks:address' => "Endereço do Marcador",

	'bookmarks:notify:summary' => 'Novo marcador chamado \'%s\'',
	'bookmarks:notify:subject' => 'Novo marcador: \'%s\'',
	
	'notification:mentions:object:bookmarks:subject' => '%s mencionou você em uma marcação',
	
	'bookmarks:numbertodisplay' => 'Número de Marcadores para exibir',

	'river:object:bookmarks:create' => '%s marcou %s',
	'river:object:bookmarks:comment' => '%s comentou em uma marcação %s',

	'groups:tool:bookmarks' => 'Habilitar Marcadores no Grupo',
	'groups:tool:bookmarks:description' => 'Permitir que os Membros compartilhem marcadores neste grupo.',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Marcadores',
	'widgets:bookmarks:description' => "Mostrar seus marcadores recentes.",

	'bookmarks:bookmarklet:description' => "Um bookmarklet é um tipo especial de botão que você salva na barra de links do seu navegador. Ele permite que você salve qualquer recurso encontrado na web nos seus favoritos. Para configurá-lo, arraste o botão abaixo para a barra de links do seu navegador:",
	'bookmarks:bookmarklet:descriptionie' => "Se estiver usando o Internet Explorer, você precisará clicar com o botão direito do mouse no ícone do bookmarklet, selecionar \"Adicionar aos favoritos\" e, em seguida, na barra de links.",
	'bookmarks:bookmarklet:description:conclusion' => "Você pode então marcar qualquer página que visitar clicando no botão no seu navegador a qualquer momento.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Seu item foi marcado com sucesso.",
	'entity:edit:object:bookmarks:success' => "O marcador foi salvo com sucesso",
	'entity:delete:object:bookmarks:success' => "Marcador excluído.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Não foi possível salvar o seu marcador. Certifique-se de ter inserido um título e endereço e tente novamente.",
);
