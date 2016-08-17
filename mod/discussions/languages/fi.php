<?php

return array(
	'discussion' => 'Keskustelut',
	'discussion:add' => 'Lisää uusi keskustelu',
	'discussion:latest' => 'Uusimmat keskustelut',
	'discussion:group' => 'Ryhmän keskustelut',
	'discussion:none' => 'Ei keskusteluja',
	'discussion:reply:title' => 'Vastaus käyttäjältä %s',
	'discussion:new' => "Lisää keskustelu",
	'discussion:updated' => "Viimeisin vastaus käyttäjältä %s %s",

	'discussion:topic:created' => 'Keskustelu lisätty',
	'discussion:topic:updated' => 'Keskustelu tallennettiin',
	'discussion:topic:deleted' => 'Keskustelu poistettu',

	'discussion:topic:notfound' => 'Keskustelua ei löydy',
	'discussion:error:notsaved' => 'Keskustelun tallentaminen epäonnistui',
	'discussion:error:missing' => 'Otsikko ja viesti ovat molemmat pakollisia',
	'discussion:error:permissions' => 'Sinulla ei ole oikeuksia tämän toiminnon tekemiseen',
	'discussion:error:notdeleted' => 'Keskustelun poistaminen epäonnistui',

	'discussion:reply:edit' => 'Muokkaa',
	'discussion:reply:deleted' => 'Viesti poistettu',
	'discussion:reply:error:notfound' => 'Viestiä ei löytynyt',
	'discussion:reply:error:notfound_fallback' => "Haluamaasi viestiä ei löytynyt, joten sinut on ohjattu alkuperäiseen keskustelun aloitukseen",
	'discussion:reply:error:notdeleted' => 'Viestin poistaminen epäonnistui',

	'discussion:search:title' => 'Vastaus keskusteluun: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'Et voi tallentaa tyhjää vastausta',
	'discussion:reply:topic_not_found' => 'Keskustelua ei löytynyt',
	'discussion:reply:error:cannot_edit' => 'Sinulla ei ole oikeuksia tämän vastauksen muokkaamiseen',
	'discussion:reply:error:permissions' => 'You are not allowed to reply to this topic',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s lisäsi uuden keskustelun: %s',
	'river:reply:object:discussion' => '%s vastasi keskusteluun: %s',
	'river:reply:view' => 'Näytä vastaus',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Uusi keskustelu: %s',
	'discussion:topic:notify:subject' => 'Uusi keskustelu: %s',
	'discussion:topic:notify:body' =>
'%s lisäsi uuden keskustelun "%s":

%s

Osallistu keskusteluun:
%s
',

	'discussion:reply:notify:summary' => 'Uusi vastaus keskustelussa: %s',
	'discussion:reply:notify:subject' => 'Uusi vastaus keskustelussa: %s',
	'discussion:reply:notify:body' =>
'%s vastasi keskusteluun "%s":

%s

Osallistu keskusteluun:
%s
',

	'item:object:discussion' => "Keskusteluaiheet",
	'item:object:discussion_reply' => "Keskustelujen vastaukset",

	'groups:enableforum' => 'Ota käyttöön ryhmän keskustelut',

	'reply:this' => 'Vastaa tähän',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Ryhmän keskustelut',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Tila',
	'discussion:topic:closed:title' => 'Tämä keskustelu on suljettu',
	'discussion:topic:closed:desc' => 'Tämä keskustelu on suljettu, eikä siihen voi enää lisätä vastauksia.',

	'discussion:replies' => 'Vastaukset',
	'discussion:addtopic' => 'Lisää keskustelu',
	'discussion:post:success' => 'Keskustelu tallennettu',
	'discussion:post:failure' => 'Keskustelun tallentaminen epäonnistui',
	'discussion:topic:edit' => 'Muokkaa aihetta',
	'discussion:topic:description' => 'Aihe',

	'discussion:reply:edited' => "Vastaus tallennettu",
	'discussion:reply:error' => "Vastauksen tallentaminen epäonnistui",
);
