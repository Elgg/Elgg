<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s heeft een vriendschapsverzoek verstuurd naar %s",
	'relationship:friendrequest:pending' => "%s wil je vriend worden",
	'relationship:friendrequest:sent' => "Je hebt een vriendschapsverzoek verstuurd naar %s",
	
	// plugin settings
	'friends:settings:request:description' => "Standaard kan iedere gebruiker een vriendschap aangeven met iedere andere gebruiker, dit is eigenlijk het volgen van de activiteit van de andere gebruiker.
Na het inschakelen van de vriendschapsverzoeken moet indien gebruiker A vriend wil worden met gebruiker B, gebruiker B dit goedkeuren. Na goedkeuring is gebruiker A een vriend van gebruiker B en gebruiker B een vriend van gebruiker A,",
	'friends:settings:request:label' => "Schakel vriendschapsverzoeken in",
	'friends:settings:request:help' => "Gebruikers moeten vriendschapsverzoeken goedkeuren en de vriendschap wordt bi-directioneel",
	
	'friends:owned' => "%s's vrienden",
	'friend:add' => "Voeg toe als vriend",
	'friend:remove' => "Verwijder vriend",
	'friends:menu:request:status:pending' => "Vriendschapsverzoek in afwachting",

	'friends:add:successful' => "%s is succesvol toegevoegd aan je vrienden",
	'friends:add:duplicate' => "Je bent reeds vrienden met %s",
	'friends:add:failure' => "Er is een fout opgetreden tijdens het toevoegen van %s als vriend",
	'friends:request:successful' => 'Er is een vriendschapsverzoek verzonden naar %s',
	'friends:request:error' => 'Er is een fout opgetreden tijdens het verwerken van je vriendschapsverzoek met %s',

	'friends:remove:successful' => "Je hebt %s succesvol verwijderd als vriend",
	'friends:remove:no_friend' => "Je bent geen vriend met %s",
	'friends:remove:failure' => "Er is een fout opgetreden tijdens het verwijderen van %s als vriend",

	'friends:none' => "Nog geen vrienden gevonden.",
	'friends:of:owned' => "Mensen wie %s hebben toegevoegd als vriend",

	'friends:of' => "Vrienden van",
	
	'friends:request:pending' => "Uitstaande vriendschapsverzoeken",
	'friends:request:pending:none' => "Er zijn geen uitstaande vriendschapsverzoeken gevonden",
	'friends:request:sent' => "Verzonden vriendschapsverzoeken",
	'friends:request:sent:none' => "Er zijn nog geen vriendschapsverzoeken verzonden",
	
	'friends:num_display' => "Het aantal vrienden om weer te geven",
	
	'widgets:friends:name' => "Vrienden",
	'widgets:friends:description' => "Toon een aantal van je vrienden",
	
	'widgets:friends_of:name' => "Vrienden van",
	'widgets:friends_of:description' => "Toon wie jou volgen",
	
	'friends:notification:request:subject' => "%s wil je vriend worden!",
	'friends:notification:request:message' => "%s heeft een vriendschapsverzoek ingediend op %s.

On het vriendschapsverzoek te bekijken, klik hier:
%s",
	
	'friends:notification:request:decline:subject' => "%s heeft je vriendschapsverzoek afgewezen",
	'friends:notification:request:decline:message' => "%s heeft je vriendschapsverzoek afgewezen",
	
	'friends:notification:request:accept:subject' => "%s heeft je vriendschapsverzoek geaccepteerd",
	'friends:notification:request:accept:message' => "%s heeft je vriendschapsverzoek geaccepteerd.",
	
	'friends:action:friendrequest:revoke:fail' => "Er is een fout opgetreden tijdens het intrekken van het vriendschapsverzoek, probeer het nogmaals",
	'friends:action:friendrequest:revoke:success' => "Het vriendschapsverzoek is ingetrokken",
	
	'friends:action:friendrequest:decline:fail' => "Er is een fout opgetreden tijdens het afwijzen van het vriendschapsverzoek, probeer het nogmaals",
	'friends:action:friendrequest:decline:success' => "Het vriendschapsverzoek is afgewezen",
	
	'friends:action:friendrequest:accept:success' => "Het vriendschapsverzoek is geaccepteerd",
	
	// notification settings
	'friends:notification:settings:description' => 'Standaard notificatie instellingen voor mensen die je gaat volgen',
);
