<?php
/**
 * Skin of the theme
 *
 * - theme chrome
 * - page layout
 * - icons
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
	PAGE LAYOUT
*************************************** */

/***** TOPBAR ******/

.elgg-page-topbar {
	background: #333333 url(<?php echo elgg_get_site_url(); ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	border-bottom: 1px solid #000000;
	min-width: 998px;
	position: relative;
	height: 24px;
	z-index: 9000;
}
.elgg-page-topbar > .elgg-inner {
	padding: 2px 10px 2px 8px;
}
.elgg-page-topbar a {
	color: #eeeeee;
	float: left;
	margin: 2px 30px 0 0;
	line-height: 1.1em;
}
.elgg-page-topbar a.elgg-alt {
	float: right;
	margin: 2px 0 0 30px;
}
.elgg-page-topbar a:hover {
	color: #71cbff;
	text-decoration: none;
}
/* elgg logo and user avatar need to be adjusted slightly */
.elgg-page-topbar img {
	margin-top: -1px;
}

/***** PAGE HEADER ******/

.elgg-page-header {
	x-overflow: hidden;
	position: relative;
	background-color: #4690D6;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png);
	background-repeat: repeat-x;
	background-position: bottom left;
}
.elgg-classic .elgg-page-header > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	height: 90px;
	position: relative;
}
.elgg-page-header h1 a {
	font-size: 2em;
	line-height: 1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	text-shadow: 1px 2px 4px #333333;
	text-decoration: none;
}

/***** PAGE BODY ******/

.elgg-page-body > .elgg-inner {
	min-height: 360px;
}
.elgg-classic .elgg-page-body > .elgg-inner {
	width: 990px;
	margin: 0 auto;
}
#elgg-layout-one-column {
	padding: 10px 0;
}
#elgg-layout-sidebar {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif);
	background-repeat: repeat-y;
	background-position: right top;
}
#elgg-layout-two-sidebar {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif);
	background-repeat: repeat-y;
	background-position: right top;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 10px;
}
.elgg-aside {
	padding: 20px 10px;
	position: relative;
	min-height: 360px;
}
.elgg-sidebar {
	float: right;
	width: 210px;
	margin: 0 0 0 10px;
}
.elgg-sidebar.elgg-alt {
	float: left;
	width: 160px;
	margin: 0 10px 0 0;
}
.elgg-main .elgg-header {
    border-bottom: 1px solid #CCCCCC;
    padding-bottom: 3px;
}
.elgg-main .elgg-header h2 {
	float: left;
	max-width: 530px;
	margin-right: 10px;
}

.elgg-main > .elgg-header a {
	float: right;
}

/***** PAGE SIDEBAR ******/

.elgg-sidebar .elgg-module {
	margin-top: 20px;
}
.elgg-sidebar .elgg-module .elgg-head {
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 5px;
	padding-bottom: 5px;
}

/***** PAGE FOOTER ******/

.elgg-page-footer {
	position: relative;
	z-index: 999;
}
.elgg-classic .elgg-page-footer > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 1px solid #DEDEDE;
}
.elgg-page-footer a {
	float: left;
}
.elgg-page-footer a.elgg-alt {
	float: right;
}
.elgg-page-footer .elgg-menu {
	float: left;
	width: 100%;
}
.elgg-page-footer .elgg-inner,
.elgg-page-footer .elgg-inner a,
.elgg-page-footer .elgg-inner p {
	color: #999999;
}
.elgg-page-footer .elgg-inner a:hover {
	color: #666666;
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
	margin: 0 2px;
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
.elgg-icon-delete {
	background-position: -199px 1px;
}
.elgg-icon-delete:hover {
	background-position: -199px -15px;
}
.elgg-icon-likes {
	background-position: 0px -101px;
	width: 20px;
	height: 20px;
}
.elgg-icon-likes:hover {
	background-position: 0px -131px;
}
.elgg-icon-liked {
	background-position: 0px -131px;
	width: 20px;
	height: 20px;
}
.elgg-icon-arrow-s {
	background-position: -146px -56px;
}
.elgg-icon-arrow-s:hover {
	background-position: -146px -76px;
}
.elgg-icon-following {
	background-position: -35px -100px;
	width: 22px;
	height: 20px;
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