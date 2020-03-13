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
	'collection:object:discussion' => 'Discussion topics',
	'collection:object:discussion:group' => 'Ryhmän keskustelut',
	'collection:object:discussion:my_groups' => 'Discussions in my groups',
	
	'discussion:settings:enable_global_discussions' => 'Enable global discussions',
	'discussion:settings:enable_global_discussions:help' => 'Allow discussions to be created outside of groups',

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
	'discussion:error:no_groups' => "You're not a member of any groups.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s added a new discussion topic %s',
	'river:object:discussion:comment' => '%s commented on the discussion topic %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Uusi keskustelu: %s',
	'discussion:topic:notify:subject' => 'Uusi keskustelu: %s',
	'discussion:topic:notify:body' =>
'%s added a new discussion topic "%s":

%s

View and reply to the discussion topic:
%s
',

	'discussion:comment:notify:summary' => 'Uusi vastaus keskustelussa: %s',
	'discussion:comment:notify:subject' => 'Uusi vastaus keskustelussa: %s',
	'discussion:comment:notify:body' =>
'%s commented on the discussion topic "%s":

%s

View and comment on the discussion:
%s
',

	'groups:tool:forum' => 'Ota käyttöön ryhmän keskustelut',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Tila',
	'discussion:topic:closed:title' => 'Tämä keskustelu on suljettu',
	'discussion:topic:closed:desc' => 'Tämä keskustelu on suljettu, eikä siihen voi enää lisätä vastauksia.',

	'discussion:topic:description' => 'Aihe',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Migrate discussion replies to comments",
	'discussions:upgrade:2017112800:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
	'discussions:upgrade:2017112801:title' => "Migrate river activity related to discussion replies",
	'discussions:upgrade:2017112801:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
);
