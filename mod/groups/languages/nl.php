<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Groepen",
	'groups:owned' => "Groepen beheren",
	'groups:owned:user' => 'Groepen waar %s beheerder van is',
	'groups:yours' => "Mijn groepen",
	'groups:user' => "Groepen waarvan %s lid is",
	'groups:all' => "Alle groepen op de site",
	'groups:add' => "Maak een nieuwe groep",
	'groups:edit' => "Bewerk groep",
	'groups:delete' => 'Verwijder groep',
	'groups:membershiprequests' => 'Beheer van lidmaatschapsaanvragen',
	'groups:membershiprequests:pending' => 'Beheer van lidmaatschapsaanvragen (%s)',
	'groups:invitations' => 'Groepsuitnodigingen',
	'groups:invitations:pending' => 'Groepsuitnodigingen (%s)',

	'groups:icon' => 'Groepsicoon (laat leeg om het huidige icoon te behouden)',
	'groups:name' => 'Groepsnaam',
	'groups:description' => 'Omschrijving',
	'groups:briefdescription' => 'Korte omschrijving',
	'groups:interests' => 'Tags',
	'groups:website' => 'Website',
	'groups:members' => 'Groepsleden',

	'groups:members_count' => '%s leden',

	'groups:members:title' => 'Leden van %s',
	'groups:members:more' => "Bekijk alle leden",
	'groups:membership' => "Lidmaatschap",
	'groups:content_access_mode' => "Toegangsrechten voor de groepsinhoud",
	'groups:content_access_mode:warning' => "Let op! Als je deze instelling verandert heeft dat <em>geen</em> invloed op de toegangsrechten van de reeds bestaande groepsinhoud!",
	'groups:content_access_mode:unrestricted' => "Geen beperkingen. Toegang hangt af van de instelling van de content.",
	'groups:content_access_mode:membersonly' => "Alleen voor leden - Niet-leden kunnen nooit de inhoud van de groep zien.",
	'groups:access' => "Toegangsrechten",
	'groups:owner' => "Eigenaar",
	'groups:owner:warning' => "Opgelet: als je deze waarde aanpast ben je niet meer de eigenaar van deze groep",
	'groups:widget:num_display' => 'Aantal groepen om weer te geven',
	'widgets:a_users_groups:name' => 'Groepslidmaatschap',
	'widgets:a_users_groups:description' => 'Laat de groepen waarvan je lid bent zien op je profiel',

	'groups:noaccess' => 'Geen toegang tot de groep',
	'groups:cantcreate' => 'Alleen sitebeheerders mogen nieuwe groepen aanmaken.',
	'groups:cantedit' => 'Je kunt deze groep niet bewerken',
	'groups:saved' => 'Groep opgeslagen',
	'groups:save_error' => 'Groep kon niet worden opgeslagen',
	'groups:featured' => 'Aangeraden groepen',
	'groups:makeunfeatured' => 'Afraden',
	'groups:makefeatured' => 'Aanraden',
	'groups:featuredon' => '%s is nu een aangeraden groep',
	'groups:unfeatured' => '%s is verwijderd van de aangeraden groepen',
	'groups:featured_error' => 'Ongeldige groep.',
	'groups:nofeatured' => 'Geen aangeraden groepen',
	'groups:joinrequest' => 'Lidmaatschap van deze groep aanvragen',
	'groups:join' => 'Word lid van deze groep',
	'groups:leave' => 'Verlaat deze groep',
	'groups:invite' => 'Nodig vrienden uit',
	'groups:invite:title' => 'Nodig vrienden uit in deze groep',
	'groups:invite:friends:help' => 'Zoek naar een vriend op naam of gebruikersnaam en selecteer de vriend uit de lijst',
	'groups:invite:resend' => 'Verstuur de uitnodigingen opnieuw voor de al uitgenodigde gebruikers',

	'groups:nofriendsatall' => 'Je hebt geen vrienden om uit te nodigen!',
	'groups:group' => "Groep",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Zoek naar groepen met het trefwoord '%s'",
	'groups:search:none' => "Geen overeenkomende groepen gevonden",
	'groups:search_in_group' => "Zoek in deze groep",
	'groups:acl' => "Groep: %s",
	'groups:acl:in_context' => 'Groepsleden',

	'groups:notfound' => "Groep niet gevonden",
	
	'groups:requests:none' => 'Er zijn  op dit moment <strong>geen</strong> openstaande lidmaatschapsaanvragen.',

	'groups:invitations:none' => 'Er zijn op dit moment <strong>geen</strong> openstaande uitnodigingen.',

	'groups:open' => "open groep",
	'groups:closed' => "besloten groep",
	'groups:member' => "leden",
	'groups:search' => "Zoek naar groepen",

	'groups:more' => 'Meer groepen',
	'groups:none' => 'Geen groepen',

	/**
	 * Access
	 */
	'groups:access:private' => 'Gesloten: gebruikers moeten lidmaatschap aanvragen',
	'groups:access:public' => 'Open: iedere gebruiker kan lid worden',
	'groups:access:group' => 'Alleen voor groepsleden',
	'groups:closedgroup' => "Deze groep is besloten.",
	'groups:closedgroup:request' => 'Om lid te worden klik je op de link "Lidmaatschap van deze groep aanvragen". Deze link vind je in het menu.',
	'groups:closedgroup:membersonly' => "Het lidmaatschap van deze groep is gesloten en de inhoud is enkel toegankelijk door leden.",
	'groups:opengroup:membersonly' => "De inhoud van deze groep is alleen toegankelijk voor leden.",
	'groups:opengroup:membersonly:join' => 'Om lid te worden klik je op de "Word lid" link in het menu.',
	'groups:visibility' => 'Wie kan deze groep zien?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Groepen',

	'groups:notitle' => 'Groepen moeten een titel hebben',
	'groups:cantjoin' => 'Kan niet lid worden van de groep',
	'groups:cantleave' => 'Kon de groep niet verlaten',
	'groups:removeuser' => 'Verwijder uit groep',
	'groups:cantremove' => 'Kan de gebruiker niet uit de groep verwijderen',
	'groups:removed' => '%s succesvol verwijderd uit de groep',
	'groups:addedtogroup' => 'Gebruiker succesvol toegevoegd aan de groep',
	'groups:joinrequestnotmade' => 'Lidmaatschapsverzoek kon niet worden gedaan',
	'groups:joinrequestmade' => 'Lidmaatschapsverzoek succesvol gedaan',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Lid',
	'groups:button:owned' => 'Eigenaar',
	'groups:joined' => 'Je bent lid geworden van de groep!',
	'groups:left' => 'De groep succesvol verlaten',
	'groups:userinvited' => 'Gebruiker is uitgenodigd.',
	'groups:usernotinvited' => 'Gebruiker kon niet worden uitgenodigd.',
	'groups:useralreadyinvited' => 'Gebruiker is al uitgenodigd',
	'groups:invite:subject' => "%s je bent uitgenodigd om lid te worden van %s!",
	'groups:joinrequest:remove:check' => 'Weet je zeker dat je dit lidmaatschapsverzoek wilt verwijderen?',
	'groups:invite:remove:check' => 'Weet je zeker dat je deze uitnodiging wilt verwijderen?',
	'groups:invite:body' => "Beste %s,

