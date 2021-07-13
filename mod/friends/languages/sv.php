<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s skickade en vänförfrågan till %s",
	'relationship:friendrequest:pending' => "%s vill bli din vän",
	'relationship:friendrequest:sent' => "Du skickade en vänförfrågan till %s",
	
	// plugin settings
	'friends:settings:request:label' => "Aktivera vänförfrågningar",
	'friends:settings:request:help' => "Användare måste godkänna en vänförfrågan och vänskap blir dubbelriktad",
	
	'friends:owned' => "%ss vänner",
	'friend:add' => "Lägg till vän",
	'friend:remove' => "Ta bort vän",

	'friends:add:successful' => "Du har lagt till %s som en vän.",
	'friends:add:duplicate' => "Du är redan vän med %s",
	'friends:add:failure' => "Vi kunde inte lägga till %s som en vän.",
	'friends:request:successful' => 'En vänförfrågan har skickats till %s',
	'friends:request:error' => 'Ett fel inträffade när din vänförfrågan till %s behandlades',

	'friends:remove:successful' => "Du har tagit bort %s från dina vänner.",
	'friends:remove:no_friend' => "Du och %s är inte vänner",
	'friends:remove:failure' => "Vi kunde inte ta bort %s från dina vänner.",

	'friends:none' => "Inga vänner än.",
	'friends:of:owned' => "Medlemmar som har %s som vän",

	'friends:of' => "Vänner för",
	
	'friends:request:pending' => "Väntande vänförfrågningar",
	'friends:request:pending:none' => "Inga väntande vänförfrågningar hittades.",
	'friends:request:sent' => "Skickade vänförfrågningar",
	'friends:request:sent:none' => "Inga vänförfrågningar har skickats.",
	
	'friends:num_display' => "Antalet vänner att visas",
	
	'widgets:friends:name' => "Vänner",
	'widgets:friends:description' => "Visas några av dina vänner.",
	
	'friends:notification:request:subject' => "%s vill bli din vän!",
	
	'friends:notification:request:decline:subject' => "%s har tackat nej till din vänförfrågan",
	
	'friends:notification:request:accept:subject' => "%s har accepterat din vänförfrågan",
	
	'friends:action:friendrequest:revoke:fail' => "Ett fel uppstod när vänförfrågan återkallades, vänligen försök igen",
	'friends:action:friendrequest:revoke:success' => "Vänförfrågan har återkallats",
	
	'friends:action:friendrequest:decline:fail' => "Ett fel uppstod när vänförfrågan nekades, vänligen försök igen",
	'friends:action:friendrequest:decline:success' => "Vänförfrågan har nekats",
	
	'friends:action:friendrequest:accept:success' => "Vänförfrågan har godkänts",
	
	// notification settings
);
