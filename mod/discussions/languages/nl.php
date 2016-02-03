<?php

return array(
	'discussion' => 'Discussies',
	'discussion:add' => 'Nieuw discussieonderwerp',
	'discussion:latest' => 'Laatste discussies',
	'discussion:group' => 'Groepsdiscussies',
	'discussion:none' => 'Geen discussies',
	'discussion:reply:title' => 'Reactie door %s',
	'discussion:new' => "Nieuwe discussie",
	'discussion:updated' => "Laatste reactie door %s: '%s'",

	'discussion:topic:created' => 'Het discussieonderwerp is aangemaakt.',
	'discussion:topic:updated' => 'Het discussieonderwerp is bijgewerkt.',
	'discussion:topic:deleted' => 'Het discussieonderwerp is verwijderd.',

	'discussion:topic:notfound' => 'Het discussieonderwerp kan niet gevonden worden',
	'discussion:error:notsaved' => 'Fout tijdens het opslaan van dit onderwerp',
	'discussion:error:missing' => 'Zowel titel als bericht zijn verplichte velden',
	'discussion:error:permissions' => 'Je hebt onvoldoende rechten om deze actie uit te mogen voeren',
	'discussion:error:notdeleted' => 'Het discussieonderwerp kon niet worden verwijderd',

	'discussion:reply:edit' => 'Bewerk reactie',
	'discussion:reply:deleted' => 'Reactie op de discussie is verwijderd.',
	'discussion:reply:error:notfound' => 'Reactie op discussie kon niet gevonden worden',
	'discussion:reply:error:notfound_fallback' => "Helaas, we kunnen de specifieke reactie niet terug vinden, maar we hebben je doorgestuurd naar de originele discussie.",
	'discussion:reply:error:notdeleted' => 'De reactie op de discussie kon niet worden verwijderd',

	'discussion:search:title' => 'Reactie op onderwerp: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'Je kunt geen lege reactie plaatsen',
	'discussion:reply:topic_not_found' => 'Het discussieonderwerp kan niet gevonden worden',
	'discussion:reply:error:cannot_edit' => 'U beschikt niet over de juiste rechten om deze reactie te bewerken',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s begon een nieuwe discussie:  \'%s\'',
	'river:reply:object:discussion' => '%s reageerde op de discussie \'%s\'',
	'river:reply:view' => 'Lees de reactie',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nieuw discussieonderwerp met de titel \'%s\'',
	'discussion:topic:notify:subject' => 'Nieuw discussieonderwerp: %s',
	'discussion:topic:notify:body' =>
'Hallo!

%s heeft een nieuw discussie gestart "%s":

%s

Je kunt de discussie hier lezen en er op reageren:
%s
',

	'discussion:reply:notify:summary' => 'Nieuwe reactie in discussie \'%s\'',
	'discussion:reply:notify:subject' => 'Nieuwe reactie in discussie \'%s\'',
	'discussion:reply:notify:body' =>
'Hallo!

%s reageerde op de discussie "%s": 

%s

Je kunt de discussie hier bekijken en er op reageren:
%s
',

	'item:object:discussion' => "Discussies",
	'item:object:discussion_reply' => "Reacties op discussies",

	'groups:enableforum' => 'Activeer groepsdiscussies',

	'reply:this' => 'Reageer hierop',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Groepsdiscussies',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Onderwerpstatus',
	'discussion:topic:closed:title' => 'Deze discussie is gesloten.',
	'discussion:topic:closed:desc' => 'Deze discussie is gesloten. Er kunnen geen reacties meer geplaatst worden.',

	'discussion:replies' => 'Reacties',
	'discussion:addtopic' => 'Nieuwe discussie',
	'discussion:post:success' => 'Je reactie is succesvol geplaatst',
	'discussion:post:failure' => 'Er was een probleem bij het toevoegen van je reactie',
	'discussion:topic:edit' => 'Bewerk discussie',
	'discussion:topic:description' => 'Onderwerp van je discussie',

	'discussion:reply:edited' => "Je hebt de reactie succesvol bewerkt.",
	'discussion:reply:error' => "Er was een probleem tijdens het bewerken van de reactie.",
);
