<?php
return [

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Marcadores",
	'bookmarks:add' => "Engadir un marcador",
	'bookmarks:edit' => "Editar o marcador",
	'bookmarks:owner' => "Marcadores de %s",
	'bookmarks:friends' => "Marcadores dos contactos",
	'bookmarks:everyone' => "Marcadores do sitio",
	'bookmarks:this' => "Marcar a páxina",
	'bookmarks:this:group' => "Marcar en %s",
	'bookmarks:bookmarklet' => "Obter o miniaplicativo ligado",
	'bookmarks:bookmarklet:group' => "Obter o miniaplicativo ligado do grupo",
	'bookmarks:inbox' => "Caixa de entrada dos marcadores",
	'bookmarks:address' => "Enderezo do marcador",
	'bookmarks:none' => 'Non hai marcadores',

	'bookmarks:notify:summary' => 'Novo marcador: «%s».',
	'bookmarks:notify:subject' => 'Novo marcador: %s',
	'bookmarks:notify:body' =>
'%s engadiu un novo marcador: %s

Enderezo: %s

%s

Vexa e deixe un comentario no marcador:
%s
',

	'bookmarks:delete:confirm' => "Está seguro de que quere eliminar este recurso?",

	'bookmarks:numbertodisplay' => 'Número de marcadores para mostrar',

	'river:create:object:bookmarks' => '%s engadiu %s aos seus marcadores',
	'river:comment:object:bookmarks' => '%s deixou un comentario nun marcador %s',
	'bookmarks:river:annotate' => 'un comentario neste marcador',
	'bookmarks:river:item' => 'un elemento',

	'item:object:bookmarks' => 'Marcadores',

	'bookmarks:group' => 'Marcadores do grupo',
	'bookmarks:enablebookmarks' => 'Activar os marcadores do grupo',
	'bookmarks:nogroup' => 'O grupo aínda non ten ningún marcador',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Mostrar os seus últimos marcadores.",

	'bookmarks:bookmarklet:description' =>
			"A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks. To set it up, drag the button below to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Se está a usar Internet Explorer, terá que premer a icona do miniaplicativo ligado co botón secundario, seleccionar «Engadir aos favoritos», e entón seleccionar a barra de ligazóns.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"A partir de entón pode marcar calquera páxina que visite. Só ten que premer o botón no navegador en calquera momento",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Marcouse o elemento",
	'bookmarks:delete:success' => "Eliminouse o marcador.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Non foi posíbel gardar o marcador. Asegúrese de que os campos do título e do enderezo non están baleiros, e inténteo de novo",
	'bookmarks:save:invalid' => "O enderezo do marcador non é válido e non foi posíbel gardalo.",
	'bookmarks:delete:failed' => "Non foi posíbel eliminar o marcador. Inténteo de novo",
	'bookmarks:unknown_bookmark' => 'Non é posíbel atopar o marcador indicado',
];
