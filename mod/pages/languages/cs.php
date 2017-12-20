<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Články",
	'pages:owner' => "%s - články",
	'pages:friends' => "Články přátel",
	'pages:all' => "Všechny články",
	'pages:add' => "Přidat článek",

	'pages:group' => "Články skupiny",
	'groups:enablepages' => 'Povolit skupinové články',

	'pages:new' => "Nový článek",
	'pages:edit' => "Upravit tento článek",
	'pages:delete' => "Smazat tento článek",
	'pages:history' => "Historie",
	'pages:view' => "Zobrazit článek",
	'pages:revision' => "Revize",
	'pages:current_revision' => "Současná revize",
	'pages:revert' => "Vrátit",

	'pages:navigation' => "Navigace",

	'pages:notify:summary' => 'Nový článek se jménem %s',
	'pages:notify:subject' => "Nový článek: %s",
	'pages:notify:body' =>
'%s přidal/a nový článek: %s

%s

Zobrazení a komentáře ke článku:
%s
',
	'item:object:page_top' => 'Články na nejvyšší úrovni',
	'item:object:page' => 'Články',
	'pages:nogroup' => 'Skupina zatím nemá žádné články',
	'pages:more' => 'Více článků',
	'pages:none' => 'Zatím nebyly vytvořeny žádné články',

	/**
	* River
	**/

	'river:create:object:page' => '%s vytvořil/a článek %s',
	'river:create:object:page_top' => '%s vytvořil/a článek %s',
	'river:update:object:page' => '%s aktualizoval/a článek %s',
	'river:update:object:page_top' => '%s aktualizoval/a článek %s',
	'river:comment:object:page' => '%s přidal/a komentář ke článku s názvem %s',
	'river:comment:object:page_top' => '%s přidal/a komentář ke článku s názvem %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Název článku',
	'pages:description' => 'Text článku',
	'pages:tags' => 'Štítky',
	'pages:parent_guid' => 'Nadřazená stránka',
	'pages:access_id' => 'Ke čtení',
	'pages:write_access_id' => 'Pro zápis',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'K tomuto článku nemáte přístup',
	'pages:cantedit' => 'Nemůžete upravit tento článek',
	'pages:saved' => 'Článek byl uložen',
	'pages:notsaved' => 'Článek není možné uložit',
	'pages:error:no_title' => 'Musíte zadat název článku.',
	'pages:delete:success' => 'Článek byl úspěšně smazán.',
	'pages:delete:failure' => 'Článek není možné smazat.',
	'pages:revision:delete:success' => 'Revize článku byla úspěšně smazána.',
	'pages:revision:delete:failure' => 'Revizi článku není možné smazat.',
	'pages:revision:not_found' => 'Nemohu najít tuto revizi.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Naposledy změněno %s uživatelem %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revize vytvořena %s uživatelem %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Počet zobrazených článků',
	'pages:widget:description' => "Toto je seznam vašich článků.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Zobrazit článek",
	'pages:label:edit' => "Upravit článek",
	'pages:label:history' => "Historie článku",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Tento článek",
	'pages:sidebar:children' => "Pod-články",
	'pages:sidebar:parent' => "Nadřazený",

	'pages:newchild' => "Vytvořit pod-článek",
	'pages:backtoparent' => "Zpět k '%s'",
);
