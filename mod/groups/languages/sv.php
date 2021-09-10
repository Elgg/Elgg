<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	
	'groups' => "Grupper",
	'groups:owned' => "Grupper jag äger",
	'groups:owned:user' => 'Grupper %s äger',
	'groups:yours' => "Mina grupper",
	'groups:user' => "%ss grupper",
	'groups:all' => "Alla grupper",
	'groups:add' => "Skapa en ny grupp",
	'groups:edit' => "Redigera grupp",
	'groups:membershiprequests' => 'Hantera begäranden att gå med',
	'groups:membershiprequests:pending' => 'Hantera begäranden att gå med (%s)',
	'groups:invitedmembers' => "Hantera inbjudningar",
	'groups:invitations' => 'Gruppinbjudningar',
	'groups:invitations:pending' => 'Gruppinbjudningar (%s)',
	
	'relationship:invited' => '%2$s blev inbjuden att gå med i %1$s',
	'relationship:membership_request' => '%s begärde att gå med i %s',

	'groups:icon' => 'Gruppikon (lämna tomt för att inte gör någon ändring)',
	'groups:name' => 'Gruppnamn',
	'groups:description' => 'Beskrivning',
	'groups:briefdescription' => 'Kort beskrivning',
	'groups:interests' => 'Taggar',
	'groups:website' => 'Webbplats',
	'groups:members' => 'Gruppmedlemmar',

	'groups:members_count' => '%s medlemmar',

	'groups:members:title' => 'Medlemmar i %s',
	'groups:members:more' => "Visa alla medlemmar",
	'groups:membership' => "Behörigheter för medlemsskap i grupp",
	'groups:content_access_mode' => "Tillgänglighet för innehåll i grupp",
	'groups:content_access_mode:warning' => "Varning: Ändring av dessa inställningar kommer inte ändra åtkomstbehörigheten för existerande innehåll i grupp.",
	'groups:content_access_mode:unrestricted' => "Obegränsat - Åtkomst beror på inställningar på innehållsnivå.",
	'groups:content_access_mode:membersonly' => "Endast medlemmar - Icke medlemmar kan aldrig komma åt gruppens innehåll",
	'groups:access' => "Åtkomstbehörigheter",
	'groups:owner' => "Ägare",
	'groups:owner:warning' => "Varning: Om du ändrar det här värdet, kommer du inte längre vara ägaren av den här gruppen.",
	'groups:widget:num_display' => 'Antalet grupper att visa',
	'widgets:a_users_groups:name' => 'Medlemskap i grupp',
	'widgets:a_users_groups:description' => 'Visa grupperna du är medlem i på din profil',

	'groups:noaccess' => 'Inget åtkomst till grupp',
	'groups:cantcreate' => 'Du kan inte skapa en grupp- Bara admins kan göra det. ',
	'groups:cantedit' => 'Du kan inte redigera den här gruppen',
	'groups:saved' => 'Gruppen sparad',
	'groups:save_error' => 'Grupp kunde inte sparas',
	'groups:featured' => 'Utvalda grupper',
	'groups:makeunfeatured' => 'Ta bort utvald',
	'groups:makefeatured' => 'Gör till utvald',
	'groups:featuredon' => '%s är nu en utvald grupp.',
	'groups:unfeatured' => '%s har tagits bort från de utvalda grupperna.',
	'groups:featured_error' => 'Ogiltig grupp.',
	'groups:nofeatured' => 'Inga utvalda grupper.',
	'groups:joinrequest' => 'Begär medlemsskap',
	'groups:join' => 'Gå med i grupp',
	'groups:leave' => 'Lämna grupp',
	'groups:invite' => 'Bjud in vänner',
	'groups:invite:title' => 'Bjud in vänner till den här gruppen',
	'groups:invite:friends:help' => 'Sök efter en vän med namn eller användarnamn och välj vännen från listan',
	'groups:invite:resend' => 'Återsänd inbjudningar till redan inbjudna användare',
	'groups:invite:member' => 'Redan medlem i den här gruppen',
	'groups:invite:invited' => 'Redan inbjuden till den här gruppen',

	'groups:nofriendsatall' => 'Du har inga vänner att bjuda in!',
	'groups:group' => "Grupp",
	'groups:search:title' => "Sök efter grupper med '%s'",
	'groups:search:none' => "Inga matchande grupper hittades",
	'groups:search_in_group' => "Sök i den här gruppen",
	'groups:acl' => "Grupp: %s",
	'groups:acl:in_context' => 'Gruppmedlemmar',

	'groups:notfound' => "Grupp hittades inte",
	
	'groups:requests:none' => 'Det finns för närvarande inga förfrågningar om medlemskap.',

	'groups:invitations:none' => 'Det finns för närvarande inga inbjudningar.',

	'groups:open' => "öppen grupp",
	'groups:closed' => "stängd grupp",
	'groups:member' => "medlemmar",
	'groups:search' => "Sök efter grupper",

	'groups:more' => 'Fler grupper',
	'groups:none' => 'Inga grupper',

	/**
	 * Access
	 */
	'groups:access:private' => 'Stängd - Användare måste bjudas in',
	'groups:access:public' => 'Öppen - Vilken användare som helst kan gå med',
	'groups:access:group' => 'Endast gruppmedlemmar',
	'groups:closedgroup' => "Den här gruppens medlemskap är stängd.",
	'groups:closedgroup:request' => 'För att be om att bli tillagd, tryck på menylänken "Begär medlemskap".',
	'groups:closedgroup:membersonly' => "Den här gruppens medlemskap är stängd och dess innehåll kan endast nås av medlemmar.",
	'groups:opengroup:membersonly' => "Den här gruppens innehåll kan endast nås av medlemmar.",
	'groups:opengroup:membersonly:join' => 'För att bli medlem, tryck på menylänken "Gå med i grupp".',
	'groups:visibility' => 'Vem kan se den här gruppen?',
	'groups:content_default_access' => 'Standardbehörighet för gruppinnehåll',
	'groups:content_default_access:help' => 'Här kan du konfigurera standardbehörigheten för nytt innehåll i den här gruppen. Innehållsnivån i gruppen kan förhindra det valda alternativet att gälla.',
	'groups:content_default_access:not_configured' => 'Ingen standardbehörig är konfigurerad, lämna det till användaren',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupper',

	'groups:notitle' => 'Grupper måste ha en titel',
	'groups:cantjoin' => 'Kan inte gå med i grupp',
	'groups:cantleave' => 'Kunde inte lämna gruppen',
	'groups:removeuser' => 'Ta bort från grupp',
	'groups:cantremove' => 'Kan inte ta bort användare från grupp',
	'groups:removed' => '%s togs bort från grupp med lyckat resultat',
	'groups:addedtogroup' => 'Användaren lades till i gruppen med lyckat resultat',
	'groups:joinrequestnotmade' => 'Kunde inte göra en begäran att gå med i grupp',
	'groups:joinrequestmade' => 'Begärde att gå med i grupp',
	'groups:joinrequest:exists' => 'Du har redan begärt medlemskap i den här gruppen',
	'groups:button:joined' => 'Gick med',
	'groups:button:owned' => 'Ägd',
	'groups:joined' => 'Gick med i grupp!',
	'groups:left' => 'Lämnade grupp',
	'groups:userinvited' => 'Användare har bjudits in.',
	'groups:usernotinvited' => 'Användare kunde inte bjudas in.',
	'groups:useralreadyinvited' => 'Användare har redan bjudits in',
	'groups:invite:subject' => "%s du har blivit inbjuden att gå med i %s!",
	'groups:joinrequest:remove:check' => 'Är du säker på att du vill ta bort den här begäran om att gå med?',
	'groups:invite:remove:check' => 'Är du säker på att du vill ta bort den här inbjudan?',

	'groups:welcome:subject' => "Välkommen till gruppen %s!",

	'groups:request:subject' => "%s har begärt att gå med i %s",

	'river:group:create' => '%s skapade gruppen %s',
	'river:group:join' => '%s gick med i gruppen %s',

	'groups:allowhiddengroups' => 'Tillåt privata (osynliga) grupper?',
	'groups:whocancreate' => 'Vem kan skapa nya grupper?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Inbjudan har tagits bort.',
	'groups:joinrequestkilled' => 'Begäran om att gå med har tagits bort.',
	'groups:error:addedtogroup' => "Kunde inte lägga till %s i gruppen",
	'groups:add:alreadymember' => "%s är redan medlem i den här gruppen",
	
	// Notification settings
);