%s heeft je uitgenodigd om lid te worden van de groep '%s'.

On al je uitnodigingen te bekijken, klik hier:
%s",

	'groups:welcome:subject' => "Welkom bij de groep '%s'!",
	'groups:welcome:body' => "Beste %s,

Je bent nu lid van de groep '%s'.

Je kunt direct beginnen in de groep!
%s",

	'groups:request:subject' => "%s wil lid worden van %s",
	'groups:request:body' => "Beste %s,

%s wil graag lid worden van de groep '%s'.

Om het profiel te bekijken, klik hier:
%s

of klik op de link om alle lidmaatschapsverzoeken te bekijken:
%s",

	'river:group:create' => '%s heeft de groep %s aangemaakt',
	'river:group:join' => '%s is lid geworden van de groep %s',

	'groups:allowhiddengroups' => 'Wil je privégroepen toestaan? Deze zijn onzichtbaar voor wie er geen toegang toe heeft.',
	'groups:whocancreate' => 'Wie mag of mogen nieuwe groepen aanmaken?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'De groep en zijn content zijn verwijderd',
	'groups:notdeleted' => 'De groep kon niet worden verwijderd',
	'groups:deletewarning' => "Weet je zeker dat je deze groep wilt verwijderen? Dit kan niet ongedaan worden gemaakt!",

	'groups:invitekilled' => 'De uitnodiging is verwijderd',
	'groups:joinrequestkilled' => 'Lidmaatschapsverzoek verwijderd.',
	'groups:error:addedtogroup' => "Kon %s niet toevoegen aan de groep",
	'groups:add:alreadymember' => "%s is al lid van deze groep",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Groepsprofielen',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Verplaats de groepsiconen naar de nieuwe locatie',
	'groups:upgrade:2016101900:description' => 'De nieuwe iconen API plaatst de bestanden op een voorspelbare locatie op de bestandslocatie relatief aan de entity\'s bestandlocatie.
Deze upgrade verplaatst alle groepsiconen naar deze nieuwe locatie.',
);
