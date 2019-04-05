<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Стране',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Обриши ову  страну",
	'pages:history' => "Историја",
	'pages:view' => "Види страну",
	'pages:revision' => "Ревизија",

	'pages:navigation' => "Навигација",

	'pages:notify:summary' => 'Нова страна названа %s',
	'pages:notify:subject' => "Нова страна: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Више страна',
	'pages:none' => 'Још увек нема креираних страна',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Наслов стране',
	'pages:description' => 'Текст стране',
	'pages:tags' => 'Ознаке',
	'pages:parent_guid' => 'Над страна',
	'pages:access_id' => 'Право читања',
	'pages:write_access_id' => 'Право писања',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Не можете да уредите ову страну',
	'pages:saved' => 'Страна сачувана',
	'pages:notsaved' => 'Није било могуће сачувати ову страну',
	'pages:error:no_title' => 'Морате да унесете наслов за ову стране.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => 'Ревизија стране је успешно обрисана',
	'pages:revision:delete:failure' => 'Ревизија стране није обрисана',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Ревизија направљена %s од стране %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Број страна за приказ',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Види страну",
	'pages:label:edit' => "Уреди страну",
	'pages:label:history' => "Историја стране",

	'pages:newchild' => "Направи под-страну",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
