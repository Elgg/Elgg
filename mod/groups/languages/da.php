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
	'groups:username' => 'Gruppens korte navn (vises i web adressen, brug kun alfanumeriske tegn, dvs. A-Z og 0-9)',
	'groups:description' => 'Beskrivelse',
	'groups:briefdescription' => 'Kort beskrivelse',
	'groups:interests' => 'Tags',
	'groups:website' => 'Hjemmeside',
	'groups:members' => 'Gruppens medlemmer',
	'groups:my_status' => 'My status',
	'groups:my_status:group_owner' => 'You own this group',
	'groups:my_status:group_member' => 'You are in this group',
	'groups:subscribed' => 'Group notifications on',
	'groups:unsubscribed' => 'Group notifications off',

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
	'groups:widget:membership' => 'Grupper',
	'groups:widgets:description' => 'Vis de grupper, som du er medlem af, på din profil',

	'groups:widget:group_activity:title' => 'Gruppeaktivitet',
	'groups:widget:group_activity:description' => 'Vis aktiviteten i en af dine grupper',
	'groups:widget:group_activity:edit:select' => 'Vælg en gruppe',
	'groups:widget:group_activity:content:noactivity' => 'Der er ingen aktivitet i denne gruppe',
	'groups:widget:group_activity:content:noselect' => 'Ændre denne widget for at vælge en gruppe',

	'groups:noaccess' => 'Ikke adgang til gruppen',
	'groups:permissions:error' => 'Du har ikke tilladelser til dette',
	'groups:ingroup' => 'i gruppen',
	'groups:cantcreate' => 'You can not create a group. Only admins can.',
	'groups:cantedit' => 'Du kan ikke redigere denne gruppe',
	'groups:saved' => 'Gruppe gemt',
	'groups:featured' => 'Foretrukne grupper',
	'groups:makeunfeatured' => 'Vælg fra',
	'groups:makefeatured' => 'Vælg til',
	'groups:featuredon' => '%s er nu en foretrukket gruppe',
	'groups:unfeatured' => '%s has been removed from the featured groups.',
	'groups:featured_error' => 'Ugyldig gruppe.',
	'groups:joinrequest' => 'Ansøg om medlemsskab',
	'groups:join' => 'Bliv medlem af gruppen',
	'groups:leave' => 'Forlad gruppen',
	'groups:invite' => 'Inviter venner',
	'groups:invite:title' => 'Inviter venner til gruppen',
	'groups:inviteto' => "Inviter venner til '%s'",
	'groups:nofriends' => "Ingen af dine venner mangler at blive inviteret til gruppen.",
	'groups:nofriendsatall' => 'Du har ingen venner at invitere!',
	'groups:viagroups' => "via grupper",
	'groups:group' => "Gruppe",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Søg efter grupper tagget med '%s'",
	'groups:search:none' => "Ingen match blev fundet",
	'groups:search_in_group' => "Search in this group",
	'groups:acl' => "Group: %s",

	'discussion:topic:notify:summary' => 'Nyt diskussionsemne kaldt %s',
	'discussion:topic:notify:subject' => 'Nyt diskussionsemne: %s',
	'discussion:topic:notify:body' =>
'%s har tilføjet et nyt diskussionsemne i gruppen %s:

Titel: %s

%s

Se og svar på diskussionsemnet:
%s
',

	'discussion:reply:notify:summary' => 'Nyt svar i emnet: %s',
	'discussion:reply:notify:subject' => 'Nyt svar i emnet: %s',
	'discussion:reply:notify:body' =>
'%s replied to the discussion topic %s in the group %s:

%s

