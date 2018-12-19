<?php

return array(
	'add:object:discussion' => 'Discussie toevoegen',
	'edit:object:discussion' => 'Bewerk discussie',

	'discussion:latest' => 'Laatste discussies',
	'collection:object:discussion:group' => 'Groepsdiscussies',
	'discussion:none' => 'Geen discussies',
	'discussion:updated' => "Laatste reactie door %s: '%s'",

	'discussion:topic:created' => 'Het discussieonderwerp is aangemaakt.',
	'discussion:topic:updated' => 'De discussie is bijgewerkt.',
	'entity:delete:object:discussion:success' => 'De discussie is verwijderd.',

	'discussion:topic:notfound' => 'De discussie kan niet gevonden worden',
	'discussion:error:notsaved' => 'Fout tijdens het opslaan van deze discussie',
	'discussion:error:missing' => 'Zowel titel als bericht zijn verplichte velden',
	'discussion:error:permissions' => 'Je hebt onvoldoende rechten om deze actie uit te mogen voeren',

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s heeft een nieuwe discussie toegevoegd %s',
	'river:object:discussion:comment' => '%s reageerde op de discussie %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nieuwe discussie met de titel \'%s\'',
	'discussion:topic:notify:subject' => 'Nieuwe discussie: %s',
	'discussion:topic:notify:body' =>
'%s heeft een nieuwe discussie toegevoegd "%s":

%s

Bekijk en reageer hier op de discussie:
%s
',

	'discussion:comment:notify:summary' => 'Nieuwe reactie in discussie \'%s\'',
	'discussion:comment:notify:subject' => 'Nieuwe reactie in discussie \'%s\'',
	'discussion:comment:notify:body' =>
'%s reageerde op de discussie "%s":

%s

Bekijk en reageer hier op de discussie:
%s
',

	'item:object:discussion' => "Discussies",
	'collection:object:discussion' => 'Discussies',

	'groups:tool:forum' => 'Activeer groepsdiscussies',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status',
	'discussion:topic:closed:title' => 'Deze discussie is gesloten.',
	'discussion:topic:closed:desc' => 'Deze discussie is gesloten. Er kunnen geen reacties meer geplaatst worden.',

	'discussion:topic:description' => 'Onderwerp van je discussie',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Migreer discussie reacties naar reguliere reacties",
	'discussions:upgrade:2017112800:description' => "Discussie reacties waren eerder een apart soort reacties, maar dit is nu samengevoegd in de reguliere reacties.",
	'discussions:upgrade:2017112801:title' => "Migreer activiteitenstroom items mbt discussie reacties",
	'discussions:upgrade:2017112801:description' => "Discussie reacties waren eerder een apart soort reacties, maar dit is nu samengevoegd in de reguliere reacties.",
);
