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

	'messageboard:board' => "Mural de Recados",
	'messageboard:none' => "Ainda não há Mensagens neste Mural",
	'messageboard:num_display' => "Número de Mensagens a Exibir",
	'messageboard:owner' => 'Mural de Recados de %s',
	'messageboard:owner_history' => 'Publicações de %s no mural de %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s publicou no mural de %s",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Desculpe, não foi possível apagar esta Mensagem.",
	'annotation:delete:messageboard:success' => "Mensagem apagada com sucesso!",
	
	'messageboard:posted' => "Você publicou no Mural com sucesso!",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Você recebeu um novo comentário no Mural!',
	'messageboard:email:body' => "Você recebeu um novo comentário no Mural de %s.

O texto é:

%s

Para ver os comentários do seu Mural, clique aqui:
%s

Para ver o Perfil de %s, clique aqui:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Desculpe, você precisa escrever algo no Campo da Mensagem antes de salvar.",

	'messageboard:failure' => "Ocorreu um erro inesperado ao adicionar sua Mensagem. Por favor, tente novamente.",

	'widgets:messageboard:name' => "Mural de Recados",
	'widgets:messageboard:description' => "Este é um Mural de Mensagens que você pode colocar no seu Perfil para que outros Usuários possam comentar.",
);
