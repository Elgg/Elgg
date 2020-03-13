<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Tema rasprave",
	
	'add:object:discussion' => 'Dodaj temu rasprave',
	'edit:object:discussion' => 'Uredi temu',
	'collection:object:discussion' => 'Teme rasprave',
	'collection:object:discussion:group' => 'Grupna rasprava',
	'collection:object:discussion:my_groups' => 'Discussions in my groups',
	
	'discussion:settings:enable_global_discussions' => 'Enable global discussions',
	'discussion:settings:enable_global_discussions:help' => 'Allow discussions to be created outside of groups',

	'discussion:latest' => 'Zadnja rasprava',
	'discussion:none' => 'Nema rasprava',
	'discussion:updated' => "Zadnji komentar od %s %s",

	'discussion:topic:created' => 'Tema rasprave je stvorena. ',
	'discussion:topic:updated' => 'Tema rasprave je izmijenjena. ',
	'entity:delete:object:discussion:success' => 'Tema rasprave je izbrisana. ',

	'discussion:topic:notfound' => 'Tema rasprave nije pronadjena',
	'discussion:error:notsaved' => 'Nije moguće spremiti ovu temu',
	'discussion:error:missing' => 'Naslov i sadržaj poruke su obavezna polja',
	'discussion:error:permissions' => 'Nemate dovoljnu razinu ovlasti za izvršavanje ove akcije',
	'discussion:error:no_groups' => "You're not a member of any groups.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s dodao je novu temu rasprave %s',
	'river:object:discussion:comment' => '%s komentirao je temu rasprave %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nova tema rasprave je %s',
	'discussion:topic:notify:subject' => 'Nova tema rasprave: %s',
	'discussion:topic:notify:body' =>
'%s dodao je novu temu rasprave "%s":

%s

Pogledaj i odgovori na temu rasprace:
%s
',

	'discussion:comment:notify:summary' => 'Novi komentar u temi: %s',
	'discussion:comment:notify:subject' => 'Novi komentar u temu:',
	'discussion:comment:notify:body' =>
'%s komentirao je temu rasprave "%s":

%s

Pregledaj i komentiraj ovu raspravu:',

	'groups:tool:forum' => 'Omogući grupnu raspravu',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status teme',
	'discussion:topic:closed:title' => 'Ova je rasprava zatvorena. ',
	'discussion:topic:closed:desc' => 'Rasprava je zatvorena te nije više moguće komentirati. ',

	'discussion:topic:description' => 'Tema poruke',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Prebaci odgovore na raspravu u komentare",
	'discussions:upgrade:2017112800:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
	'discussions:upgrade:2017112801:title' => "Migrate river activity related to discussion replies",
	'discussions:upgrade:2017112801:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
);
