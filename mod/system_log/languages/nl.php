<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:administer_utilities:logbrowser' => 'Log browser',
	'logbrowser:search' => 'Verfijn de resultaten',
	'logbrowser:user' => 'Gebruikersnaam om op te zoeken',
	'logbrowser:starttime' => 'Starttijd (bijvoorbeeld  "last monday", "1 hour ago")',
	'logbrowser:endtime' => 'Eindtijd',

	'logbrowser:explore' => 'Log verkennen',

	'logbrowser:date' => 'Datum en tijd',
	'logbrowser:ip_address' => 'IP Adres',
	'logbrowser:user:name' => 'Gebruiker',
	'logbrowser:user:guid' => 'Gebruikers GUID',
	'logbrowser:object' => 'Content Type',
	'logbrowser:object:id' => 'Object ID',
	'logbrowser:action' => 'Actie',

	'logrotate:period' => 'Hoe vaak moet het systeem log worden gearchiveerd?',
	'logrotate:retention' => 'Verwijder gearchiveerde logs na x dagen',
	'logrotate:retention:help' => 'Het aantal dagen dat de gearchiveerde logs in de database behouden moeten blijven. Laat dit leeg om de gearchiveerde logs niet op te schonen.',

	'logrotate:logrotated' => "Log gearchiveerd",
	'logrotate:lognotrotated' => "Fout tijdens het archiveren van het log",

	'logrotate:logdeleted' => "Log verwijderd",
	'logrotate:lognotdeleted' => "Er zijn geen logs verwijderd",

	// not used any more since Elgg 4.1, can be cleaned in Elgg 5.0
	'logrotate:delete' => 'Verwijder gearchiveerde logs ouder dan één',
	'logrotate:week' => 'week',
	'logrotate:month' => 'maand',
	'logrotate:year' => 'jaar',
);
