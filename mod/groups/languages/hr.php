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
	'groups' => "Grupe",
	'groups:owned' => "Grupe kojima sam vlasnik",
	'groups:yours' => "Moje grupe",
	'groups:all' => "Sve grupe",
	'groups:add' => "Stvori novu grupu",
	'groups:edit' => "Uredi grupu",
	'groups:delete' => 'Izbriši grupu',
	'groups:name' => 'Naziv grupe',
	'groups:description' => 'Opis',
	'groups:briefdescription' => 'Kratak opis',
	'groups:interests' => 'Oznake',
	'groups:members' => 'Članovi grupe',
	'groups:members:more' => "Pregledaj sve članove",
	'groups:membership' => "Ovlasti članova u grupi",
	'groups:content_access_mode:membersonly' => "Rezervirano za članove - nečlanovi ne mogu pristupiti sadržaju grupe",
	'groups:access' => "Ovlasti pristupa",
	'groups:owner' => "Vlasnik",
	'groups:owner:warning' => "Upozorenje: ako promijenite ovu vrijednost nećete više biti vlasnik grupe",
	'groups:widget:num_display' => 'Broj grupa za prikaz',
	'widgets:a_users_groups:description' => 'Na profilu prikaži grupe u kojima sam član',
	'groups:cantcreate' => 'Ne možete izraditi grupu. To mogu samo administratori. ',
	'groups:cantedit' => 'Ne možete uređivati ovu grupu',
	'groups:saved' => 'Grupa je sačuvana',
	'groups:save_error' => 'Grupu nije moguće spremiti',
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
	'groups:search_in_group' => "Pretraži ovu grupu",
	'groups:acl' => "Grupa: %s",
	'groups:acl:in_context' => 'Čanovi grupe',

	'groups:notfound' => "Grupa nije pronađena",

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
	'groups:opengroup:membersonly' => "Sadržaju grupe mogu pristpiti samo njezini članovi. ",

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
	'groups:joinrequestmade' => 'Zatraži članstvo u grupi',
	'groups:joinrequest:exists' => 'Već ste zatražili članstvo u ovoj grupi',
	'groups:userinvited' => 'Korisnik je pozvan. ',
	'groups:usernotinvited' => 'Korisnika nije moguće pozvati. ',
	'groups:useralreadyinvited' => 'Korisnik je već pozvan',
	'groups:joinrequest:remove:check' => 'Jeste li sigurni da želite ukloniti ovaj zahtjev za uključivanjem?',
	'groups:invite:remove:check' => 'Jeste li sigurni da želite ukloniti ovaj poziv?',

	'groups:welcome:subject' => "Dobrodošli u grupu %s!",

	'groups:request:subject' => "%s je zatražio uključivanje u grupu %s",

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
	
	// Notification settings
);
