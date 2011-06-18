<?php
/**
 * Quick introduction to the theme preview
 *
 * @todo links to resources?
 */
?>

<p>This theme preview provides a visual catalog for many of the theming elements
	that Elgg uses. The primary css selector is listed with each theme element.
	The preview is divided into sections that are listed in the page menu
	(usually in the sidebar but depends on your current theme).
</p>
<p>
<?php
	echo elgg_view('output/url', array(
		'text' => elgg_echo('theme_preview:breakout'),
		'href' => current_page_url(),
		'target' => '_parent',
	));
?>
</p>
