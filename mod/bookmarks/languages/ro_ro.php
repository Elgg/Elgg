<?php
return [

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Marcaje",
	'bookmarks:add' => "Adaugă un marcaj",
	'bookmarks:edit' => "Editează marcajul",
	'bookmarks:owner' => "Marcajele lui %s",
	'bookmarks:friends' => "Marcajele prietenilor",
	'bookmarks:everyone' => "Toate marcajele siteului",
	'bookmarks:this' => "Marchează această pagină",
	'bookmarks:this:group' => "Marchează în %s",
	'bookmarks:bookmarklet' => "Folosește marcatorul",
	'bookmarks:bookmarklet:group' => "Folosește marcatorul de grup",
	'bookmarks:inbox' => "Intrări de marcaje",
	'bookmarks:address' => "Adresa marcajului",
	'bookmarks:none' => 'Nu există marcaje',

	'bookmarks:notify:summary' => 'Marcaj nou numit %s',
	'bookmarks:notify:subject' => 'Marcaj nou: %s',
	'bookmarks:notify:body' =>
'%s a adăugat un marcaj nou: %s

Adresă: %s

%s

Vezi și comentează marcajul:
%s
',

	'bookmarks:delete:confirm' => "Sigur dorești să ștergi această resursă?",

	'bookmarks:numbertodisplay' => 'Numărul de marcaje de afișat',

	'river:create:object:bookmarks' => '%s a marcat %s',
	'river:comment:object:bookmarks' => '%s a adăugat un comentariu la un marcaj %s',
	'bookmarks:river:annotate' => 'un comentariu la acest marcaj',
	'bookmarks:river:item' => 'un element',

	'item:object:bookmarks' => 'Marcaje',

	'bookmarks:group' => 'Marcaje de grup',
	'bookmarks:enablebookmarks' => 'Activează marcajele de grup',
	'bookmarks:nogroup' => 'Acest grup nu are niciun marcaj încă',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Afișează-ți ultimele marcaje.",

	'bookmarks:bookmarklet:description' =>
			"A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks. To set it up, drag the button below to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Dacă folosești Internet Explorer, va trebui să dai clic dreapta pe butonul de marcat, selectează 'adaugă la favorite', și apoi bara de favorite.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Vei putea apoi să marchezi orice pagină pe care o vizitezi dând clic pe butonul de marcat din bara de favorite a navigatorului tău.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Elementul tău a fost marcat cu succes.",
	'bookmarks:delete:success' => "Marcajul tău a fost șters.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Marcajul tău nu a putut fi salvat. Fi sigur că ai introdus un titlu și o adresă apoi reîncearcă.",
	'bookmarks:save:invalid' => "Adresa marcajului este invalidă și nu s-a putut salva.",
	'bookmarks:delete:failed' => "Marcajul tău nu s-a putut șterge. Te rugăm să reîncerci.",
	'bookmarks:unknown_bookmark' => 'Nu s-a putut găsi marcajul specificat',
];
