<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Bestanden',
	'item:object:file:application' => 'Applicatie',
	'item:object:file:archive' => 'Archief',
	'item:object:file:excel' => 'Excel',
	'item:object:file:image' => 'Afbeelding',
	'item:object:file:music' => 'Muziek',
	'item:object:file:openoffice' => 'OpenOffice',
	'item:object:file:pdf' => 'PDF',
	'item:object:file:ppt' => 'PowerPoint',
	'item:object:file:text' => 'Tekst',
	'item:object:file:vcard' => 'vCard',
	'item:object:file:video' => 'Video',
	'item:object:file:word' => 'Word',
	
	'file:upgrade:2022092801:title' => 'Verplaats bestanden',
	'file:upgrade:2022092801:description' => 'Verplaatst bestand die middels de file plugin zijn geüpload bij de eigenaar van de content naar de bestandslocatie van de entiteit zelf.',
	
	'collection:object:file' => 'Bestanden',
	'collection:object:file:all' => "Alle bestanden",
	'collection:object:file:owner' => "%s's bestanden",
	'collection:object:file:friends' => "Bestanden van vrienden",
	'collection:object:file:group' => "Groepsbestanden",
	'add:object:file' => "Upload een bestand",
	'edit:object:file' => "Bewerk bestand",
	'notification:object:file:create' => "Stuur een notificatie wanneer een bestand is geupload",
	'notifications:mute:object:file' => "over het bestand '%s'",

	'file:more' => "Meer bestanden",
	'file:list' => "lijstweergave",

	'file:num_files' => "Aantal bestanden om weer te geven",
	'file:replace' => 'Vervang bestandsinhoud (laat dit leeg om het bestand <em>niet</em> te vervangen)',
	'file:list:title' => "Van %s: %s en %s",

	'file:file' => "Bestand",

	'file:list:list' => 'Toon in lijstweergave',
	'file:list:gallery' => 'Toon in galerijweergave',

	'file:type:' => 'Bestanden',
	'file:type:all' => "Alle bestanden",
	'file:type:video' => "Video's",
	'file:type:document' => "Documenten",
	'file:type:audio' => "Geluidsbestanden",
	'file:type:image' => "Afbeeldingen",
	'file:type:general' => "Algemeen",

	'file:user:type:video' => "Video's van %s",
	'file:user:type:document' => "Documenten van %s",
	'file:user:type:audio' => "Geluidsbestanden van %s",
	'file:user:type:image' => "Afbeeldingen van %s",
	'file:user:type:general' => "Algemene bestanden van %s",

	'file:friends:type:video' => "Video's van je vrienden",
	'file:friends:type:document' => "Documenten van je vrienden",
	'file:friends:type:audio' => "Geluidsbestanden van je vrienden",
	'file:friends:type:image' => "Afbeeldingen van je vrienden",
	'file:friends:type:general' => "Algemene bestanden van je vrienden",

	'widgets:filerepo:name' => "Bestandenwidget",
	'widgets:filerepo:description' => "Laat je laatst geüploade bestanden zien",

	'groups:tool:file' => 'Schakel groepsbestanden in',
	'groups:tool:file:description' => 'Laat groepsleden bestanden delen in deze groep.',

	'river:object:file:create' => '%s heeft het bestand %s geüpload',
	'river:object:file:comment' => '%s reageerde op het bestand %s',

	'file:notify:summary' => 'Nieuw bestand met de titel %s',
	'file:notify:subject' => 'Nieuw bestand: %s',
	'file:notify:body' => '%s heeft een nieuw bestand geüpload: %s

%s

Om het bestand te bekijken en te reageren, klik hier:
%s',
	
	'notification:mentions:object:file:subject' => '%s heeft je vermeld bij een bestand',

	/**
	 * Status messages
	 */

	'file:saved' => "Het bestand is succesvol opgeslagen",
	'entity:delete:object:file:success' => "Het bestand is succesvol verwijderd",

	/**
	 * Error messages
	 */

	'file:none' => "We konden op dit moment geen bestanden vinden.",
	'file:uploadfailed' => "Sorry, we konden het bestand niet opslaan.",
	'file:noaccess' => "Je hebt onvoldoende rechten om dit bestand aan te passen",
	'file:cannotload' => "Er was een fout tijdens het uploaden van het bestand",
);
