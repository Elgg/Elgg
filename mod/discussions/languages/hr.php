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

	'discussion:comment:notify:summary' => 'Novi komentar u temi: %s',

	'groups:tool:forum' => 'Omogući grupnu raspravu',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status teme',
	'discussion:topic:closed:title' => 'Ova je rasprava zatvorena. ',
	'discussion:topic:closed:desc' => 'Rasprava je zatvorena te nije više moguće komentirati. ',

	'discussion:topic:description' => 'Tema poruke',
);
