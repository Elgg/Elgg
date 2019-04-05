<?php
return array(

	/**
	 * Menu items and titles
	 */
	'item:object:bookmarks' => 'Segnalibri',
	'collection:object:bookmarks' => 'Bookmarks',
	'collection:object:bookmarks:group' => 'Group bookmarks',
	'collection:object:bookmarks:all' => "All site bookmarks",
	'collection:object:bookmarks:owner' => "%s's bookmarks",
	'collection:object:bookmarks:friends' => "Friends' bookmarks",
	'add:object:bookmarks' => "Add a bookmark",
	'edit:object:bookmarks' => "Edit bookmark",

	'bookmarks:this' => "Aggiungi questa pagina ai segnalibri",
	'bookmarks:this:group' => "Segnalibro in %s",
	'bookmarks:bookmarklet' => "Ottieni il Bookmarklet",
	'bookmarks:bookmarklet:group' => "Ottieni il Bookmarklet di gruppo",
	'bookmarks:address' => "Indirizzo del segnalibro",
	'bookmarks:none' => 'Nessun segnalibro',

	'bookmarks:notify:summary' => 'Nuovo segnalibro chiamato %s',
	'bookmarks:notify:subject' => 'Nuovo segnalibro: %s',
	'bookmarks:notify:body' =>
'%s added a new bookmark: %s

Address: %s

%s

View and comment on the bookmark:
%s
',

	'bookmarks:numbertodisplay' => 'Numero di segnalibri da visualizzare',

	'river:object:bookmarks:create' => '%s ha aggiunto il segnalibro %s',
	'river:object:bookmarks:comment' => '%s ha commentato il segnalibro %s',

	'groups:tool:bookmarks' => 'Enable group bookmarks',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Bookmarks',
	'widgets:bookmarks:description' => "Display your latest bookmarks.",

	'bookmarks:bookmarklet:description' =>
			"A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks. To set it up, drag the button below to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Se stai usando Internet Explorer, bisogna cliccare col tasto destro sull'icona del Bookmarklet, selezionare ''aggiungi ai preferiti'' e poi scegliere la barra dei collegamenti.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Dopo sarà possibile aggiungere velocemente ai preferiti qualsiasi pagina visitata cliccando nel browser questo pulsate .",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "L'elemento è stato aggiunto ai segnalibri.",
	'entity:delete:object:bookmarks:success' => "The bookmark was deleted.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Il segnalibro non può essere salvato. Assicurasi di aver inserito un titolo e un indirizzo, quindi riprovare.",
	'bookmarks:unknown_bookmark' => 'Impossibile trovare il segnalibro specificato.',
);
