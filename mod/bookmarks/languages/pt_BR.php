<?php
return array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Favoritos",
	'bookmarks:add' => "Adicionar favorito",
	'bookmarks:edit' => "Editar favoritos",
	'bookmarks:owner' => "Favorito de %s",
	'bookmarks:friends' => "Favoritos dos amigos",
	'bookmarks:everyone' => "Todos os favoritos do site",
	'bookmarks:this' => "Adicionar aos favoritos",
	'bookmarks:this:group' => "Favoritos em %s",
	'bookmarks:bookmarklet' => "Obter Marcador de favoritos",
	'bookmarks:bookmarklet:group' => "Obter Marcador de Favoritos da Comunidade",
	'bookmarks:inbox' => "Caixa de entrada dos favoritos",
	'bookmarks:with' => "Compartilhar com",
	'bookmarks:new' => "Um novo item adicionado aos favoritos",
	'bookmarks:address' => "hiperlink a ser marcado como favorito",
	'bookmarks:none' => 'Sem favoritos',

	'bookmarks:notify:summary' => 'Novo favoritos chamado %s',
	'bookmarks:notify:subject' => 'Novo favorito: %s',
	'bookmarks:notify:body' =>
'%s adicionado como um novo favorito: %s

Endereço: %s

%s

Visualizado e comentado no favorito:
%s
',

	'bookmarks:delete:confirm' => "Você tem certeza de que deseja apagar este item?",

	'bookmarks:numbertodisplay' => 'Número de favoritos a serem exibidos',

	'bookmarks:shared' => "Compartilhados",
	'bookmarks:visit' => "Visitar o link",
	'bookmarks:recent' => "Adicionados recentemente",

	'river:create:object:bookmarks' => '%s adicionou como favorito %s',
	'river:comment:object:bookmarks' => '%s comentou no favorito %s',
	'bookmarks:river:annotate' => 'adicionado um comentário neste link marcado como favorito',
	'bookmarks:river:item' => 'um item',

	'item:object:bookmarks' => 'Links favoritos',

	'bookmarks:group' => 'Favoritos da comunidade',
	'bookmarks:enablebookmarks' => 'Habilita favoritos na comunidade',
	'bookmarks:nogroup' => 'Esta comunidade ainda não tem nenhum favorito',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Este dispositivo demonstra seus últimos itens favoritos.",

	'bookmarks:bookmarklet:description' =>
			"O marcador de favoritos permite que você compartilhe qualquer link que você encontrar na Internet com seus amigos, ou apenas marcá-lo como favorito para você mesmo. Para usar este recurso, apenas arraste o seguinte botão para a barra de links do seu navegador:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Se você está usando o Internet Explorer, você precisará clicar com o botão direito no ícone do marcador de favoritos, selecionar 'adicionar em favoritos', e então selecionar a barra de links.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Você poderá então salvar qualquer página que você visitar apenas clicando neste ícone a qualquer momento.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Este link foi adicionado aos favoritos com sucesso.",
	'bookmarks:delete:success' => "Este hiperlink favorito foi apagado com sucesso.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Este hiperlink favorito não pode ser salvo. Tenha certeza que você digitou titulo e endereço e então tente novamente.",
	'bookmarks:save:invalid' => "O endereço do favorito é inválido e não pode ser salvo.",
	'bookmarks:delete:failed' => "Este hiperlink favorito não pode ser apagado. Por favor, tente novamente.",
	'bookmarks:unknown_bookmark' => 'Não foi possível encontrar favorito específico',
);
