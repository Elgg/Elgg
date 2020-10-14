<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Discussies",
	
	'add:object:discussion' => 'Discussie toevoegen',
	'edit:object:discussion' => 'Bewerk discussie',
	'collection:object:discussion' => 'Discussies',
	'collection:object:discussion:group' => 'Groepsdiscussies',
	'collection:object:discussion:my_groups' => 'Discussies in mijn groepen',
	
	'discussion:settings:enable_global_discussions' => 'Schakel globale discussies in',
	'discussion:settings:enable_global_discussions:help' => 'Discussies kunnen ook buiten groepen worden gestart',

	'discussion:latest' => 'Laatste discussies',
	'discussion:none' => 'Geen discussies',
	'discussion:updated' => "Laatste reactie door %s: '%s'",

	'discussion:topic:created' => 'Het discussieonderwerp is aangemaakt.',
	'discussion:topic:updated' => 'De discussie is bijgewerkt.',
	'entity:delete:object:discussion:success' => 'De discussie is verwijderd.',

	'discussion:topic:notfound' => 'De discussie kan niet gevonden worden',
	'discussion:error:notsaved' => 'Fout tijdens het opslaan van deze discussie',
	'discussion:error:missing' => 'Zowel titel als bericht zijn verplichte velden',
	'discussion:error:permissions' => 'Je hebt onvoldoende rechten om deze actie uit te mogen voeren',
	'discussion:error:no_groups' => "Je hebt geen groepslidmaatschappen",

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
