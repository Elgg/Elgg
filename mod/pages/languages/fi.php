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

	'item:object:page' => 'Wikisivut',
	'collection:object:page' => 'Wikit',
	'collection:object:page:all' => "Kaikki sivuston wikit",
	'collection:object:page:owner' => "Käyttäjän %s wikit",
	'collection:object:page:friends' => "Ystävien wikit",
	'collection:object:page:group' => "Ryhmän wikit",
	'add:object:page' => "Luo uusi wiki",
	'edit:object:page' => "Muokkaa sivua",

	'groups:tool:pages' => 'Ota käyttöön ryhmän wikit',
	
	'annotation:delete:page:success' => 'The page revision was successfully deleted',
	'annotation:delete:page:fail' => 'The page revision could not be deleted',

	'pages:delete' => "Poista tämä sivu",
	'pages:history' => "Historia",
	'pages:view' => "Näytä sivu",
	'pages:revision' => "Versio",

	'pages:navigation' => "Navigaatio",

	'pages:notify:summary' => 'Uusi wikisivu: %s',
	'pages:notify:subject' => "Uusi wikisivu: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Lisää wikisivuja',
	'pages:none' => 'Wikejä ei vielä ole luotu',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Sivun otsikko',
	'pages:description' => 'Sivun sisältö',
	'pages:tags' => 'Tagit',
	'pages:parent_guid' => 'Yläsivu',
	'pages:access_id' => 'Lukuoikeus',
	'pages:write_access_id' => 'Kirjoitusoikeus',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Sinulla ei ole oikeuksia muokata tätä wikisivua',
	'pages:saved' => 'Sivu tallennettu',
	'pages:notsaved' => 'Sivun tallentaminen epäonnistui',
	'pages:error:no_title' => 'Syötä sivulle otsikko.',
	'entity:delete:object:page:success' => 'Sivu poistettiin.',
	'pages:revision:delete:success' => 'Versio poistettu.',
	'pages:revision:delete:failure' => 'Version poistaminen epäonnistui.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Tämän versio luonut %s käyttäjä %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Näytettävien wikien määrä',
	'widgets:pages:name' => 'Wikit',
	'widgets:pages:description' => "Näyttää listan omistamistasi wikeistä.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Näytä sivu",
	'pages:label:edit' => "Muokkaa sivua",
	'pages:label:history' => "Sivun historia",

	'pages:newchild' => "Luo alasivu",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
