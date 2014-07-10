<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Wikit",
	'pages:owner' => "Käyttäjän %s wikit",
	'pages:friends' => "Ystävien wikit",
	'pages:all' => "Kaikki sivuston wikit",
	'pages:add' => "Luo uusi wiki",

	'pages:group' => "Ryhmän wikit",
	'groups:enablepages' => 'Ota käyttöön ryhmän wikit',

	'pages:new' => "Uusi sivu",
	'pages:edit' => "Muokkaa sivua",
	'pages:delete' => "Poista tämä sivu",
	'pages:history' => "Historia",
	'pages:view' => "Näytä sivu",
	'pages:revision' => "Versio",
	'pages:current_revision' => "Tämänhetkinen versio",
	'pages:revert' => "Palauta",

	'pages:navigation' => "Navigaatio",

	'pages:notify:summary' => 'Uusi wikisivu: %s',
	'pages:notify:subject' => "Uusi wikisivu: %s",
	'pages:notify:body' =>
'%s lisäsi uuden wikisivun: %s

%s

Voit lukea sivun täällä:
%s
',
	'item:object:page_top' => 'Päätason wikisivut',
	'item:object:page' => 'Wikisivut',
	'pages:nogroup' => 'Tässä ryhmällä ei ole vielä wikisivuja',
	'pages:more' => 'Lisää wikisivuja',
	'pages:none' => 'Wikejä ei vielä ole luotu',

	/**
	* River
	**/

	'river:create:object:page' => '%s loi wikisivun %s',
	'river:create:object:page_top' => '%s loi wikisivun %s',
	'river:update:object:page' => '%s päivitti wikisivua %s',
	'river:update:object:page_top' => '%s päivitti wikisivua %s',
	'river:comment:object:page' => '%s kommentoi wikisivua %s',
	'river:comment:object:page_top' => '%s kommentoi wikisivua %s',

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
	'pages:noaccess' => 'Sinulla ei ole oikeuksia lukea tätä wikisivua',
	'pages:cantedit' => 'Sinulla ei ole oikeuksia muokata tätä wikisivua',
	'pages:saved' => 'Sivu tallennettu',
	'pages:notsaved' => 'Sivun tallentaminen epäonnistui',
	'pages:error:no_title' => 'Syötä sivulle otsikko.',
	'pages:delete:success' => 'Sivu poistettiin.',
	'pages:delete:failure' => 'Sivun poistaminen epäonnistui.',
	'pages:revision:delete:success' => 'Versio poistettu.',
	'pages:revision:delete:failure' => 'Version poistaminen epäonnistui.',
	'pages:revision:not_found' => 'Versiota ei löydy.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Viimeisin päivitys %s käyttäjältä %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Tämän versio luonut %s käyttäjä %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Näytettävien wikien määrä',
	'pages:widget:description' => "Näyttää listan omistamistasi wikeistä.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Näytä sivu",
	'pages:label:edit' => "Muokkaa sivua",
	'pages:label:history' => "Sivun historia",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Tämä sivu",
	'pages:sidebar:children' => "Alasivut",
	'pages:sidebar:parent' => "Yläsivu",

	'pages:newchild' => "Luo alasivu",
	'pages:backtoparent' => "Takaisin sivulle '%s'",
);