View and reply to the discussion:
%s
',

	'groups:activity' => "Gruppeaktivitet",
	'groups:enableactivity' => 'Aktiver gruppeaktivitet',
	'groups:activity:none' => "Der er ingen gruppeaktivitet endnu",

	'groups:notfound' => "Gruppe ikke fundet",
	'groups:notfound:details' => "Den forespurgte gruppe eksisterer ikke eller du har ikke adgang til den",

	'groups:requests:none' => 'Der er ingen udestående anmodninger om medlemskab.',

	'groups:invitations:none' => 'Der er ingen udestående invitationer.',

	'item:object:groupforumtopic' => "Diskussions emner",
	'item:object:discussion_reply' => "Svar på diskussioner",

	'groupforumtopic:new' => "Nyt diskussions emne",

	'groups:count' => "grupper oprettet",
	'groups:open' => "åben gruppe",
	'groups:closed' => "lukket gruppe",
	'groups:member' => "medlemmer",
	'groups:searchtag' => "Søg grupper efter tag",

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
	'groups:enableforum' => 'Aktiver gruppedebat',
	'groups:lastupdated' => 'Sidst opdateret %s af %s',
	'groups:lastcomment' => 'Seneste kommentar %s af %s',

	/**
	 * Group discussion
	 */
	'discussion' => 'Discussion',
	'discussion:add' => 'Tilføj diskussionsemne',
	'discussion:latest' => 'Seneste diskussion',
	'discussion:group' => 'Gruppediskussioner',
	'discussion:none' => 'No discussion',
	'discussion:reply:title' => 'Reply by %s',

	'discussion:topic:created' => 'Diskussionsemnet blev oprettet.',
	'discussion:topic:updated' => 'Diskussionsemnet blev opdateret.',
	'discussion:topic:deleted' => 'Diskussionsemne er blevet slettet.',

	'discussion:topic:notfound' => 'Diskussionsemne ikke fundet',
	'discussion:error:notsaved' => 'Kan ikke gemme dette emne',
	'discussion:error:missing' => 'Både titel og besked skal udfyldes',
	'discussion:error:permissions' => 'Du har ikke tilladelse til at udføre denne handling',
	'discussion:error:notdeleted' => 'Kunne ikke slette diskussionsemne',

	'discussion:reply:edit' => 'Ændre svar',
	'discussion:reply:deleted' => 'Svaret er blevet slettet.',
	'discussion:reply:error:notfound' => 'Svaret på diskussionen blev ikke fundet',
	'discussion:reply:error:notdeleted' => 'Kunne ikke slette diskussionssvaret',

	'admin:groups' => 'Grupper',

	'reply:this' => 'Reply to this',

	'group:replies' => 'Svar',
	'groups:forum:created' => 'Oprettet %s med %d kommentarer',
	'groups:forum:created:single' => 'Oprettet %s med %d kommentar',
	'groups:forum' => 'Discussion',
	'groups:addtopic' => 'Tilføj et emne',
	'groups:forumlatest' => 'Seneste diskussion',
	'groups:latestdiscussion' => 'Seneste diskussion',
	'groupspost:success' => 'Din kommentar er tilføjet',
	'groupspost:failure' => 'Der opstod et problem under oprettelse af dit svar',
	'groups:alldiscussion' => 'Seneste diskussion',
	'groups:edittopic' => 'Rediger emne',
	'groups:topicmessage' => 'Emne besked',
	'groups:topicstatus' => 'Emne status',
	'groups:reply' => 'Send en kommentar',
	'groups:topic' => 'Emne',
	'groups:posts' => 'Indlæg',
	'groups:lastperson' => 'Sidste person',
	'groups:when' => 'Når',
	'grouptopic:notcreated' => 'Ingen enmer er blevet oprettet.',
	'groups:topicclosed' => 'Lukket',
	'grouptopic:created' => 'Dit emne blev oprettet.',
	'groups:topicsticky' => 'Vigtig',
	'groups:topicisclosed' => 'Dette ene er lukket.',
	'groups:topiccloseddesc' => 'Dette emne er nu blevet lukket og kan ikke modtage nye kommenterer.',
	'grouptopic:error' => 'Dit gruppeemne kunne ikke oprettes. Prøv venligst igen eller kontakt systemadministratoren.',
	'groups:forumpost:edited' => "Du har redigeret forumindlægget korrekt.",
	'groups:forumpost:error' => "Der opstod et problem med at redigere forumindlægget.",

	'groups:privategroup' => 'Denne gruppe er privat, kræver medlemsskab.',
	'groups:notitle' => 'Grupper skal have en titel',
	'groups:cantjoin' => 'Kunne ikke blive medlem af gruppen',
	'groups:cantleave' => 'Kunne ikke forlade gruppen',
	'groups:removeuser' => 'Fjern fra gruppe',
	'groups:cantremove' => 'Kan ikke fjerne bruger fra gruppe',
	'groups:removed' => '%s er fjernet fra gruppen',
	'groups:addedtogroup' => 'Brugeren blev tilføjet til gruppen',
	'groups:joinrequestnotmade' => 'Kunne ikke ansøge om at blive medlem',
	'groups:joinrequestmade' => 'Ansøgning om at blive medlem af gruppen er gennemført',
	'groups:joined' => 'Du er blevet medlem af gruppen!',
	'groups:left' => 'Du er frameldt gruppen!',
	'groups:notowner' => 'Beklager, du ejer ikke denne gruppe.',
	'groups:notmember' => 'Beklager, du er ikke medlem af denne gruppe.',
	'groups:alreadymember' => 'Du er allerede medlem af denne gruppe!',
	'groups:userinvited' => 'Brugeren er blevet inviteret.',
	'groups:usernotinvited' => 'Brugeren kunne ikke inviteres.',
	'groups:useralreadyinvited' => 'Brugeren er allerede blevet inviteret',
	'groups:invite:subject' => "%s du er blevet inviteret til at blive medlem af %s!",
	'groups:updated' => "Seneste kommentar af %s %s",
	'groups:started' => "Startet af %s",
	'groups:joinrequest:remove:check' => 'Er du sikker på, at du vil fjerne denne anmodning om tilmelding?',
	'groups:invite:remove:check' => 'Er du sikker på, at du vil fjerne denne invitation?',
	'groups:invite:body' => "Hej %s,
	
