<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'profile' => 'Profil',
	'profile:notfound' => 'Tyvärr, vi kunde inte hitta den efterfrågade profilen.',
	'profile:upgrade:2017040700:title' => 'Migrera schema för profilfält',
	'profile:upgrade:2017040700:description' => 'Den här migrationen konverterar profilfält från metadata till anteckningar med prefixet "profile:" för varje namn. <strong>Notera:</strong> Om du har "inaktiva" profilfält som du vill migrera, återskapa de fälten och ladda om den här sidan för att vara säker på att de migreras.',
	
	'admin:configure_utilities:profile_fields' => 'Redigera Profiilfält',
	
	'profile:edit' => 'Redigera profil',
	'profile:aboutme' => "Om mig",
	'profile:description' => "Om mig",
	'profile:briefdescription' => "Kort beskrivning",
	'profile:location' => "Plats",
	'profile:skills' => "Kompetens",
	'profile:interests' => "Intressen",
	'profile:contactemail' => "E-post för kontakt",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Mobiltelefon",
	'profile:website' => "Webbplats",
	'profile:twitter' => "Twitter användarnamn",
	'profile:saved' => "Din profil sparades.",

	'profile:field:text' => 'Kort text',
	'profile:field:longtext' => 'Stort textområde',
	'profile:field:tags' => 'Taggar',
	'profile:field:url' => 'Webbadress',
	'profile:field:email' => 'E-postadress',
	'profile:field:tel' => 'Telefon',
	'profile:field:location' => 'Plats',
	'profile:field:date' => 'Datum',
	'profile:field:datetime-local' => 'Datum Tid',
	'profile:field:month' => 'Månad',
	'profile:field:week' => 'Vecka',
	'profile:field:color' => 'Färg',

	'profile:edit:default' => 'Redigera profilfält',
	'profile:label' => "Profiletikett",
	'profile:type' => "Profiltyp",
	'profile:editdefault:delete:fail' => 'Borttagandet av profilfält misslyckades',
	'profile:editdefault:delete:success' => 'Profilfält togs bort',
	'profile:defaultprofile:reset' => 'Återställning av profilfält till systemstandard',
	'profile:resetdefault' => 'Återställ profilfält till systemstandard',
	'profile:resetdefault:confirm' => 'Är du säker på att du vill ta bort dina anpassade profilfält?',
	'profile:explainchangefields' => "Du kan ersätta de existerande profilfälten med dina egna.
Tryck på knappen \"Lägg till\" och ge det nya profilfältet en etikett, till exempel, 'Favoritteam', välj sedan fältets typ (eg. text, url, taggar).
För att ordna fälten, dra i handtaget brevid fältetiketten.
För att redigera ett fält - tryck på redigeringsikonen.

Du kan när som helst återgå till standardinställningen för profiler, men du kommer förlora all information som redan finns i de anpassade fälte på profilsidor.",
	'profile:editdefault:success' => 'Nytt profilfält lades till',
	'profile:editdefault:fail' => 'Standardprofil kunde inte sparas',
	'profile:noaccess' => "Du har inte behörighet att redigera den här profilen.",
	'profile:invalid_email' => '%s måste vara en giltig e-postadress.',
);
