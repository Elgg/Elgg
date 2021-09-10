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
	
	'groups' => "Ryhmät",
	'groups:owned' => "Ryhmät, jotka omistan",
	'groups:owned:user' => 'Käyttäjän %s omistamat ryhmät',
	'groups:yours' => "Omat ryhmäni",
	'groups:user' => "Käyttäjän %s ryhmät",
	'groups:all' => "Kaikki ryhmät",
	'groups:add' => "Luo uusi ryhmä",
	'groups:edit' => "Muokkaa ryhmää",
	'groups:membershiprequests' => 'Hallinnoi liittymispyyntöjä',
	'groups:membershiprequests:pending' => 'Hallinnoi liittymispyyntöjä (%s)',
	'groups:invitations' => 'Ryhmäkutsut',
	'groups:invitations:pending' => 'Ryhmäkutsut (%s)',

	'groups:icon' => 'Ryhmän kuvake (jätä tyhjäksi, jos et halua vaihtaa)',
	'groups:name' => 'Ryhmän nimi',
	'groups:description' => 'Kuvaus',
	'groups:briefdescription' => 'Lyhyt kuvaus',
	'groups:interests' => 'Tagit',
	'groups:website' => 'Nettisivu',
	'groups:members' => 'Ryhmän jäsenet',

	'groups:members:title' => 'Jäsenet ryhmässä: %s',
	'groups:members:more' => "Näytä kaikki jäsenet",
	'groups:membership' => "Ryhmän jäsenyysasetukset",
	'groups:content_access_mode' => "Pääsy ryhmän sisältöihin",
	'groups:content_access_mode:warning' => "Varoitus: Asetuksen muuttaminen vaikuttaa vain uusiin sisältöihin.",
	'groups:content_access_mode:unrestricted' => "Rajoittamaton - Näkyvyys riippuu kunkin sisällön omasta näkyvyystasosta",
	'groups:content_access_mode:membersonly' => "Vain jäsenet - Ryhmän ulkopuoliset eivät pääse ollenkaan käsiksi sisältöihin",
	'groups:access' => "Pääsyoikeudet",
	'groups:owner' => "Omistaja",
	'groups:owner:warning' => "Varoitus: Jos muutat tätä, luovut ryhmän omistajuudesta.",
	'groups:widget:num_display' => 'Näytettävien ryhmien määrä',
	'widgets:a_users_groups:name' => 'Ryhmien jäsenyys',
	'widgets:a_users_groups:description' => 'Näytä profiilissasi ryhmät, joiden jäsenenä olet',

	'groups:noaccess' => 'Sinulla ei ole oikeuksia tämän ryhmän näkemiseen',
	'groups:cantcreate' => 'Vain sivuston ylläpitäjät voivat luoda uusia ryhmiä.',
	'groups:cantedit' => 'Voit nyt muokata ryhmää',
	'groups:saved' => 'Ryhmä tallennettu',
	'groups:save_error' => 'Ryhmän tallentaminen epäonnistui',
	'groups:featured' => 'Valikoidut ryhmät',
	'groups:makeunfeatured' => 'Poista mainos',
	'groups:makefeatured' => 'Mainosta ryhmää',
	'groups:featuredon' => '%s on nyt mainostettu ryhmä.',
	'groups:unfeatured' => '%s on poistettu mainostettavista ryhmistä.',
	'groups:featured_error' => 'Virheellinen ryhmä.',
	'groups:nofeatured' => 'Ei valikoituja ryhmiä',
	'groups:joinrequest' => 'Ano jäsenyyttä',
	'groups:join' => 'Liity ryhmään',
	'groups:leave' => 'Eroa ryhmästä',
	'groups:invite' => 'Kutsu ystäviä',
	'groups:invite:title' => 'Kutsu ystäviä ryhmään',

	'groups:nofriendsatall' => 'Sinulla ei vielä ole ystäviä, joita kutsua!',
	'groups:group' => "Ryhmä",
	'groups:search:title' => "Ryhmät, joissa tagi '%s'",
	'groups:search:none' => "Ryhmiä ei löytynyt",
	'groups:search_in_group' => "Etsi tästä ryhmästä",
	'groups:acl' => "Ryhmä: %s",
	'groups:acl:in_context' => 'Ryhmän jäsenet',

	'groups:notfound' => "Ryhmää ei löytynyt",
	
	'groups:requests:none' => 'Ei odottavia jäsenyyspyyntöjä.',

	'groups:invitations:none' => 'Ei odottavia kutsuja.',

	'groups:open' => "avoin ryhmä",
	'groups:closed' => "suljettu ryhmä",
	'groups:member' => "jäsentä",

	'groups:more' => 'Lisää ryhmiä',
	'groups:none' => 'Ei ryhmiä',

	/**
	 * Access
	 */
	'groups:access:private' => 'Suljettu - Jäsenet pitää kutsua',
	'groups:access:public' => 'Avoin - Kuka tahansa voi liittyä',
	'groups:access:group' => 'Vain ryhmän jäsenet',
	'groups:closedgroup' => "Tällä ryhmällä on suljettu jäsenyys.",
	'groups:closedgroup:request' => 'Klikkaa linkkiä "Ano jäsenyyttä" päästäksesi jäseneksi.',
	'groups:closedgroup:membersonly' => "Tällä ryhmällä on suljettu jäsenyys, ja vain jäsenet pääsevät käsiksi sen sisältöihin.",
	'groups:opengroup:membersonly' => "Vain jäsenillä on pääsy tämän ryhmän sisältöihin.",
	'groups:opengroup:membersonly:join' => 'Klikkaa "Liity ryhmään" päästäksesi ryhmän jäseneksi.',
	'groups:visibility' => 'Kuka voi nähdä tämän ryhmän?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Ryhmät',

	'groups:notitle' => 'Ryhmällä pitää olla nimi',
	'groups:cantjoin' => 'Ryhmään liittyminen epäonnistui',
	'groups:cantleave' => 'Ryhmästä eroaminen epäonnistui',
	'groups:removeuser' => 'Poista ryhmästä',
	'groups:cantremove' => 'Käyttäjää ei voida poistaa ryhmästä',
	'groups:removed' => 'Käyttäjä %s poistettiin ryhmästä',
	'groups:addedtogroup' => 'Käyttäjä lisättiin ryhmään',
	'groups:joinrequestnotmade' => 'Ryhmän jäsenyyden anominen epäonnistui',
	'groups:joinrequestmade' => 'Lähetettiin pyyntö päästä ryhmän jäseneksi',
	'groups:joinrequest:exists' => 'Olet jo anonut jäsenyyttä tähän ryhmään',
	'groups:joined' => 'Olet nyt ryhmän jäsen!',
	'groups:left' => 'Olet nyt eronnut ryhmästä',
	'groups:userinvited' => 'Käyttäjä kutsuttu.',
	'groups:usernotinvited' => 'Käyttäjän kutsuminen epäonnistui.',
	'groups:useralreadyinvited' => 'Käyttäjä on jo kutsuttu',
	'groups:invite:subject' => "%s sinut on kutsuttu ryhmän %s jäseneksi!",
	'groups:joinrequest:remove:check' => 'Haluatko varmasti poistaa tämän liittymispyynnön?',
	'groups:invite:remove:check' => 'Haluatko varmasti poistaa tämän kutsun?',

	'groups:welcome:subject' => "Tervetuloa ryhmään %s!",

	'groups:request:subject' => "%s on anonut jäsenyyttä ryhmään %s",

	'groups:allowhiddengroups' => 'Sallitaanko suljettujen (piilotettujen) ryhmien luominen?',
	'groups:whocancreate' => 'Kuka voi luoda uusia ryhmiä?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Kutsu poistettu.',
	'groups:joinrequestkilled' => 'Pyyntö poistettu.',
	'groups:error:addedtogroup' => "Käyttäjän %s lisääminen epäonnistui",
	'groups:add:alreadymember' => "%s on jo tämän ryhmän jäsen",
	
	// Notification settings
);
