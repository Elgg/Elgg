<?php
/**
 * Language definitions for ECML
 *
 * @package ecml
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$english = array(
	'ecml' => 'ECML',
	'ecml:help' => 'ECML Help',

	/**
	 * Key words
	 */
	'ecml:keywords_title' => 'Keywords',
	'ecml:keywords_instructions' =>
		'Keywords are replaced with content when viewed.  They must be surrounded by
		two square brackets ([[ and ]]).  You can build your own or use the ones listed below.
		Hover over a keyword to read its description.',

	'ecml:keywords_instructions_more' =>
		'
		<p>You can build your own keywords for views and entities.</p>

		<p>[[entity: type=type, subtype=subtype, owner=username, limit=number]]<br />

		EX: To show 5 blog posts by admin:<br />
		[[entity: type=object, subtype=blog, owner=admin, limit=5]]</p>

		<p>You can also specify a valid Elgg view:<br />
		[[view: elgg_view, name=value]]</p>

		<p>Ex: To show a text input with a default value:<br />
		[[view: input/text, value=This is a default value]]</p>',
);

add_translation('en', $english);