%s inviterede dig til at være med i '%s' gruppen, klik herunder for at bekræfte:

%s",

	'groups:welcome:subject' => "Velkommen til %s gruppen!",
	'groups:welcome:body' => "Hej %s!
	
Du er nu medlem af '%s' gruppen! Klik herunder for at begynde med at skrive!

%s",

	'groups:request:subject' => "%s har ønsket at blive medlem af %s",
	'groups:request:body' => "Hej %s,
	
%s har bedt om at måtte være med i '%s' gruppen, klik nedenfor for at se deres profil:

%s

eller klik nedenfor for at se gruppens anmodningsliste:

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s oprettede gruppen %s',
	'river:join:group:default' => '%s blev medlem af gruppen %s',
	'river:create:object:groupforumtopic' => '%s tilføjede et nyt diskussionsemne %s',
	'river:reply:object:groupforumtopic' => '%s svarede på diskussionsemnet %s',

	'groups:nowidgets' => 'Ingen widgets defineret for denne gruppe.',


	'groups:widgets:members:title' => 'Gruppens medlemmer',
	'groups:widgets:members:description' => 'Vis en gruppes medlemmer.',
	'groups:widgets:members:label:displaynum' => 'Vis en gruppes medlemmer.',
	'groups:widgets:members:label:pleaseedit' => 'Indstil venligst denne widget.',

	'groups:widgets:entities:title' => "Objekter i gruppen",
	'groups:widgets:entities:description' => "Vis objekterne gemt i denne gruppe",
	'groups:widgets:entities:label:displaynum' => 'Vis en gruppes objekter',
	'groups:widgets:entities:label:pleaseedit' => 'Indstil venligst denne widget.',

	'groups:forumtopic:edited' => 'Forumemne succesfuldt redigeret.',

	'groups:allowhiddengroups' => 'Vil du tillade private (skjulte) grupper?',
	'groups:whocancreate' => 'Who can create new groups?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Gruppe og gruppeindhold slettet',
	'group:notdeleted' => 'Gruppen kunne ikke slettes',

	'group:notfound' => 'Kunne ikke finde gruppen',
	'grouppost:deleted' => 'Gruppeindlæg slettet korrekt',
	'grouppost:notdeleted' => 'Gruppeindlæg kunne ikke slettes',
	'groupstopic:deleted' => 'Emne slettet',
	'groupstopic:notdeleted' => 'Emne kunne ikke slettes',
	'grouptopic:blank' => 'Ingen emner',
	'grouptopic:notfound' => 'Kunne ikke finde emnet',
	'grouppost:nopost' => 'Tom post',
	'groups:deletewarning' => "Er du sikker på at du vil slette denne gruppe? Du kan ikke gøre det om!",

	'groups:invitekilled' => 'Invitationen er blevet slettet.',
	'groups:joinrequestkilled' => 'Anmodningen om tilslutning er blevet slettet.',

	/**
	 * ecml
	 */
	'groups:ecml:discussion' => 'Gruppediskussioner',
	'groups:ecml:groupprofile' => 'Gruppeprofiler',
);
