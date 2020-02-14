<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'profile' => 'Profil',
	'profile:notfound' => 'Entschuldigung, wir konnten das gesuchte Profil nicht finden.',
	'profile:upgrade:2017040700:title' => 'Aktualisieren des Datenbank-Schemas der Profilfelder',
	'profile:upgrade:2017040700:description' => 'Dieses Upgrade konvertiert die Datenbankeinträge der Profilfelder von Metadata-Einträgen zu Annotations mit einem Präfix des Annotationnamens von "profile:". <strong>Anmerkung:</strong> Wenn Du derzeit "inaktive" Profilfelder hast, die Du auch konvertieren willst, erzeuge erst diese Felder erneut und lade dann diese Upgrade-Seite neu, damit diese Felder bei der Aktualisierung ebenfalls konvertiert werden.',
	
	'admin:configure_utilities:profile_fields' => 'Profilfelder bearbeiten',
	
	'profile:edit' => 'Profil bearbeiten',
	'profile:aboutme' => "Über mich",
	'profile:description' => "Über mich",
	'profile:briefdescription' => "Kurzbeschreibung",
	'profile:location' => "Heimatort",
	'profile:skills' => "Fähigkeiten",
	'profile:interests' => "Interessen",
	'profile:contactemail' => "Email-Kontaktadresse",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Handy",
	'profile:website' => "Webseite",
	'profile:twitter' => "Twitter-Benutzername",
	'profile:saved' => "Die Änderungen an Deinem Profil wurden gespeichert.",

	'profile:field:text' => 'Textfeld',
	'profile:field:longtext' => 'Grosses (HTML) Textfeld',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Webseite',
	'profile:field:email' => 'Email-Adresse',
	'profile:field:tel' => 'Telefonnummer',
	'profile:field:location' => 'Ort',
	'profile:field:date' => 'Datum',
	'profile:field:datetime-local' => 'Zeit und Datum',
	'profile:field:month' => 'Monat',
	'profile:field:week' => 'Woche',
	'profile:field:color' => 'Farbe',

	'profile:edit:default' => 'Anpassen der Profilfelder',
	'profile:label' => "Name des Profilfeldes",
	'profile:type' => "Feldtyp",
	'profile:editdefault:delete:fail' => 'Das Entfernen des Profilfeldes ist fehlgeschlagen.',
	'profile:editdefault:delete:success' => 'Das Profilfeld wurde gelöscht.',
	'profile:defaultprofile:reset' => 'Die Standard-Profilfelder wurden wiederhergestellt.',
	'profile:resetdefault' => 'Standard-Profilfelder wiederherstellen',
	'profile:resetdefault:confirm' => 'Bist Du sicher, dass Du Deine benutzerdefinierten Profilfelder löschen willst?',
	'profile:explainchangefields' => "Hier kannst Du die existierenden Profilfelder durch eigene Felder ersetzen. Zuerst mußt Du einen Namen für das neue Feld eingeben, z.B. 'Lieblingsteam'. Dann mußt Du den Typ des Feldes auswählen (z.B. Text, URL, Tags). Mit einem Klick auf 'Hinzufügen' wird das Feld dann in das Profil aufgenommen. Um die Reihenfolge der Felder zu ändern, kannst Du ein Feld am Greifer neben dem Namen in seine gewünschte Position ziehen. Um den Namen eines Feldes zu ändern, klicke auf den Text, um ihn editierbar zu machen. \n\n Du kannst jederzeit das ursprüngliche Standard-Profil wiederherstellen. Aber alle Informationen, die von Mitgliedern in die benutzerdefinierten Felder auf den Profilseiten eingegeben wurden, gehen dann verloren.",
	'profile:editdefault:success' => 'Das Profilfeld wurde hinzugefügt',
	'profile:editdefault:fail' => 'Die Änderung der Profilfelder konnte nicht gespeichert werden.',
	'profile:noaccess' => "Du hast keine ausreichende Berechtigung, um dieses Profil zu editieren.",
	'profile:invalid_email' => '%s muss eine gültige Email-Adresse sein.',
);
