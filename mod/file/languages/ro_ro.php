<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Fișier',
	'collection:object:file' => 'Fișiere',
	'collection:object:file:all' => "Toate fișierele site-ului",
	'collection:object:file:owner' => "Fișierele membrului %s",
	'collection:object:file:friends' => "Fișierele prietenilor",
	'collection:object:file:group' => "Fișiere de grup",
	'add:object:file' => "Încarcă un fișier",
	'edit:object:file' => "Editează fișierul",
	'notification:object:file:create' => "Trimite o notificare atunci când un fișier este creat",
	'notifications:mute:object:file' => "despre fișierul '%s'",

	'file:more' => "Mai multe fișiere",
	'file:list' => "vizualizare în listă",

	'file:num_files' => "Numărul de fișiere pentru afișare",
	'file:replace' => 'Înlocuiește conținutul fișierului (lasă gol pentru a nu schimba fișierul)',
	'file:list:title' => "%s %s %s",

	'file:file' => "Fișier",

	'file:list:list' => 'Comută la vizualizarea în listă',
	'file:list:gallery' => 'Comută la vizualizarea în galerie',

	'file:type:' => 'Fișiere',
	'file:type:all' => "Toate fișierele",
	'file:type:video' => "Videoclipuri",
	'file:type:document' => "Documente",
	'file:type:audio' => "Audio",
	'file:type:image' => "Poze",
	'file:type:general' => "General",

	'file:user:type:video' => "Videoclipuri de %s",
	'file:user:type:document' => "Documente de %s",
	'file:user:type:audio' => "Audio de %s",
	'file:user:type:image' => "Poze de %s",
	'file:user:type:general' => "Fișiere generale de %s",

	'file:friends:type:video' => "Videoclipurile prietenilor tăi",
	'file:friends:type:document' => "Documentele prietenilor tăi",
	'file:friends:type:audio' => "Fișierele audio ale prietenilor tăi",
	'file:friends:type:image' => "Pozele prietenilor tăi",
	'file:friends:type:general' => "Fișierele generale ale prietenilor tăi",

	'widgets:filerepo:name' => "Modul fișier",
	'widgets:filerepo:description' => "Afișează-ți fișierele recente",

	'groups:tool:file' => 'Activează fișierele de grup',

	'river:object:file:create' => '%s a încărcat fișierul %s',
	'river:object:file:comment' => '%s a comentat la fișierul %s',

	'file:notify:summary' => 'Fișier nou numit %s',
	'file:notify:subject' => 'Fișier nou: %s',
	'file:notify:body' => '%s a încărcat un fișier nou: %s

%s

Vezi și comentează fișierul:
%s',

	/**
	 * Status messages
	 */

	'file:saved' => "Fișierul a fost salvat cu succes.",
	'entity:delete:object:file:success' => "Fișierul a fost șters cu succes.",

	/**
	 * Error messages
	 */

	'file:none' => "Nu sunt fișiere.",
	'file:uploadfailed' => "Scuze; nu ți-am putut salva fișierul.",
	'file:noaccess' => "Nu ai permisiunea de a schimba acest fișier",
	'file:cannotload' => "A apărut o eroare la încărcarea fișierului",
);
