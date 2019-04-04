<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Grupe",
	'groups:owned' => "Grupe kojima sam vlasnik",
	'groups:owned:user' => 'Groups %s owns',
	'groups:yours' => "Moje grupe",
	'groups:user' => "%s's groups",
	'groups:all' => "Sve grupe",
	'groups:add' => "Stvori novu grupu",
	'groups:edit' => "Uredi grupu",
	'groups:delete' => 'Izbriši grupu',
	'groups:membershiprequests' => 'Manage join requests',
	'groups:membershiprequests:pending' => 'Manage join requests (%s)',
	'groups:invitations' => 'Group invitations',
	'groups:invitations:pending' => 'Group invitations (%s)',

	'groups:icon' => 'Group icon (leave blank to leave unchanged)',
	'groups:name' => 'Naziv grupe',
	'groups:description' => 'Opis',
	'groups:briefdescription' => 'Kratak opis',
	'groups:interests' => 'Oznake',
	'groups:website' => 'Website',
	'groups:members' => 'Članovi grupe',

	'groups:members_count' => '%s members',

	'groups:members:title' => 'Members of %s',
	'groups:members:more' => "Pregledaj sve članove",
	'groups:membership' => "Ovlasti članova u grupi",
	'groups:content_access_mode' => "Accessibility of group content",
	'groups:content_access_mode:warning' => "Warning: Changing this setting won't change the access permission of existing group content.",
	'groups:content_access_mode:unrestricted' => "Unrestricted - Access depends on content-level settings",
	'groups:content_access_mode:membersonly' => "Rezervirano za članove - nečlanovi ne mogu pristupiti sadržaju grupe",
	'groups:access' => "Ovlasti pristupa",
	'groups:owner' => "Vlasnik",
	'groups:owner:warning' => "Upozorenje: ako promijenite ovu vrijednost nećete više biti vlasnik grupe",
	'groups:widget:num_display' => 'Broj grupa za prikaz',
	'widgets:a_users_groups:name' => 'Group membership',
	'widgets:a_users_groups:description' => 'Display the groups you are a member of on your profile',

	'groups:noaccess' => 'No access to group',
	'groups:cantcreate' => 'Ne možete izraditi grupu. To mogu samo administratori. ',
	'groups:cantedit' => 'Ne možete uređivati ovu grupu',
	'groups:saved' => 'Grupa je sačuvana',
	'groups:save_error' => 'Grupu nije moguće spremiti',
	'groups:featured' => 'Featured groups',
	'groups:makeunfeatured' => 'Unfeature',
	'groups:makefeatured' => 'Make featured',
	'groups:featuredon' => '%s is now a featured group.',
	'groups:unfeatured' => '%s has been removed from the featured groups.',
	'groups:featured_error' => 'Invalid group.',
	'groups:nofeatured' => 'No featured groups',
	'groups:joinrequest' => 'Zatražite članstvo',
	'groups:join' => 'Pridružite se grupi',
	'groups:leave' => 'Napustite grupu',
	'groups:invite' => 'Pozovite prijatelje',
	'groups:invite:title' => 'Pozovi prijatelje u ovu grupu',
	'groups:invite:friends:help' => 'Pretraži prijatelja po imenu ili korisničkom imenu i odaberi ga s popisa',
	'groups:invite:resend' => 'Ponovno pošalji poziv već pozvanim korisnicima',

	'groups:nofriendsatall' => 'Nije moguće pozvati još prijatelja!',
	'groups:group' => "Grupa",
	'groups:search:tags' => "oznaka",
	'groups:search:title' => "Pretraži grupu naziva '%s'",
	'groups:search:none' => "No matching groups were found",
	'groups:search_in_group' => "Pretraži ovu grupu",
	'groups:acl' => "Grupa: %s",
	'groups:acl:in_context' => 'Čanovi grupe',

	'groups:notfound' => "Grupa nije pronađena",
	
	'groups:requests:none' => 'There are no current membership requests.',

	'groups:invitations:none' => 'There are no current invitations.',

	'groups:open' => "otvorena grupa",
	'groups:closed' => "zatvorena grupa",
	'groups:member' => "članovi",
	'groups:search' => "Pretraži grupe",

	'groups:more' => 'Više grupa',
	'groups:none' => 'Nema grupa',

	/**
	 * Access
	 */
	'groups:access:private' => 'Zatvoreno - Korisnici trebaju biti pozvani',
	'groups:access:public' => 'Otvoreno - Svaki korisnik se može pridružiti',
	'groups:access:group' => 'Samo za članove grupe',
	'groups:closedgroup' => "This group's membership is closed.",
	'groups:closedgroup:request' => 'To ask to be added, click the "Request membership" menu link.',
	'groups:closedgroup:membersonly' => "This group's membership is closed and its content is accessible only by members.",
	'groups:opengroup:membersonly' => "Sadržaju grupe mogu pristpiti samo njezini članovi. ",
	'groups:opengroup:membersonly:join' => 'To be a member, click the "Join group" menu link.',
	'groups:visibility' => 'Who can see this group?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupe',

	'groups:notitle' => 'Grupe trebaju imati naziv',
	'groups:cantjoin' => 'Nije se moguće pridružiti grupi',
	'groups:cantleave' => 'Nije moguće napustiti grupu',
	'groups:removeuser' => 'Ukloni iz grupe',
	'groups:cantremove' => 'Nije moguće ukloniti korisnike iz grupe',
	'groups:removed' => 'Uspješno uklonjen %s iz grupe',
	'groups:addedtogroup' => 'Korisnik uspješno dodan u grupu',
	'groups:joinrequestnotmade' => 'Could not request to join group',
	'groups:joinrequestmade' => 'Zatraži članstvo u grupi',
	'groups:joinrequest:exists' => 'Već ste zatražili članstvo u ovoj grupi',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'Successfully joined group!',
	'groups:left' => 'Successfully left group',
	'groups:userinvited' => 'Korisnik je pozvan. ',
	'groups:usernotinvited' => 'Korisnika nije moguće pozvati. ',
	'groups:useralreadyinvited' => 'Korisnik je već pozvan',
	'groups:invite:subject' => "%s you have been invited to join %s!",
	'groups:joinrequest:remove:check' => 'Jeste li sigurni da želite ukloniti ovaj zahtjev za uključivanjem?',
	'groups:invite:remove:check' => 'Jeste li sigurni da želite ukloniti ovaj poziv?',
	'groups:invite:body' => "Bok %s, 

