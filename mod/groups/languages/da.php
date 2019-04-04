<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Grupper",
	'groups:owned' => "Grupper jeg styrer",
	'groups:owned:user' => 'Groups %s owns',
	'groups:yours' => "Dine grupper",
	'groups:user' => "%s's grupper",
	'groups:all' => "Alle grupper",
	'groups:add' => "Opret en ny gruppe",
	'groups:edit' => "Rediger gruppe",
	'groups:delete' => 'Slet gruppe',
	'groups:membershiprequests' => 'Administrer anmodning om deltagelse',
	'groups:membershiprequests:pending' => 'Manage join requests (%s)',
	'groups:invitations' => 'Gruppe invitationer',
	'groups:invitations:pending' => 'Group invitations (%s)',

	'groups:icon' => 'Gruppe ikon (efterlad blank for at beholde det uændret)',
	'groups:name' => 'Gruppe navn',
	'groups:description' => 'Beskrivelse',
	'groups:briefdescription' => 'Kort beskrivelse',
	'groups:interests' => 'Tags',
	'groups:website' => 'Hjemmeside',
	'groups:members' => 'Gruppens medlemmer',

	'groups:members_count' => '%s members',

	'groups:members:title' => 'Medlemmer af %s',
	'groups:members:more' => "Se alle medlemmer",
	'groups:membership' => "Medlemsskab",
	'groups:content_access_mode' => "Adgangsniveau for gruppe indhold",
	'groups:content_access_mode:warning' => "Advarsel: Ændring af denne indstilling ændrer ikke adgangsniveauet på eksisterende gruppe indhold.",
	'groups:content_access_mode:unrestricted' => "Ubegrænset - Adgang afhængig af indhold-niveau indstillinger",
	'groups:content_access_mode:membersonly' => "Kun medlemmer - Ikke-medlemmer kan aldrig få adgang til gruppe indholdet",
	'groups:access' => "Adgangs tilladelser",
	'groups:owner' => "Ejer",
	'groups:owner:warning' => "Warning: if you change this value, you will no longer be the owner of this group.",
	'groups:widget:num_display' => 'Antal af grupper der skal vises',
	'widgets:a_users_groups:name' => 'Group membership',
	'widgets:a_users_groups:description' => 'Display the groups you are a member of on your profile',

	'groups:noaccess' => 'Ikke adgang til gruppen',
	'groups:cantcreate' => 'You can not create a group. Only admins can.',
	'groups:cantedit' => 'Du kan ikke redigere denne gruppe',
	'groups:saved' => 'Gruppe gemt',
	'groups:save_error' => 'Group could not be saved',
	'groups:featured' => 'Foretrukne grupper',
	'groups:makeunfeatured' => 'Vælg fra',
	'groups:makefeatured' => 'Vælg til',
	'groups:featuredon' => '%s er nu en foretrukket gruppe',
	'groups:unfeatured' => '%s has been removed from the featured groups.',
	'groups:featured_error' => 'Ugyldig gruppe.',
	'groups:nofeatured' => 'No featured groups',
	'groups:joinrequest' => 'Ansøg om medlemsskab',
	'groups:join' => 'Bliv medlem af gruppen',
	'groups:leave' => 'Forlad gruppen',
	'groups:invite' => 'Inviter venner',
	'groups:invite:title' => 'Inviter venner til gruppen',
	'groups:invite:friends:help' => 'Search for a friend by name or username and select the friend from the list',
	'groups:invite:resend' => 'Resend the invitations to already invited users',

	'groups:nofriendsatall' => 'Du har ingen venner at invitere!',
	'groups:group' => "Gruppe",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Søg efter grupper tagget med '%s'",
	'groups:search:none' => "Ingen match blev fundet",
	'groups:search_in_group' => "Search in this group",
	'groups:acl' => "Group: %s",
	'groups:acl:in_context' => 'Group members',

	'groups:notfound' => "Gruppe ikke fundet",
	
	'groups:requests:none' => 'Der er ingen udestående anmodninger om medlemskab.',

	'groups:invitations:none' => 'Der er ingen udestående invitationer.',

	'groups:open' => "åben gruppe",
	'groups:closed' => "lukket gruppe",
	'groups:member' => "medlemmer",
	'groups:search' => "Search for groups",

	'groups:more' => 'Flere grupper',
	'groups:none' => 'Ingen grupper',

	/**
	 * Access
	 */
	'groups:access:private' => 'Lukket - brugere skal inviteres',
	'groups:access:public' => 'Åben - alle kan deltage',
	'groups:access:group' => 'Kun for medlemmer',
	'groups:closedgroup' => "Denne gruppe's medlemsskab er lukket.",
	'groups:closedgroup:request' => 'For at ansøge om at blive tilmeldt, skal du klikke på "Ansøg medlemsskab" menu linket.',
	'groups:closedgroup:membersonly' => "Denne gruppe's medlemsskab er lukket og kun medlemmerne har adgang til gruppens indhold.",
	'groups:opengroup:membersonly' => "Denne gruppe's indhold er kun tilladt for gruppens medlemmer.",
	'groups:opengroup:membersonly:join' => 'For at blive medlem, klik "Tilmeld gruppe" menu linket',
	'groups:visibility' => 'Hvem kan se denne gruppe?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupper',

	'groups:notitle' => 'Grupper skal have en titel',
	'groups:cantjoin' => 'Kunne ikke blive medlem af gruppen',
	'groups:cantleave' => 'Kunne ikke forlade gruppen',
	'groups:removeuser' => 'Fjern fra gruppe',
	'groups:cantremove' => 'Kan ikke fjerne bruger fra gruppe',
	'groups:removed' => '%s er fjernet fra gruppen',
	'groups:addedtogroup' => 'Brugeren blev tilføjet til gruppen',
	'groups:joinrequestnotmade' => 'Kunne ikke ansøge om at blive medlem',
	'groups:joinrequestmade' => 'Ansøgning om at blive medlem af gruppen er gennemført',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'Du er blevet medlem af gruppen!',
	'groups:left' => 'Du er frameldt gruppen!',
	'groups:userinvited' => 'Brugeren er blevet inviteret.',
	'groups:usernotinvited' => 'Brugeren kunne ikke inviteres.',
	'groups:useralreadyinvited' => 'Brugeren er allerede blevet inviteret',
	'groups:invite:subject' => "%s du er blevet inviteret til at blive medlem af %s!",
	'groups:joinrequest:remove:check' => 'Er du sikker på, at du vil fjerne denne anmodning om tilmelding?',
	'groups:invite:remove:check' => 'Er du sikker på, at du vil fjerne denne invitation?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group.

Click below to view your invitations:
%s",

	'groups:welcome:subject' => "Velkommen til %s gruppen!",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group.

Click below to begin posting!
%s",

	'groups:request:subject' => "%s har ønsket at blive medlem af %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group.

Click below to view their profile:
%s

or click below to view the group's join requests:
%s",

	'river:group:create' => '%s created the group %s',
	'river:group:join' => '%s joined the group %s',

	'groups:allowhiddengroups' => 'Vil du tillade private (skjulte) grupper?',
	'groups:whocancreate' => 'Who can create new groups?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Group and group contents deleted',
	'groups:notdeleted' => 'Group could not be deleted',
	'groups:deletewarning' => "Er du sikker på at du vil slette denne gruppe? Du kan ikke gøre det om!",

	'groups:invitekilled' => 'Invitationen er blevet slettet.',
	'groups:joinrequestkilled' => 'Anmodningen om tilslutning er blevet slettet.',
	'groups:error:addedtogroup' => "Could not add %s to the group",
	'groups:add:alreadymember' => "%s is already a member of this group",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Gruppeprofiler',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Transfer group icons to new location',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade aligns will align group plugin with the requirements of the new API.',
);
