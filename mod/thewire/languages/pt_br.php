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
	'thewire' => "Recados",

	'item:object:thewire' => "Recados",
	'collection:object:thewire' => 'Recados',
	'collection:object:thewire:all' => "Todos os Recados",
	'collection:object:thewire:owner' => "Recados de %s",
	'collection:object:thewire:friends' => "Recados dos Amigos",
	'collection:object:thewire:mentions' => "Recados mencionando @%s",
	'notification:object:thewire:create' => "Enviar uma Notificação quando um Recado for Publicado",
	'notifications:mute:object:thewire' => "sobre o recado '%s'",
	
	'entity:edit:object:thewire:success' => 'Recado salvo com sucesso!',

	'thewire:menu:filter:mentions' => "Menções",
	
	'thewire:replying' => "Respondendo a %s (@%s), que escreveu",
	'thewire:thread' => "Conversas",
	'thewire:charleft' => "caracteres restantes",
	'thewire:tags' => "Recados marcados com '%s'",
	'thewire:noposts' => "Nenhum Recado ainda...",

	'thewire:by' => 'Recado de %s',

	'thewire:form:body:placeholder' => "Novidades?",
	
	/**
	 * The wire river
	 */
	'river:object:thewire:create' => "%s publicou em %s",
	'thewire:wire' => 'Recados',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'Exibe seus Recados Mais Recentes',
	'thewire:num' => 'Número de Recados para Exibir',
	'thewire:moreposts' => 'Mais Recados',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Seu Recado foi publicado com sucesso!",
	'thewire:blank' => "Desculpe, você precisa escrever algo antes de enviar o Recado.",
	'thewire:notsaved' => "Desculpe. Não foi possível salvar este Recado.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'Novo recado: %s',
	'thewire:notify:subject' => "Novo Recado de %s",
	'thewire:notify:reply' => '%s respondeu a %s nos Recados:',
	'thewire:notify:post' => '%s publicou um Recado:',
	'thewire:notify:footer' => "Visualize e responda:\n%s",
	
	'notification:mentions:object:thewire:subject' => '%s mencionou você em um Recado',

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Número máximo de caracteres para Recados:",
	'thewire:settings:limit:none' => "Sem limites",
	
	/**
	 * Exceptions
	 */
	'ValidationException:thewire:limit' => "O comprimento do Recado ultrapassa o limite definido.",
);
