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
	'thewire' => "Nyhetsflöde",

	'item:object:thewire' => "Nyhetsinlägg",
	'collection:object:thewire' => 'Nyhetsinlägg',
	'collection:object:thewire:all' => "Alla nyhetsinlägg",
	'collection:object:thewire:owner' => "%ss nyhetsinlägg",
	'collection:object:thewire:friends' => "Vänners nyhetsinlägg",

	'thewire:replying' => "Svara %s (@%s) som skrev",
	'thewire:thread' => "Tråd",
	'thewire:charleft' => "Tecken kvar",
	'thewire:tags' => "Nyhetsinlägg taggad med '%s'",
	'thewire:noposts' => "Inga nyhetsinlägg än",

	'thewire:by' => 'Nyhetsinlägg av %s',

	'thewire:form:body:placeholder' => "Vad händer?",
	
	/**
	 * The wire river
	 */
	'river:object:thewire:create' => "%s skickades till %s",
	'thewire:wire' => 'nyhetsflödet',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'Visa dina senaste nyhetsinlägg',
	'thewire:num' => 'Antal inlägg att visa',
	'thewire:moreposts' => 'Fler nyhetsinlägg',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Ditt meddelande skickades till nyhetsflödet.",
	'thewire:deleted' => "Nyhetsinlägget togs bort.",
	'thewire:blank' => "Tyvärr, du måste skriva något innan du kan skicka det här.",
	'thewire:notsaved' => "Tyvärr kunde vi inte spara det här nyhetsinlägget",
	'thewire:notdeleted' => "Tyvärr kunde vi inte ta bort det här nyhetsinlägget.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'Nytt nyhetsinlägg: %s',
	'thewire:notify:subject' => "Nytt nyhetsinlägg från %s",
	'thewire:notify:reply' => '%s svarade %s i nyhetsflödet:',
	'thewire:notify:post' => '%s skrev ett nyhetsinlägg:',
	'thewire:notify:footer' => "Visa och svara:\n%s",

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Maximalt antal tecken för meddelanden i nyhetsflödet:",
	'thewire:settings:limit:none' => "Ingen begränsning",
);