%ste poziva da se pridružiš grupi '%s'

Za pregled pozivnike kliknite ovdje:
%s",

	'groups:welcome:subject' => "Dobrodošli u grupu %s!",
	'groups:welcome:body' => "Bok %s!

Od sada ste član grupe '%s'

Za objavu poruka kliknite ovdje
%s",

	'groups:request:subject' => "%s je zatražio uključivanje u grupu %s",
	'groups:request:body' => "Bok %s, 

%s je zatražio uključivanje u grupu '%s'

Za pregled profila kliknite ovdje:
%s

a za pregled zahtjeva za pridruživanje grupi kliknite ovjde:
%s
",

	'river:group:create' => '%s izrađena je grupa %s',
	'river:group:join' => '%s priključilo se grupi %s',

	'groups:allowhiddengroups' => 'Omogućiti privatne (nevidljive) grupe?',
	'groups:whocancreate' => 'Tko može izraditi novu grupu?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Grupa i sadržaj grupe su izbrisani',
	'groups:notdeleted' => 'Grupu nije moguće izbrisati',
	'groups:deletewarning' => "Jeste li sigurni da želite izbrisati ovu grupu? Ne postoji mogućnost povrata!",

	'groups:invitekilled' => 'Poziv je izbrisan. ',
	'groups:joinrequestkilled' => 'Zahtjev za priključivanjem grupi je izbrisan. ',
	'groups:error:addedtogroup' => "Grupi nije moguće dodati %s",
	'groups:add:alreadymember' => "%s je već član grupe",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Grupni profili',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Prijenos ikone grupe na novu lokaciju',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade aligns will align group plugin with the requirements of the new API.',
);
