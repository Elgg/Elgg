<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Diskussionsämne",
	
	'add:object:discussion' => 'Lägg till diskussionsämne',
	'edit:object:discussion' => 'Redigera ämne',
	'collection:object:discussion' => 'Diskussionsämnen',
	'collection:object:discussion:group' => 'Gruppdiskussioner',
	'collection:object:discussion:my_groups' => 'Diskussioner i min grupp',
	
	'discussion:settings:enable_global_discussions' => 'Aktivera globala diskussioner',
	'discussion:settings:enable_global_discussions:help' => 'Tillåt diskussioner att skapas utanför grupper',

	'discussion:latest' => 'Senaste diskussioner',
	'discussion:none' => 'Inga diskussioner',
	'discussion:updated' => "Senaste kommentar av %s %s",

	'discussion:topic:created' => 'Diskussionsämnet skapades.',
	'discussion:topic:updated' => 'Diskussionsämnet uppdaterades.',
	'entity:delete:object:discussion:success' => 'Diskussionsämnet togs bort.',

	'discussion:topic:notfound' => 'Diskussionsämnet hittades inte',
	'discussion:error:notsaved' => 'Det gick inte att spara det här ämnet',
	'discussion:error:missing' => 'Både titel och meddelande är obligatoriska fält',
	'discussion:error:permissions' => 'Du har inte behörigheter att utföra den här handlingen',
	'discussion:error:no_groups' => "Du är inte medlem i några grupper.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s lade till ett nytt diskussionsämne %s',
	'river:object:discussion:comment' => '%s kommenterade ett nytt diskussionsämne %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nytt diskussionsämne med namnet %s',
	'discussion:topic:notify:subject' => 'Nytt diskussionsämne: %s',

	'discussion:comment:notify:summary' => 'Ny kommentar i ämnet: %s',
	'discussion:comment:notify:subject' => 'Ny kommentar i ämnet: %s',

	'groups:tool:forum' => 'Aktivera gruppdiskussioner',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status för ämnet',
	'discussion:topic:closed:title' => 'Den här diskussionen är stängd.',
	'discussion:topic:closed:desc' => 'Den här diskussionen är stängd och accepterar inte nya kommentarer.',

	'discussion:topic:description' => 'Ämnesmeddelande',
);
