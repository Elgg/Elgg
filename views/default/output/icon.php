<?php
/**
* Display an icon from the FontAwesome library.
*
* @package Elgg
* @subpackage Core
*
* @uses $vars['class']   Class of elgg-icon
* @uses $vars['convert'] Convert an elgg-icon class to a FontAwesome class (default: true)
*/

// these 'old' Elgg 1.x sprite icons will be converted to the FontAwesome version
$translated_icons = array(
	"arrow-two-head" => "arrows-h",
	"attention" => "exclamation-triangle",
	"cell-phone" => "mobile",
	"checkmark" => "check",
	"clip" => "paperclip",
	"cursor-drag-arrow" => "arrows",
	"drag-arrow" => "arrows", // 'old' admin sprite
	"delete-alt" => "times-circle",
	"delete" => "times",
	"facebook" => "facebook-square",
	"grid" => "th",
	"hover-menu" => "caret-down",
	"info" => "info-circle",
	"lock-closed" => "lock",
	"lock-open" => "unlock",
	"mail" => "envelope-o",
	"mail-alt" => "envelope",
	"print-alt" => "print",
	"push-pin" => "thumb-tack",
	"push-pin-alt" => "thumb-tack",
	"redo" => "share",
	"round-arrow-left" => "arrow-circle-left",
	"round-arrow-right" => "arrow-circle-right",
	"round-checkmark" => "check-circle",
	"round-minus" => "minus-circle",
	"round-plus" => "plus-circle",
	"rss" => "rss-square",
	"search-focus" => "search",
	"settings" => "wrench",
	"settings-alt" => "cog",
	"share" => "share-alt-square",
	"shop-cart" => "shopping-cart",
	"speech-bubble" => "comment",
	"speech-bubble-alt" => "comments",
	"star-alt" => "star",
	"star-empty" => "star-o",
	"thumbs-down-alt" => "thumbs-down",
	"thumbs-up-alt" => "thumbs-up",
	"trash" => "trash-o",
	"twitter" => "twitter-square",
	"undo" => "reply",
	"video" => "film"
);

$convert = (bool) elgg_extract('convert', $vars, true);
unset($vars['convert']);

$class = elgg_extract_class($vars, ["elgg-icon", "fa"]);

foreach ($class as $index => $c) {
	if (preg_match_all('/^elgg-icon-(.+)/i', $c)) {
		// convert
		$base_icon = preg_replace('/^elgg-icon-(.+)/i', '$1', $c);
		if ($convert) {
			$base_icon = elgg_extract($base_icon, $translated_icons, $base_icon);
		}
		$class[] = "fa-{$base_icon}";
	}
}

$vars["class"] = array_unique($class);

echo elgg_format_element('span', $vars, '');
