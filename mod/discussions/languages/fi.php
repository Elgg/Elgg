<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Keskusteluaiheet",
	
	'add:object:discussion' => 'Lisää uusi keskustelu',
	'edit:object:discussion' => 'Muokkaa aihetta',
	'collection:object:discussion:group' => 'Ryhmän keskustelut',

	'discussion:latest' => 'Uusimmat keskustelut',
	'discussion:none' => 'Ei keskusteluja',
	'discussion:updated' => "Viimeisin vastaus käyttäjältä %s %s",

	'discussion:topic:created' => 'Keskustelu lisätty',
	'discussion:topic:updated' => 'Keskustelu tallennettiin',
	'entity:delete:object:discussion:success' => 'Keskustelu poistettu',

	'discussion:topic:notfound' => 'Keskustelua ei löydy',
	'discussion:error:notsaved' => 'Keskustelun tallentaminen epäonnistui',
	'discussion:error:missing' => 'Otsikko ja viesti ovat molemmat pakollisia',
	'discussion:error:permissions' => 'Sinulla ei ole oikeuksia tämän toiminnon tekemiseen',

	/**
	 * River
	 */
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Uusi keskustelu: %s',
	'discussion:topic:notify:subject' => 'Uusi keskustelu: %s',

	'discussion:comment:notify:summary' => 'Uusi vastaus keskustelussa: %s',
	'discussion:comment:notify:subject' => 'Uusi vastaus keskustelussa: %s',

	'groups:tool:forum' => 'Ota käyttöön ryhmän keskustelut',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Tila',
	'discussion:topic:closed:title' => 'Tämä keskustelu on suljettu',
	'discussion:topic:closed:desc' => 'Tämä keskustelu on suljettu, eikä siihen voi enää lisätä vastauksia.',

	'discussion:topic:description' => 'Aihe',
);
