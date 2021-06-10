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

	'item:object:page' => 'Články',

	'pages:delete' => "Smazat tento článek",
	'pages:history' => "Historie",
	'pages:view' => "Zobrazit článek",
	'pages:revision' => "Revize",

	'pages:navigation' => "Navigace",

	'pages:notify:summary' => 'Nový článek se jménem %s',
	'pages:notify:subject' => "Nový článek: %s",
	'pages:notify:body' =>
'',

	'pages:more' => 'Více článků',
	'pages:none' => 'Zatím nebyly vytvořeny žádné články',

	/**
	* River
	**/
	
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
	'pages:cantedit' => 'Nemůžete upravit tento článek',
	'pages:saved' => 'Článek byl uložen',
	'pages:notsaved' => 'Článek není možné uložit',
	'pages:error:no_title' => 'Musíte zadat název článku.',
	'entity:delete:object:page:success' => 'Článek byl úspěšně smazán.',
	'pages:revision:delete:success' => 'Revize článku byla úspěšně smazána.',
	'pages:revision:delete:failure' => 'Revizi článku není možné smazat.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revize vytvořena %s uživatelem %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Počet zobrazených článků',
	'widgets:pages:description' => "Toto je seznam vašich článků.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Zobrazit článek",
	'pages:label:edit' => "Upravit článek",
	'pages:label:history' => "Historie článku",

	'pages:newchild' => "Vytvořit pod-článek",
);
