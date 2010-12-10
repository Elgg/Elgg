<?php
/**
 * Skin of the theme
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	THEME CHROME
*************************************** */
body {
	background-color: white;
}
a {
	color: #4690D6;
}
a:hover,
a.selected {
	color: #555555;
}
/* ***************************************
	ICONS
*************************************** */
.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	display: block;
	float: left;
}
.elgg-icon-settings {
	background-position: -302px -44px;
}
.elgg-icon-friends {
	background-position: 0 -300px;
	width: 36px;
}
.elgg-icon-friends:hover {
	background-position: 0 -340px;
}
.elgg-icon-help {
	background-position: -302px -136px;
}
.elgg-icon-arrow-s {
	background-position: -146px -56px;
}
.elgg-icon-arrow-s:hover {
	background-position: -146px -76px;
}

.ajax-loader {
	background-color: white;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif);
	background-repeat: no-repeat;
	background-position: center center;
	min-height:33px;
	min-width:33px;
}
.ajax-loader.left {
	background-position: left center;
}